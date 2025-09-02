<?php
$c = $_GET['c'] ?? 'Patron';
$a = $_GET['a'] ?? 'index';

$c .= 'Controller';
require __DIR__ . "/../app/controllers/$c.php";
$controller = new $c();
$controller->$a();
