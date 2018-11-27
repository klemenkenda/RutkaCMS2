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
        $module["integrity"] = null;

        return $module;
    }
}

?>