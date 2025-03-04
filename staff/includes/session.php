<?php
	session_start();
	include 'dbconnection.php';

	if(!isset($_SESSION['staff']) || trim($_SESSION['staff']) == ''){
		header('location: dashboard.php');
	}

	$sql = "SELECT *, tblservices.ID AS sID, tbluser.ID AS ID  FROM tbluser 
	LEFT JOIN tblservices ON tblservices.user_id = tbluser.ID 
	WHERE tbluser.ID = '".$_SESSION['staff']."'";
	$query = $con->query($sql);
	 $user = $query->fetch_assoc();
	
	
?>