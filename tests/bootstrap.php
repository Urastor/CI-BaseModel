<?php

// Set Basepath to empty string to prevent script access error
define("BASEPATH", "");

require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Fake CI's Model
class CI_Model
{

    public $db = null;

    public function __construct()
    {}
}

require_once dirname(__FILE__) . '/database/MY_Model_DBTestCase.php';


