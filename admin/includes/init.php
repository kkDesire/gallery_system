<?php
//C:\xampp\htdocs\gallery\admin\includes
// defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);
// defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:' . DS . 'xampp'.DS.'htdocs'.DS.'gallery');
// defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT.DS.'admin'.DS.'includes');
define('DS', DIRECTORY_SEPARATOR);

require_once(__DIR__ . DS . "functions.php");
require_once(__DIR__ . DS . "config.php");
require_once(__DIR__ . DS . "database.php");
require_once(__DIR__ . DS . "db_object.php");
require_once(__DIR__ . DS . "user.php");
require_once(__DIR__ . DS . "photo.php");
require_once(__DIR__ . DS . "session.php");
require_once(__DIR__ . DS . "comment.php");
require_once(__DIR__ . DS . "paginate.php");





?>