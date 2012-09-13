<?php
/* @var $path String */

require_once($path.'lib/config.php');
require_once(FILE_ROOT.'classes/lib/autoloader.php');
require_once(FILE_ROOT.'lib/functions.php');

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');
?>