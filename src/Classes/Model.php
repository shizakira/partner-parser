<?php

namespace App\Classes;

use App\Classes\Traits\Filer;

class Model extends Database
{
    use Filer;

    private $partners = ['id', 'name', 'details_url', 'website'];
    private $projects = ['partner_id', 'project_url', 'product_version', 'description'];
    private $partnersPath;
    private $projectsPath;


    public function __construct($partnersPath, $projectsPath)
    {
        $this->partnersPath = $partnersPath;
        $this->projectsPath = $projectsPath;
    }

    public function writePartners()
    {
        $rows = $this->getElemsFromRow($this->partnersPath);

        foreach ($rows as $row) {
            $row = explode('<#>', $row);

            if (trim($row[0]) === '') {
                break;
            }

            $this->insert('partners', $this->partners, $row);
        }

        echo "Data inserted successfully";
    }

    public function writeProjects()
    {
        $rows = $this->getElemsFromRow($this->projectsPath);
        $partnerProjects = '';

        foreach ($rows as $row) {
            $partnerProjects .= $row;

            if (!strpos($row, '</row>')) {
                continue;
            }

            $pattern = '/<row>(.*?)<\/row>/us';
            preg_match($pattern, $partnerProjects, $match);

            $data = explode('<#>', $match[1]);
            $this->insert('projects', $this->projects, $data);

            $partnerProjects = '';
        }

        echo "Data inserted successfully";
    }
}
