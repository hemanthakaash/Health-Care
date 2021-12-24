<?php
	require_once "connect.php";
	$table=$_COOKIE['login'];
	if($table=="receptionist" and isset($_COOKIE['uid']))
		echo "<script>
				location.replace('reception.php');
			</script>";
	else if($table=="doctor" and isset($_COOKIE['duid']))
		echo "<script>
				location.replace('Doctor_desk.php');
			</script>";
	else if($table=="pharmacist" and isset($_COOKIE['puid']))
		echo "<script>
				location.replace('pharmacist.php');
			</script>";
	else if($table=="laboratory" and isset($_COOKIE['luid']))
		echo "<script>
				location.replace('laboratory.php');
			</script>";
	else if($table=="dressing" and isset($_COOKIE['druid']))
		echo "<script>
				location.replace('dressing.php');
			</script>";
	else if($table=="injection" and isset($_COOKIE['inuid']))
		echo "<script>
				location.replace('injection.php');
			</script>";
	else header("Location: staff.php");
	