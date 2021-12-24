<?php
	require_once 'connect.php';
?>
<html> 
	<head>
		<title>Home</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="3600">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div>
			<img src="top.png" height=150 width=100%><hr>
			<h1 align="center">HOME page</h1>
		</div>
		<div class="homebut">
			<form>
				<button name="gotoreception">Receptionist</button><br><br>
				<button name="gotodoctor">Doctor</button><br><br>
				<button name="pharmacist">Pharmacist</button><br><br>
				<button name="lab">Laboratory</button><br><br>
				<button name="dressing">Dressings</button><br><br>
				<button name="injection">Injections</button><br><br>
				<button name="register">Register</button><br><br>
			</form>
		</div>
		<div class="hometext">
			<p>
					

			Health Centre facility with attached inpatient ward is available at MIT campus. It is located  in the Hostel zone for the convenience of hostellers. All students and teaching and non teaching staff of Anna University  in MIT Campus including members in other campuses of Anna university and their dependents  are eligible to avail the services of health centre.  The health centre is committed to provide quality treatment services at free of cost. The Centre provides outpatient treatment from morning 10.00 AM to evening 6.00 PM. The emergency cases are treated as inpatients.
			The fundamental purpose of the Health Center is to enable students to pursue their academic goals  by providing support in maintaining the best possible physical and mental health in a compassionate environment by way of  
			<li>	providing health and wellness with care and attention to individual patient needs.</li>
			<li>	providing basic life support and first aid in emergencies.</li>
			<li>	offering consultation and treatment in selected medical specialties.</li>
			<li>	organizing medical camps to promote basic health services and to prevent non    communicable diseases.</li>
			<br>
		
			The Health Center is staffed by licensed  Medical officers, Staff nurse,  Nursing Assistant, Pharmacist, Lab technician and other supporting staff. Ophthalmologist, ENT specialist, Orthopaedician, Dermatologist, Diabetologist and  Physiotherapist are visiting the health centre every week. An Average of 10,000 Consultations per year are being carried out.
			</p><br><br>
		</div><br><br>
	</body>
</html>
<?php 
	require_once 'connect.php';
	date_default_timezone_set('Asia/Kolkata');
	$date=date("Y-m-d");
	if(isset($_GET['gotoreception'])){
		setcookie("login","receptionist");
		header("Location: staff_login.php");
	}
	if(isset($_GET['lab'])){
		setcookie("login","laboratory");
		header("Location: staff_login.php");
	}
	if(isset($_GET['gotodoctor'])){
		setcookie("login","doctor");
		header("Location: staff_login.php");
	}
	if(isset($_GET['pharmacist'])){
		setcookie("login","pharmacist");
		header("Location: staff_login.php");
	}
	if(isset($_GET['dressing'])){
		setcookie("login","dressing");
		header("Location: staff_login.php");
	}
	if(isset($_GET['injection'])){
		setcookie("login","injection");
		header("Location: staff_login.php");
	}
	if(isset($_GET['register'])){
		header("Location: register.html");
	}
	if(isset($_COOKIE['duid'])){
		$name=$_COOKIE['duid'];
		$query="update doctor set `status`=0 where `UserId`='$name'";
		mysqli_query($conn,$query);
		setcookie("duid","",time()-3600);
	}
?>
<?php
	mysqli_close($conn);
?>
