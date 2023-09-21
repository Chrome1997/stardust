<?php 
session_start();
include('../library/config.php');

function delete_post($id, $db){
	$query = $db->prepare("DELETE FROM news WHERE id='$id'");
	$query->execute();
}

if((isset($_SESSION['id'])!= 2418) ) {
	header('Location: ../index.php');
	exit();
}

if(!isset($_GET['id'])){
	header('Location: ../index.php');
	die();
}
	
delete_post($_GET['id'], $db);
header('Location: ../index.php');
die();
