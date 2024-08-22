<?php

namespace App\Classes;

class Paginator extends Database
{
    private $totalPages;
    private static $limit = 51;
    private $currentPage;
    private $offset;

    private function setTotalPages()
    {
        $data = self::select('partners', needle: 'COUNT(*) as total');
        $row = $data->fetch_assoc();
        $totalRecords = $row['total'];
        $this->totalPages = ceil($totalRecords / self::$limit);
    }
    public function __construct()
    {
        $this->currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->offset = ($this->currentPage - 1) * self::$limit;
        $this->setTotalPages();
    }

    public function showPartners($table = 'partners')
    {
        $data = $this->select($table, $this->offset);

        foreach ($data as $el) {
            [$id, $name, $detailUrl, $address] = array_values($el);
            $html = <<<HTML
                <div class="partner-row">
                    <p>ID записи: $id</p>
                    <p>Партнер: <a href="$detailUrl" target="_blank">$name</a></p>
                    <p><a href="$address" target="_blank">Адрес сайта партнера</a></p>
                    <p><a href ="$detailUrl" target="_blank">Детальная страница на битриксе</a></p>
                    <p><a href="/?projects=true&id=$id" target="_blank">Проекты партнера</a></p>
                </div>                        
            HTML;

            echo $html;
        }
    }

    public function showPartnerProjects()
    {
        $whereArg = "partner_id = {$_GET['id']}";
        // $data = $this->select('projects', whereArg: $whereArg);
        $data = $this->query("SELECT * FROM projects WHERE $whereArg");

        foreach ($data as $el) {
            [$id,, $projectUrl, $productVersion, $description] = array_values($el);
            $html = <<<HTML
                <div class="project-row">
                    <p>ID проекта: $id</p>
                    <p>Адрес проекта: <a href="$projectUrl" target="_blank">$projectUrl</a></p>
                    <p>Версия продукта: $productVersion</p>
                    <p>Описание проекта: $description</p>
                </div>
            HTML;

            echo $html;
        }
    }

    public function showTotalPages()
    {
        foreach (range(1, $this->totalPages) as $number) {
            echo "<a class='pagination' href='?page=$number'>$number</a>";
        }
    }
}
