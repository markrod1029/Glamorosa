<?php
	session_start();
	include 'dbconnection.php';

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		header('location: dashboard.php');
	}

	$sql = "SELECT * FROM tbluser WHERE ID = '".$_SESSION['admin']."'";
	$query = $con->query($sql);
	 $user = $query->fetch_assoc();
	
	
?>