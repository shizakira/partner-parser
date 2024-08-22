<?php
ini_set('max_execution_time', '0');
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(true);

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use App\Parser;
use App\Database;
use App\Model;
use App\Paginator;
use DiDom\Document;

Database::init('db', 'user', 'user_password', 'mydb');

$parser = new Parser();
// $parser->parsAllPartners();
// $parser->parsAllProjects();

$model = new Model();
// $model->writePartners();
// $model->writeProjects();

$paginator = new Paginator();

require 'view.php';
