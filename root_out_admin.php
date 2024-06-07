<?php

// this is the main entry point of the app.
// this assumes that composer is already installed and const.php was required

require_once ROOT_PATH.'/admin/vendor/autoload.php';

// create root directories
$dirs = ['tmp', 'cache', 'logs', 'archive'];
foreach ($dirs as $dir) {
	$path = ROOT_PATH.'/'.$dir;
	if (!file_exists($path)) {
	    mkdir($path, 0777, true);
	}
}

// load configuration from .env
$dotenv = Dotenv\Dotenv::create(ROOT_PATH.'/admin', '.env');
$dotenv->load();

// global functions
//require_once ROOT_PATH.'/config.php';
require_once ROOT_PATH.'/admin/lib/func.php';

// Moment
\Moment\Moment::setDefaultTimezone(date_default_timezone_get());

// initialize app class
$app = \App\App::instance();