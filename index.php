<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("autoload.php");

$CSS = new Likel\CSS\Manager();
$CSS->addMultiple(array(
    "test001.css",
    "test002.css",
), true, 0, 1000);

$CSS->output("seeds");
echo "HERE";
