<?php

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