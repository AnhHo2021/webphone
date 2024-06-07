<?php

define('ROOT_PATH', __DIR__);
define('PUBLIC_PATH', __DIR__.'/admin/public');
define('DS', DIRECTORY_SEPARATOR);
define('EOL', PHP_EOL);

if (!isset($argv)) {
	$argv = [isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__];
	$args_list = isset($_GET['argv']) && is_array($_GET['argv']) ? $_GET['argv'] : $_GET;

	foreach ($args_list as $key => $value) {
		if ($value) $argv[] = $value;
	}
}

if (php_sapi_name() == 'cli' || !isset($_SERVER["REQUEST_METHOD"])) {
	$document_root = realpath(getenv('CLI_DOCUMENT_ROOT') ?: PUBLIC_PATH_OUT);
	$server_name = gethostname();
	$request_uri = str_replace(DS_OUT, '/', substr($_SERVER['PHP_SELF'], strlen($document_root)));
	$request_method = 'CLI';
} else {
	$document_root = realpath($_SERVER['DOCUMENT_ROOT']);
	$server_name = $_SERVER['SERVER_NAME'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_method = $_SERVER['REQUEST_METHOD'];
}

$document_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$server_name;

$app_path = $document_root;
$app_url = $document_url;

if (strpos(PUBLIC_PATH, $document_root) === 0) {
	$app_uri = substr(PUBLIC_PATH, strlen($document_root));
	$app_path .= $app_uri;
	$app_url .= str_replace(DS, '/', $app_uri);
}

define('DOCUMENT_ROOT', $document_root);
define('SERVER_NAME', $server_name);
define('SERVER_URL', $document_url);
define('SERVER_REQUEST', $document_url.$request_uri);

define('REQUEST_URI', $request_uri);
define('REQUEST_METHOD', $request_method);

define('APP_PATH', $app_path);
define('APP_URL', $app_url);

define('APP_LOGS_PATH', ROOT_PATH.'/logs');
define('APP_ARCHIVE_PATH', ROOT_PATH.'/archive');
define('APP_CACHE_PATH', ROOT_PATH.'/cache');
define('APP_TMP_PATH', ROOT_PATH.'/tmp');

define('ASSETS_URL', APP_URL.'/assets');
define('ASSETS_PATH', APP_PATH.'/assets');
define('DOCS_PATH', APP_PATH.'/docs');
define('INCLUDES_PATH', APP_PATH.'/includes');

define('API_URL', APP_URL.'/api');

define('AJAX_URL', APP_URL.'/ajax');

define('ROLE_SUPER_ADMIN', "super_admin");
define('ROLE_COMPANY_MANAGER', "company_manager");
define('ROLE_BRANCH_MANAGER', "branch_manager");
define('ROLE_USER', "user");
