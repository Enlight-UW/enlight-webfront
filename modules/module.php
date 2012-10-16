<?php

/*
 * Project: CleanWebfront
 * File: module.php
 * Author: Alex Kersten
 * 
 * This is the master module file. The purpose behind module files is that every
 * action category a user can perform is contained within one file which will
 * provide the relevant code for our main page without cluttering up that file.
 * 
 * Considerations: Do NOT let users specify names of modules - this should be
 * done by our code only since $moduleName is directly inserted into the markup.
 * A-Za-z0-9 probably works best. Spaces are alright too - just remember to
 * convert them to underscores (see features.php).
 */

class module {

    private $moduleName;

    function __construct($moduleName) {
        $this->moduleName = $moduleName;
    }

    /**
     * Return any relevant header information / setup for the beginning of this
     * module.
     */
    private function getHeader() {
        
    }

    private function getFooter() {
        
    }

    /**
     * Return the HTML markup for the entire module page. 
     */
    public final function getMarkup() {
        return $this->getHeader() . $this->getInnerContent() .
                $this->getFooter();
    }

    /**
     * Return the inner HTML inside the full view of this module. This is the
     * only function you should override when making a new module. 
     */
    protected function getInnerContent() {
        return "Default inner conent for master module object, " .
                "shouldn't see this...";
    }
    
    /**
     * Get the name of the module.
     * @return string The name of this module.
     */
    public final function getModuleName() {
        return $this->moduleName;
    }

}

?>
