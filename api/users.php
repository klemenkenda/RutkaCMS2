<?php

namespace API;

class Users {
    /**
     * Users API class constructor.
     */
    function __construct($db, $utils) {
        // saving link to database
        $this->db = $db;
        // saving link to utils object
        $this->utils = $utils;
    }

    /**
     * User login; returns authentication token, which is valid for 24h.
     */
    function login($user, $password) {
        $array = $this->db->queryResult("select * from users where username='{$user}' and password=PASSWORD('{$password}')");
        if ($array) {
            $user_id = $array["id"];

            unset($array["id"]);
            unset($array["ts"]);
            unset($array["password"]);

            // generate token
            $ok = false;
            while ($ok == false) {
                // generate new token
                $token = $this->utils->getTokenString();

                // insert it into DB
                // in insert is OK, $ok is set to true
                $ok = $this->db->querySuccess("insert into auth (token, user_id) values('{$token}', {$user_id})");
            }
            $array["token"] = $token;
            return $array;
        };

        return [ "status" => "NOK" ];
    }

    /**
     * Is authenticated?
     */
    function isAuth($token) {
        $array = $this->db->queryResult("select * from auth where token='{$token}'");
        return $array;
    }
}

?>