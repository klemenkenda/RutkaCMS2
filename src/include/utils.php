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
        if (sizeof($pieces) > 2) $function = $pieces[2];
        if (sizeof($pieces) > 3) $id = $pieces[3];

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
        if (array_key_exists($name, $_REQUEST)) return $_REQUEST[$name];
        return "";
    }

    /**
     * Generate token.
     */
    static function getTokenString($len = 64) {
        $token = "";

        $chars = "0123456789abcdef";
        for ($i = 0; $i < 64; $i++) {
            $u = rand(0, 15);
            $token .= $chars[$u];
        }

        return $token;
    }
}

$utils = new Utils();

?>