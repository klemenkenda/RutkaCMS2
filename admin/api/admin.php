<?php

namespace API;

class Admin {

    function __construct($db, $utils) {
        // saving link to database
        $this->db = $db;
        // saving link to utils object
        $this->utils = $utils;
    }
}

?>