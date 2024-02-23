<?php
$servidor="localhost";
$db="metrecicla";
$usrname="root";
$pass="1234";
try{
    $con= new PDO("mysql:host=$servidor;dbname=$db", $usrname, $pass);
}catch(Exception $e){
    echo $e->getMessage();
}
