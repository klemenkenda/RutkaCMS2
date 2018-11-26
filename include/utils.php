<?php

namespace Inc;

class Utils {

    static function LoadConfig() {
        $fp = fopen("config.json", "r");
        $jsonStr = fread($fp, 100000);
        return json_decode($jsonStr, true);
    }

}

$utils = new Utils();

?>