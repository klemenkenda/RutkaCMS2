<?php

namespace API;

class Manage {
    /**
     * Manage API class constructor.
     */
    function __construct($db, $utils) {
        // saving link to database
        $this->db = $db;
        // saving link to utils object
        $this->utils = $utils;

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
    function getModule($id) {
        $module = [];

        $module["name"] = $id;
        $module["dir"] = $this->componentDir . DIRECTORY_SEPARATOR . $id;
        $module["installed"] = $this->isModuleInstalled($id);
        $module["integrity"] = $this->moduleIntegrity($module["dir"]);
        if ($module["integrity"]["config"] == true) {
            $module["config"] = $this->moduleConfig($module["dir"]);
        };

        return $module;
    }

    /**
     * Returns true if module is installed, otherwise false.
     */
    function isModuleInstalled($id) {
        $value = $this->db->getEnvVariable($id . "_INSTALLED");

        if ($value == "true") return true;
        return false;
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

    /**
     * Install basic structures to run the CMS system:
     *   - env (environment variables)
     *   - user (user tables)
     *   - page (pages table)
     */
    function install() {
        // output structure
        $status = [];

        // get configuration
        $config = $this->getModules();

        // TODO: check modules integrity

        // install missing modules
        $modules = [ 'env', 'users', 'pages' ];

        foreach ($modules as $module) {
            $itemStatus = $this->installModule($module);
            print_r($itemStatus);
            array_push($status, $itemStatus);
        }

        return $status;
    }

    /**
     * Uninstall all core modules of the system.
     */
    function uninstall() {
        // output structure
        $status = [];

        // get configuration
        $config = $this->getModules();

        // TODO: check modules integrity

        // install missing modules
        $modules = [ 'users', 'pages', 'env' ];

        foreach ($modules as $module) {
            $itemStatus = $this->uninstallModule($module);
            array_push($status, $itemStatus);
        }

        return $status;
    }

    /**
     * Installs a module: registers it in the DB and creates the appropriate
     * table.
     */
    function installModule($id) {
        // get module config/data
        $config = $this->getModule($id);

        // integrity checks
        if ($config["installed"] == true) {
            return([ "status" => "NOK", "message" => "Module `" . $id . "` already installed" ]);
        } else if ($config["integrity"]["create"] == false) {
            return([ "status" => "NOK", "message" => "Create SQL script does not exist for module `" . $id . "`" ]);
        }

        // create file path
        $createSQLFile = $this->componentDir . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "create.sql";
        $success = $this->db->processSQLFile($createSQLFile);

        // add data
        if ($config["integrity"]["data"] == true) {
            $dataSQLFile = $this->componentDir . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "data.sql";
            $successData = $this->db->processSQLFile($dataSQLFile);
        }

        // if everything is OK, then register the module
        if ($success["status"] == "OK") {
            $statusEnv = $this->db->setEnvVariable($id . "_INSTALLED", "true");
            if ($statusEnv["status"] != "OK") {
                return $statusEnv;
            }
        }

        return $success;
    }

    /**
     * Delete a module: deletes DB and deletes it and unregisteres it.
     */
    function uninstallModule($id) {
        // get module config/data
        $config = $this->getModule($id);

        // integrity checks
        if ($config["installed"] == false) {
            return([ "status" => "NOK", "message" => "Module `" . $id . "` not installed. No need for uninstall." ]);
        } else if ($config["integrity"]["destroy"] == false) {
            return([ "status" => "NOK", "message" => "Destroy SQL script does not exist for module `" . $id . "`" ]);
        }

        // destroy file path
        $destroySQLFile = $this->componentDir . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . "destroy.sql";
        $success = $this->db->processSQLFile($destroySQLFile);

        // if everything is OK, then unregister the module
        if ($success["status"] == "OK") {
            if ($id != "env") {
                $statusEnv = $this->db->unsetEnvVariable($id . "_INSTALLED");
            } else {
                $statusEnv = [ "status" => "OK" ];
            }
            if ($statusEnv["status"] != "OK") {
                return $statusEnv;
            }
        }

        return $success;
    }

}

?>