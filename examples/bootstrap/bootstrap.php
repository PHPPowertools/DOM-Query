<?php

defined('LOCAL_PATH_BOOTSTRAP') || define("LOCAL_PATH_BOOTSTRAP", __DIR__);

// ---------------------------------------------------------------------------
// DEFINE ROOT PATHS
// ---------------------------------------------------------------------------
define("RELATIVE_PATH_ROOT", '');
define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("HTTP_PATH_ROOT", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_'));

// ---------------------------------------------------------------------------
// DEFINE RELATIVE PATHS
// ---------------------------------------------------------------------------
define("RELATIVE_PATH_BASE", dirname(str_replace(LOCAL_PATH_ROOT, RELATIVE_PATH_ROOT, $_SERVER["SCRIPT_FILENAME"])));
define("RELATIVE_PATH_APP", dirname(RELATIVE_PATH_BASE));
define("RELATIVE_PATH_LIBRARY", RELATIVE_PATH_APP . DIRECTORY_SEPARATOR . 'vendor');
define("RELATIVE_PATH_TEMPLATE", RELATIVE_PATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define("RELATIVE_PATH_CONFIG", RELATIVE_PATH_BASE . DIRECTORY_SEPARATOR . 'config');
define("RELATIVE_PATH_PAGES", RELATIVE_PATH_BASE . DIRECTORY_SEPARATOR . 'pages');
define("RELATIVE_PATH_ASSET", RELATIVE_PATH_BASE . DIRECTORY_SEPARATOR . 'assets');
define("RELATIVE_PATH_ASSET_IMG", RELATIVE_PATH_ASSET . DIRECTORY_SEPARATOR . 'img');
define("RELATIVE_PATH_ASSET_CSS", RELATIVE_PATH_ASSET . DIRECTORY_SEPARATOR . 'css');

// ---------------------------------------------------------------------------
// DEFINE LOCAL PATHS
// ---------------------------------------------------------------------------
define("LOCAL_PATH_BASE", LOCAL_PATH_ROOT . RELATIVE_PATH_BASE);
define("LOCAL_PATH_APP", LOCAL_PATH_ROOT . RELATIVE_PATH_APP);
define("LOCAL_PATH_LIBRARY", LOCAL_PATH_ROOT . RELATIVE_PATH_LIBRARY);
define("LOCAL_PATH_TEMPLATE", LOCAL_PATH_ROOT . RELATIVE_PATH_TEMPLATE);
define("LOCAL_PATH_CONFIG", LOCAL_PATH_ROOT . RELATIVE_PATH_CONFIG);
define("LOCAL_PATH_PAGES", LOCAL_PATH_ROOT . RELATIVE_PATH_PAGES);
define("LOCAL_PATH_ASSET", LOCAL_PATH_ROOT . RELATIVE_PATH_ASSET);
define("LOCAL_PATH_ASSET_IMG", LOCAL_PATH_ROOT . RELATIVE_PATH_ASSET_IMG);
define("LOCAL_PATH_ASSET_CSS", LOCAL_PATH_ROOT . RELATIVE_PATH_ASSET_CSS);

// ---------------------------------------------------------------------------
// DEFINE URL PATHS
// ---------------------------------------------------------------------------
define("HTTP_PATH_BASE", HTTP_PATH_ROOT . RELATIVE_PATH_BASE);
define("HTTP_PATH_APP", HTTP_PATH_ROOT . RELATIVE_PATH_APP);
define("HTTP_PATH_LIBRARY", false);
define("HTTP_PATH_TEMPLATE", false);
define("HTTP_PATH_CONFIG", false);
define("HTTP_PATH_PAGES", false);
define("HTTP_PATH_ASSET", HTTP_PATH_ROOT . RELATIVE_PATH_ASSET);
define("HTTP_PATH_ASSET_IMG", HTTP_PATH_ROOT . RELATIVE_PATH_ASSET_IMG);
define("HTTP_PATH_ASSET_CSS", HTTP_PATH_ROOT . RELATIVE_PATH_ASSET_CSS);

// ---------------------------------------------------------------------------
// DEFINE REQUEST PARAMETERS
// ---------------------------------------------------------------------------
define("REQUEST_QUERY", isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != '' ? $_SERVER["QUERY_STRING"] : false);
define("REQUEST_METHOD", isset($_SERVER["REQUEST_METHOD"]) ? strtoupper($_SERVER["REQUEST_METHOD"]) : false);
define("REQUEST_STATUS", isset($_SERVER["REDIRECT_STATUS"]) ? $_SERVER["REDIRECT_STATUS"] : false);
define("REQUEST_PROTOCOL", isset($_SERVER["HTTP_ORIGIN"]) ? substr($_SERVER["HTTP_ORIGIN"], 0, strpos($_SERVER["HTTP_ORIGIN"], '://') + 3) : 'http://');
define("REQUEST_PATH", isset($_SERVER["REQUEST_URI"]) ? str_replace(RELATIVE_PATH_BASE, '', $_SERVER["REQUEST_URI"]) : '_UNKNOWN_');
define("REQUEST_PATH_STRIP_QUERY", REQUEST_QUERY ? str_replace('?' . REQUEST_QUERY, '', REQUEST_PATH) : REQUEST_PATH);

// ---------------------------------------------------------------------------
// DEFINE SITE PARAMETERS
// ---------------------------------------------------------------------------
define("PRODUCTION", false);
define("PAGE_PATH_DEFAULT", DIRECTORY_SEPARATOR . 'template');
define("PAGE_PATH", (REQUEST_PATH_STRIP_QUERY === DIRECTORY_SEPARATOR) ? PAGE_PATH_DEFAULT : REQUEST_PATH_STRIP_QUERY);

// ---------------------------------------------------------------------------
// INITIALIZE AUTOLOADER
// ---------------------------------------------------------------------------
require LOCAL_PATH_LIBRARY . DIRECTORY_SEPARATOR . 'Loader.php';
\Loader::init(array(LOCAL_PATH_LIBRARY));

function load_config($path) {
    return json_decode(file_get_contents(LOCAL_PATH_CONFIG . DIRECTORY_SEPARATOR . $path));
}

function load_template($path) {
    $path = LOCAL_PATH_TEMPLATE . DIRECTORY_SEPARATOR . $path;
    $pathinfo = pathinfo($path);
    if ($pathinfo['extension'] == 'html') {
        return \PowerTools\DOM_Query::loadHTML(file_get_contents($path));
    } else {
        return \PowerTools\DOM_Query::loadHTML('<pre>' . htmlentities(file_get_contents($path)) . '</pre>');
    }
}

function load_external_data($path) {
    return \PowerTools\DOM_Query::loadHTML(file_get_contents($path));
}

?>