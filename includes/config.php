<?php

if (is_production_environment()) {
    defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
    defined('DB_USER') 	 ? null : define("DB_USER", "");
    defined('DB_PASS')   ? null : define("DB_PASS", "");
    defined('DB_NAME')   ? null : define("DB_NAME", "");
    defined('DB_PORT')   ? null : define("DB_PORT", null);
    defined('DB_SOCKET') ? null : define("DB_SOCKET", null);
} elseif (is_local_machine()) {
    defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
    defined('DB_USER') 	 ? null : define("DB_USER", "root");
    defined('DB_PASS')   ? null : define("DB_PASS", "");
    defined('DB_NAME')   ? null : define("DB_NAME", "dapulse");
    defined('DB_PORT')   ? null : define("DB_PORT", null);
    defined('DB_SOCKET') ? null : define("DB_SOCKET", null);
} elseif (is_testing_environment()) {
    defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
    defined('DB_USER') 	 ? null : define("DB_USER", "");
    defined('DB_PASS')   ? null : define("DB_PASS", "");
    defined('DB_NAME')   ? null : define("DB_NAME", "");
    defined('DB_PORT')   ? null : define("DB_PORT", null);
    defined('DB_SOCKET') ? null : define("DB_SOCKET", null);
}