<?php

ob_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

define("BASE_PATH", dirname(dirname(__FILE__)));
define("PUBLIC_PATH", BASE_PATH . '/public');
define("PRIVATE_PATH", BASE_PATH . '/includes');
define("LOG_PATH", BASE_PATH . '/logs');

require_once(PRIVATE_PATH . '/mode.php');
require_once(PRIVATE_PATH . '/config.php');
require_once(PRIVATE_PATH . '/functions.php');
require_once(PRIVATE_PATH . '/constants.php');

require_once(PRIVATE_PATH . '/session.class.php');
require_once(PRIVATE_PATH . '/database.class.php');
require_once(PRIVATE_PATH . '/database_object.class.php');
require_once(PRIVATE_PATH . '/csrf.class.php');
require_once(PRIVATE_PATH . '/hash.class.php');
require_once(PRIVATE_PATH . '/message.class.php');
require_once(PRIVATE_PATH . '/user.class.php');