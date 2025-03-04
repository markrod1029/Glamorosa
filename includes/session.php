<?php
	session_start();
	include 'conn.php';

	if(!isset($_SESSION['customer']) || trim($_SESSION['customer']) == ''){
		header('location: index.php');
	}

	$sql = "SELECT * FROM tbluser WHERE ID = '".$_SESSION['customer']."'";
	$query = $con->query($sql);
	 $user = $query->fetch_assoc();
	
	
?>