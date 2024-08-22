<?php

namespace App;

use App\Database;

class Model extends Database
{
    use Filer;

    private $partners = ['id', 'name', 'details_url', 'website'];
    private $projects = ['partner_id', 'project_url', 'product_version', 'description'];

    public function writePartners()
    {
        $rows = $this->getElemsFromRow('partners_data.txt');

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
        $rows = $this->getElemsFromRow('projects.txt');
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
    }
}
