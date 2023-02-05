<?php

class DB
{
    public $mysqli;
    
    public function __construct($dbhost = DB_HOST, $dbuser = DB_USER, $dbpass = DB_PASS, $dbname = DB_NAME, $charset = "utf8")
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        $this->mysqli->set_charset($charset);
    }

}