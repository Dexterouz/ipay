<?php
    // creating constants for db connection
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'def');
    define('DB_NAME', 'ipay_db');
    
    // try - catch block to catch any error
    try {
        // variable to hold the connection link
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // set the encoding character of the db
        mysqli_set_charset($conn, 'utf8');
    } catch (Exception $e) {
        print "An exception has occurred ".$e->getMessage();
        print "The System is busy, please try again later.";
        date_default_timezone_set('Africa/lagos');
        $date = date('m:d:Y h:i:sa');
        $error_string = $date . " | DB connection | "."{$e->getMessage()} | "."{$e->getLine()}";
        error_log($error_string, 3, "./logs/exception_log.log");
    } catch (Error $e) {
        print "An Error has occurred ".$e->getMessage();
        print "The System is busy, please try again later.";
        date_default_timezone_set('Africa/lagos');
        $date = date('m:d:Y h:i:sa');
        $error_string = $date . " | DB connection | "."{$e->getMessage()} | "."{$e->getLine()}";
        error_log($error_string, 3, "./logs/exception_log.log");
    }
?>