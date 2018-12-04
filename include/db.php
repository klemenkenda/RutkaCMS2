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
     * Perform multiple SQL queries with one call.
     */
    function multi_query($query) {
        $results = [];
        if ($result = $this->mysqli->multi_query($query)) {
            // free the results
            do { } while ($this->mysqli->more_results() && $this->mysqli->next_result());
            return [ "status" => "OK" ];
        } else {
            return ([ "status" => "NOK2", "error" => $this->getError()]);
        }
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
        $result = $this->multi_query($SQL);
        return $result;
    }

    /**
     * Write environment variable.
     */
    function setEnvVariable($key, $value) {
        // generate SQL
        $SQL = "INSERT INTO env (varname, val) VALUES ('" . $key . "', '". $value . "')";
        $result = $this->query($SQL);
        if ($result == true) {
            return [ "status" => "OK" ];
        } else {
            // find out the error
            return [ "status" => "NOK", "error" => $this->getError() ];
        }
    }

    /**
     * Get environment variable.
     */
    function getEnvVariable($key) {
        // generate SQL
        $SQL = "SELECT val FROM env WHERE varname = '" . $key . "'";
        $result = $this->query($SQL);
        if ($result == true) {
            $value = $result->fetch_array(MYSQLI_NUM);
            return $value[0];
        } else {
            // find out the error
            return [ "status" => "NOK", "error" => $this->getError() ];
        }
    }

    /**
     * Unset/delete environment variable.
     */
    function unsetEnvVariable($key) {
        // generate SQL
        $SQL = "DELETE FROM env WHERE varname = '" . $key . "'";
        $result = $this->query($SQL);
        if ($result == true) {
            return [ "status" => "OK" ];
        } else {
            // find out the error
            return [ "status" => "NOK", "error" => $this->getError() ];
        }
    }
}

?>