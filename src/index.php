<?php
ini_set('max_execution_time', '0');
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(true);

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
require 'config.php';

use App\Classes\Parser;
use App\Classes\Database;
use App\Classes\Model;
use App\Classes\Paginator;

Database::init(...$dbParams);

$parser = new Parser($partnersPath, $projectsPath, $logPath);
// $parser->parsAllPartners();
// $parser->parsAllProjects();

$model = new Model($partnersPath, $projectsPath);
// $model->writePartners();
// $model->writeProjects();

$paginator = new Paginator();

require 'View/view.php';
