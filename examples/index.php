<?php

define("LOCAL_PATH_BOOTSTRAP", __DIR__);
require LOCAL_PATH_BOOTSTRAP . DIRECTORY_SEPARATOR . 'bootstrap.php';


// LOAD TEMPLATE
// ---------------
$H = load_template('layout.html');

// LOAD DATA
// ---------------
$data = array(
    'meta' => load_config('meta.json'),
    'stylesheets' => load_config('stylesheets.json'),
    'menuitems' => load_config('menu.json'),
    'main' => $H->select('<div class="col"></div>')
);

// PROCESS PAGE
// ---------------

require LOCAL_PATH_PAGES . PAGE_PATH . '.php';

// PROCESS HEAD
// ---------------
$head = $H->select('head')
        ->append($H->select('<link rel="shortcut icon" type="image/x-icon">')->attr(array('href' => REQUEST_PROTOCOL . HTTP_PATH_ASSET_IMG . '/' . $data['meta']->favicon)))
        ->append($H->select('<title />')->text($data['meta']->title))
        ->append($H->select('<meta  name="description" />')->attr(array('content' => $data['meta']->description)))
        ->append($H->select('<meta />')->attr(array('charset' => $data['meta']->charset)))
        ->append($H->select('<meta  name="viewport" />')->attr(array('content' => $data['meta']->viewport)));
foreach ($data['stylesheets'] as $stylesheet) {
    $head->append($H->select('<link rel="stylesheet" />')->attr(array('href' => REQUEST_PROTOCOL . HTTP_PATH_ASSET_CSS . '/' . $stylesheet->url)));
}

// PROCESS BODY
// --------------
$body = $H->select('body');
$bodyparts = array(
    'siteheader' => $body->select('.site-header')->append(load_template('/bodyparts/site-header.html'))->select('#site-header-content'),
    'masthead' => $body->select('.masthead')->append(load_template('/bodyparts/masthead.html'))->select('#masthead-content'),
    'sitebody' => $body->select('.site-body')->append(load_template('/bodyparts/site-body.html'))->select('#site-body-content'),
    'sitefooter' => $body->select('.site-footer')->append(load_template('/bodyparts/site-footer.html'))->select('#site-footer-content')
);

$bodyparts['siteheader']->select('h3')->text($data['meta']->title);

$sitebodyparts = array(
    'menu' => $bodyparts['sitebody']->append(load_template('/sitebodyparts/menu.html')),
    'main' => $bodyparts['sitebody']->append(load_template('/sitebodyparts/main.html'))
);

$sitebodyparts['main']->select('#main-content')->append($data['main']);

// ADD MENU
// -------------
$navigation = $sitebodyparts['menu']->select('ul');
foreach ($data['menuitems'] as $menuitem) {
    $navigationitem = $H->select('<li><a href="#" class=""></a></li>');
    if ('/' . $menuitem->url == PAGE_PATH) {
        $navigationitem->addClass('active')->select('a')->attr(array('href' => '#'))->text($menuitem->label);
    } else {
        $navigationitem->select('a')->attr(array('href' => $menuitem->url))->text($menuitem->label);
    }
    $navigation->append($navigationitem);
}

echo $H;
?>