<?php
ini_set('max_execution_time', '0');
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(true);

require 'config.php';

use App\Classes\Parser;
use App\Classes\Database;
use App\Classes\Model;
use App\Classes\Paginator;

Database::init(...$dbParams);

if (!empty($_POST['parse']) && $_POST['parse'] === '1') {
    $parser = new Parser($partnersPath, $projectsPath, $logPath);
    $parser->parsAllPartners();
    $parser->parsAllProjects();

    $model = new Model($partnersPath, $projectsPath);
    $model->writePartners();
    $model->writeProjects();
}

$paginator = new Paginator();

try {
    require 'View/view.php';
} catch (Exception) {
    require 'View/parse.php';
}
