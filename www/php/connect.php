<?php
//To allow Ajax requests 
header("Access-Control-Allow-Origin: *");

try {
	//$pdo = new PDO('mysql:host=localhost;dbname=EJNS', 'root', '30285611');
	$pdo = new PDO('mysql:host=192.168.0.4;dbname=EJNS', 'root', '30285611');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
} catch (Exception $e) {
	echo 'Unable to connect to the database server: ' . $e->getMessage();
	exit();
}

?>