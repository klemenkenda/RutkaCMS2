<?php

namespace Inc;
use \mysqli;

class Db {

    private $mysqli;

    function __construct($config) {
        $this->mysqli = new mysqli(
            $config['db']['host'],
            $config['db']['user'],
            $config['db']['pass'],
            $config['db']['db']
        );
    }

    function query($query) {
        return $this->mysqli->query($query);
    }
}

?>