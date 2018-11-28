<?php

namespace API;

class Manage {
    /**
     * Manage class constructor.
     */
    function __construct() {
        // define working directory
        $this->rootDir = $_SERVER["CONTEXT_DOCUMENT_ROOT"];
        $this->componentDir = $this->rootDir . "components";
    }

    /**
     * Get data on modules.
     */
    function getModules() {
        // transverse component directory
        $files = scandir($this->componentDir, 1);
        $dirs = [];

        // find all directories
        foreach ($files as $file) {
            if (($file != ".") && ($file != "..") && is_dir($this->componentDir . DIRECTORY_SEPARATOR . $file)) {
                array_push($dirs, $this->getModule($file));
            }
        }

        // remember the structure within the module
        $this->modules = $dirs;

        return $dirs;
    }

    /**
     * Returns object for a module.
     */
    function getModule($file) {
        $module = [];

        $module["name"] = $file;
        $module["dir"] = $this->componentDir . DIRECTORY_SEPARATOR . $file;
        $module["installed"] = null;
        $module["integrity"] = $this->moduleIntegrity($module["dir"]);
        if ($module["integrity"]["config"] == true) {
            $module["config"] = $this->moduleConfig($module["dir"]);
        };

        return $module;
    }

    /**
     * Returning module config.
     */
    function moduleConfig($dir) {
        // load config file
        $configFile = $dir . DIRECTORY_SEPARATOR . "admin.json";
        $contentStr = file_get_contents($configFile);
        $admin = json_decode($contentStr, true);

        return $admin;
    }

    /**
     * Checking module integrity.
     */
    function moduleIntegrity($dir) {
        // initial data structure for integrity
        $integrity = [
            "config" => false,
            "create" => false,
            "destroy" => false,
            "data" => false
        ];

        // check config
        $configFile = $dir . DIRECTORY_SEPARATOR . "admin.json";
        if (file_exists($configFile)) {
            $integrity["config"] = true;
        }

        // check create sql
        $createSQLFile = $dir . DIRECTORY_SEPARATOR . "create.sql";
        if (file_exists($createSQLFile)) {
            $integrity["create"] = true;
        }

        // check delete sql
        $destroySOLFile = $dir . DIRECTORY_SEPARATOR . "destroy.sql";
        if (file_exists($destroySOLFile)) {
            $integrity["destroy"] = true;
        }

        // check data sql
        $dataSQLFile = $dir . DIRECTORY_SEPARATOR . "data.sql";
        if (file_exists($dataSQLFile)) {
            $integrity["data"] = true;
        }

        return $integrity;
    }


}

?>