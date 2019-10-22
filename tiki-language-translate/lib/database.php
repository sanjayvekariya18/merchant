<?php
/**
 * This file is for configuration of databse.
 * */
$hostname    ='localhost';
$hostUsername='root';
$hostPassword='123456abcde';
$databaseName='dev_v400';
try {
    $databaseConnection=new PDO("mysql:host=$hostname;dbname=$databaseName;charset=utf8", $hostUsername, $hostPassword, array(PDO::ATTR_TIMEOUT=>"1024", PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    date_default_timezone_set('UTC');
}
catch(Exception$conncetionQuery) {
    echo $conncetionQuery->getMessage();
}

?>
