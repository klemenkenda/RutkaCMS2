<?php

namespace Inc;
use \mysqli;

class Db {

    private $mysqli;

    /**
     * Creates mysqli object for MySQL operations.
     */
    function __construct($config) {
        $this->mysqli = new mysqli(
            $config['db']['host'],
            $config['db']['user'],
            $config['db']['pass'],
            $config['db']['db']
        );
        // TODO: what if this fails?
    }

    /**
     * Performs an SQL query.
     */
    function query($query) {
        return $this->mysqli->query($query);
    }

    /**
     * Retrives an error from SQL request.
     */
    function getError() {
        return $this->mysqli->error;
    }

    /**
     * Process the SQL file.
     */
    function processSQLFile($file) {
        // read the file
        $SQL = file_get_contents($file);
        $result = $this->query($SQL);
        if ($result == true) {
            // success
            return [ "status" => "OK" ];
        } else {
            // find out the error
            return [ "status" => "NOK", "error" => $this->getError() ];
        }
    }
}

?>