<?php

$router->get('/page/{slug}', 'PageController@index', $module);
// $router->get('/pages/{slug}', 'PagesController@index', $module);