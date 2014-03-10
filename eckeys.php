<?php

// ------------------------------------------
// Step 0 - Create the authorization request
// ------------------------------------------

// include composer autoloader
require('vendor/autoload.php');
define('USE_EXT', 'GMP');

// Generate EC Keys

$ecdsa = new ECDSA();
$keys = $ecdsa->generateEccKeys();
var_dump($keys);

// Once CORE auth comes back, we continue on step1.php file