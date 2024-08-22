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

$parser = new Parser($partnersPath, $projectsPath, $logPath);
$model = new Model($partnersPath, $projectsPath);

if (!empty($_POST['parse']) && $_POST['parse'] === '1') {
    $parser->parse();
    $model->writeToDB();
}

$paginator = new Paginator();

if ($model->isTablesNotEmpty()) {
    require 'View/view.php';
} else {
    require 'View/parse.php';
}
