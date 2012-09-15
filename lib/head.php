<?php
/* @var $path String */

require_once('config.inc.php');
require_once(FILE_ROOT.'classes/lib/autoloader.php');
require_once(FILE_ROOT.'lib/functions.inc.php');

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');

/* @var $config Config */
$config = Config::getInstance();
$config->addResource('db_dev', new MysqlResource('localhost', 'framework', 'dev', 'sql', '644926272e66674f7b64362d3d246a315a2857435c3d756b5c70445745'));
$config->setDefaultResource('db_dev');