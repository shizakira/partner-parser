<?php

namespace App\Classes;

use DiDom\Document;
use App\Classes\Traits\Filer;

class Parser
{
    use Filer;

    private $baseUrl = 'https://www.1c-bitrix.ru/partners/';
    private $rootUrl = 'https://www.1c-bitrix.ru';
    private $filePath;
    private $logPath;
    private $projectsPath;
    private $maxPagenValue;

    public function __construct($partnersPath, $projectsPath, $logPath, $maxPagenValue = 120)
    {
        $this->filePath = $partnersPath;
        $this->projectsPath = $projectsPath;
        $this->logPath = $logPath;
        $this->maxPagenValue = $maxPagenValue;
    }

    public function parsAllProjects()
    {
        $rows = $this->getElemsFromRow($this->filePath);

        foreach ($rows as $row) {
            $row = explode('<#>', $row);

            @[$id,, $url] = $row;

            $page = $this->getDocument($url);
            $projects = $page->find('.partner-project-pane__inner');

            $this->parsPartnerProjects($projects, $id);
        }

        echo 'Projects was parsed';
    }

    public function parsAllPartners()
    {
        $endpoint = 'index_ajax.php?PAGEN_1=';
        $pagenValue = 1;

        while ($pagenValue <= $this->maxPagenValue) {
            $baseDocument = $this->getDocument($this->baseUrl . $endpoint . $pagenValue++);
            $this->parsPartnersBlock($baseDocument);
        }

        echo 'Partners was parsed';
    }

    private function parsPartnerProjects($projects, $id)
    {
        foreach ($projects as $project) {
            $projectURL = $this->rootUrl . $project->getAttribute('href');

            if ($this->isFileNotFound($projectURL)) {
                continue;
            }

            $projectPage = $this->getDocument($projectURL);

            $projectEditorial = $this->getData($projectPage->first('.detail-page-list__item-record_value'));
            $projectDesc = $this->deleteEmptyRows($this->getData($projectPage->first('.detail-page-case')));

            $elements = $projectPage->find(".detail-page-list__item-record_value");

            if (isset($elements[3])) {
                $projectAddress = $this->getData($elements[3]->first('a')->getAttribute('href'), false);
            } else {
                $projectAddress = 'Unknown Address';
            }

            $template = '<row>%s<#>%s<#>%s<#>%s</row>';
            $row = sprintf($template, $id, $projectAddress, $projectEditorial, $projectDesc);

            $this->writeToFile($this->projectsPath, $row);
        }
    }

    private function getData($value, $flag = true)
    {
        try {
            if ($flag) {
                return $value->text();
            }
            return $value;
        } catch (\Throwable $e) {
            $this->writeErrorToLog($value, $e);
            return 'Unknown';
        }
    }

    private function parsPartnersBlock($baseDocument)
    {
        $endpoints = $baseDocument->find('.bx-ui-tile__main-link');

        foreach ($endpoints as $endpoint) {
            $detailUrl = $this->getDetailUrl($endpoint);
            $partnerPage = $this->getDocument($detailUrl);
            $name = $this->getPartnerName($partnerPage, $detailUrl);
            $partnerAddress = $this->getPartnerAddress($partnerPage, $detailUrl);
            $content = "{$this->getNewId()}<#>{$name}<#>{$detailUrl}<#>{$partnerAddress}";

            $this->writeToFile($this->filePath, $content);
        }
    }

    private function getDetailUrl($partner)
    {
        return $this->baseUrl . ltrim($partner->getAttribute('href'), './');
    }

    private function getPartnerName($document, $detailUrl)
    {
        try {
            return trim($document->first('.partner-card-profile-header-title')->text());
        } catch (\Throwable $e) {
            $this->writeErrorToLog($detailUrl, $e);
            return 'Unknown Name';
        }
    }

    private function getPartnerAddress($document, $detailUrl)
    {
        try {
            return $document->first('.simple-link')->getAttribute('href');
        } catch (\Throwable $e) {
            $this->writeErrorToLog($detailUrl, $e);
            return 'Unknown Address';
        }
    }

    private function isFileNotFound($url)
    {
        return get_headers($url)[0] === 'HTTP/1.1 404 Not Found';
    }

    private function getDocument($url)
    {
        while (true) {
            try {
                return new Document($url, true);
            } catch (\Throwable $e) {
                $this->writeErrorToLog($url, $e);
                sleep(20);
            }
        }
    }

    private function getNewId()
    {
        static $id = 1;
        return $id++;
    }
}
