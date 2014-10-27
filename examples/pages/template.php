<?php
// Load panel template
$paneltemplate = load_template('/panel.html');

// Add page title
$data['main']->append('<div class="col"><div class="cell"><h1>Template processing</h1></div></div>');

$data['main']->append(load_template('/pages/template.html'));

//Add code from code.php
$panel = $H->select($paneltemplate);
$panel->select('.body')->append(load_template('/pages/template/code.php'));
$data['main']->append($panel);