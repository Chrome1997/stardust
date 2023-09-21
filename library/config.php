<?php

$host = "192.168.1.195";
$username = "postgres";
$password = "UFJHze4LVQKTSjgDhqdxPAwIkzg0kuwj";

$db = new PDO("pgsql:dbname=AuraWeb;host=$host", $username, $password ) or die("Failed to connect!"); 
$db2 = new PDO("pgsql:dbname=FFMember;host=$host", $username, $password ) or die("Failed to connect!"); 
