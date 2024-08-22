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

        $sql = 'CREATE TABLE IF NOT EXISTS`partners` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `details_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
                    `website` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;';
        $this->query($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `projects` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `partner_id` INT NOT NULL,
                    `project_url` VARCHAR(255) NOT NULL,
                    `product_version` VARCHAR(255) DEFAULT NULL,
                    `description` TEXT,
                    PRIMARY KEY (`id`),
                    KEY `partner_id_idx` (`partner_id`),
                    CONSTRAINT `fk_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
                    ';
        $this->query($sql);
    }

    public function isTablesNotEmpty()
    {
        $sql = "SELECT 'partners' AS table_name, COUNT(*) > 0 AS not_empty
                FROM partners
                UNION
                SELECT 'projects' AS table_name, COUNT(*) > 0 AS not_empty
                FROM projects;";

        $result = $this->query($sql);

        foreach ($result as $row) {
            if (!$row['not_empty']) {
                return false;
            }
        }

        return true;
    }

    public function writeToDB()
    {
        $this->writePartners();
        $this->writeProjects();
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
