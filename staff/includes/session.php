<?php
	session_start();
	include 'dbconnection.php';

	if(!isset($_SESSION['staff']) || trim($_SESSION['staff']) == ''){
		header('location: dashboard.php');
	}

	$sql = "SELECT *, tblservices.ID AS sID, tbluser.ID AS ID, packages.id AS pID, events.id AS eID  FROM tbluser 
	LEFT JOIN tblservices ON tblservices.user_id = tbluser.ID 
	LEFT JOIN events ON events.service_id = tblservices.ID 
	LEFT JOIN packages ON packages.event_id = events.id 
	WHERE tbluser.ID = '".$_SESSION['staff']."'";
	$query = $con->query($sql);
	 $user = $query->fetch_assoc();
	
	
?>