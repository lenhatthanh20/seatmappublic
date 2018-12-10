<?php
define('__ROOT__', dirname(dirname(dirname(dirname(__FILE__)))));
require_once(__ROOT__ . '/smarty/Smarty.class.php');
$smarty = new Smarty;
// set the directory where templates are stored
$smarty->template_dir = __ROOT__ . '/views/templates';
// set the directory where compiled templates are stored
$smarty->compile_dir = __ROOT__ . '/views/templates_c';
// set the directory where config are stored
$smarty->config_dir = __ROOT__ . '/views/configs';
// set the directory where cache are stored
$smarty->cache_dir = __ROOT__ . '/views/cache';