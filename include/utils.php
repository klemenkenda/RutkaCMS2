<?php

namespace Inc;

class Utils {

    /**
     * Loads config from a file name (default is config.json).
     */
    static function LoadConfig($filename = "config.json") {
        $fp = fopen($filename, "r");
        $jsonStr = fread($fp, 100000);
        return json_decode($jsonStr, true);
    }

    /**
     * Parses URI string of an URL.
     */
    static function ParseURI($uri) {
        if (strpos($uri, "?") > 0) {
            list($path, $x) = explode("?", $uri, 2);
        } else {
            $path = $uri;
        }
        return $path;
    }

    /**
     * Parses last word from URI.
     */
    static function ParseCommandFromURI($uri) {
        $pieces = explode('/', $uri);

        // reseting parameters
        $id = "";
        $function = "";

        // extracting parameters
        if (sizeof($pieces) > 3) $function = $pieces[3];
        if (sizeof($pieces) > 4) $id = $pieces[4];

        return [ $function, $id ];
    }

    /**
     * Encodes PHP object into JSON.
     */
    static function JSON($arr) {
        return json_encode($arr, true);
    }

    /**
     * Extract request parameter.
     */
    static function extractRequestParameter($name) {
        return $_REQUEST[$name];
    }
}

$utils = new Utils();

?>