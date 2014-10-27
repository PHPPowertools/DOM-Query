<?php
// Load panel template
$paneltemplate = load_template('/panel.html');

// Get external content
$highlights = load_external_data('http://www.cascade-framework.com/')->select('.highlights > .col');
$author = load_external_data('http://www.johnslegers.com/')->select('.half .sizefill');
$cascadeframework = load_external_data('http://www.johnslegers.com/code')->select('a[href="http://cascade-framework.com/"] .sizefill');

// Add page title
$data['main']->append('<div class="col"><div class="cell"><h1>Web scraping</h1></div></div>');

$data['main']->append('<div class="col"><div class="cell"><p>The Following content is scraped from three different sources</p></div></div>');

//Add external content from www.cascade-framework.com
$panel = $H->select($paneltemplate);
$panel->select('.body')->append($cascadeframework);
$data['main']->append($panel);

//Add external content from www.cascade-framework.com
$panel = $H->select($paneltemplate);
$panel->select('.body')->append($highlights);
$data['main']->append($panel);

//Add external content from www.cascade-framework.com
$panel = $H->select($paneltemplate);
$panel->select('.body')->append($author);
$data['main']->append($panel);
