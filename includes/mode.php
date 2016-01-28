<?php
/*
* D - Debug mode
*
* 0 - Local machine debug
* 1 - Testing environment (debug)
* 2 - Testing environment (production)
* 3 - Production environment (debug)
* 4 - Production environment
*/

/* ON PRODUCTION CHANGE TO FALSE */
define('DEBUG', false);

if (!defined("D")) { // for scripts that executed using exec function.
    if ($_SERVER["SERVER_NAME"] == "localhost") {
        define("D", 0);
    }
}

/**
 * Returns true for testing environments on both debug or production modes
 */
function is_testing_environment() {
    if (D == 1 || D == 2)
        return true;
    return false;
}

/**
 * Returns true for production environments on both debug or production modes
 */
function is_production_environment() {
    if (D == 3 || D == 4)
        return true;
    return false;
}

/**
 * Returns true whether on production for both testing and production environments
 */
function is_on_production() {
    if ((D == 2) || (D == 4))
        return true;
    return false;
}

function is_local_machine() {
    if (D == 0)
        return true;
    return false;
}