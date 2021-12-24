<?php 
	if(!isset($_COOKIE['duid'])){ exit();}
	setcookie("reg","",time()-30);
?>
<html>
	<head>
		<title>Doctor Desk</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="5">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div class="head"><h1 id="f"></h1></div><hr>
		<div class="pharmatab" >	
			<form method="get">
				<!--button name="appoint">Appointments</button-->
				<div class="two" style="float:right;">
					<button style="float:right;height:38px;"><b>Log</b></button>
					<div class="one">
						<button  style="float:right;height:39px;" name="changepass">Change Password</button>				
						<button style="float:right;height:38px;" name="logout">logout</button>
					</div>
				</div>
			</form>
		</div><br>
		<form>
			<table id="patient_table" style="width:45%"></table>
		</form>
	</body>
</html>
<?php 
	require_once 'connect.php';
	$name=$_COOKIE['duid'];
	$query="select * from patients where Concerned_Doctor='$name' order by Date asc,time asc";
	$append="<tr><th>Date</th><th style=\"width:130px;\">Name</th><th style=\"width:180px;\">Reason</th><th style\"width:50px;\">Clear</th></tr>";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_assoc($result)){
		$n=$row['Patient_Name'];
		$r=$row['Reason'];
		$reg=$row['Patient_ID'];
		$d=$row['Date'];
		$ne="pat";
		$append=$append."<tr><td>$d</td><td><button name=\"move\" value=\"$reg\">$n</button></td><td>$r</td><td><button name=\"clear\" value=\"$reg\">Clear</button></td></tr>";
	}
	echo "<script>
			document.getElementById('patient_table').innerHTML='$append';
		</script>";
	$query="select Name from doctor where UserID=".$name;
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$append="Welcome Dr.".$row['Name'];
	echo "<script>document.getElementById('f').innerHTML='$append';</script>";
	if(isset($_GET['appoint'])){
		header("Location: Doctor_appoint.php");
	}
	if(isset($_GET['clear'])){
		$v=$_GET['clear'];
		$query="delete from patients where Patient_ID='".$v."'";
		mysqli_query($conn,$query);
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","doctor");
		header("Location: changepass.php");
	}
	if(isset($_GET['logout'])){
		$query="update doctor set `status`=0 where `UserId`='$name'";
		mysqli_query($conn,$query);
		setcookie("duid","",time()-3600);
	?>
		<script>window.history.go(-2);</script>;
	<?php
	}
	if(isset($_GET['move'])){
		$reg=$_GET['move'];
		echo "<script>window.location.replace(\"display.php?reg=$reg\");</script>;";
	}
?>