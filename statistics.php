<html>
	<head>
		<title>Statistics</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="6000">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div id="header">
			<h1 class="head">View Statistics</h1><hr>
		</div><br>
		<div style="float:right;">
			<form id="butn">
			</form>
		</div>
		<div id="entire">
			<div style="font-size: 20;font-weight: bold">
				Choose dates from current academic year..
			</div>
			<div style="padding-left:2em;width:35%;">
				<form method="get">
					<div style="width:50%;float:left;">
						<label for="date1">From:</label><br>
						<input type="date" name="date1" required ><br><br>
						<button name="completeview" class="recepbut" style="background-color:#999;">Complete Record </button>
					</div>
					<div style="width:50%;float:right;">
						<label for="date2" >To:</label><br>
						<input type="date" name="date2" required><br><br>
						<button name="aggregate" class="recepbut" style="background-color:#999;">Aggregate Record </button><br><br><br>
					</div>
				</form>
			</div>
		</div>
		<div id="getstats">
		</div>
	</body>
</html>
<?php
	require_once 'connect.php';
	if(isset($_GET['completeview'])){
		$date1=date_create($_GET['date1']);
		$date2=date_create($_GET['date2']);
		$date11=$date1->format('d F Y');
		$date22=$date2->format('d M y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$append="<table style=\"width:85%;\"><tr><th>Date</th><th>Time</th><th>Patient_Id</th><th>Name</th><th>Consulted</th><th>Prescription</th></tr>";
		for ($i=0;$i<$diff+1;$i++){
			$query="select p.Date,p.Time,p.`REG.No`,c.Name,p.Consulted,p.Prescription from patientrecord as p inner join clientrecord as c on p.`REG.No`=c.`REG. NO` where Date=\"".date_format($date1,"Y/m/d")."\"";
			$result=mysqli_query($conn,$query);
			date_add($date1,date_interval_create_from_date_string("1 days"));
			if($result)
			while($row=mysqli_fetch_assoc($result)){
				$d=$row['Date'];
				$t=$row['Time'];
				$r=$row['REG.No'];
				$n=$row['Name'];
				$p=$row['Prescription'];
				$c=$row['Consulted'];
				$append=$append."<tr><td>$d</td><td>$t</td><td>$r</td><td>$n</td><td>$c</td><td>$p</td></tr>";
			}
		}
		$append=$append."</table>";
		setcookie("append",$append,time()+3*3600);
		echo "<script>document.getElementById('getstats').innerHTML='$append';</script>";
		echo "<script>document.getElementById('butn').innerHTML='<button name=\"print\">Print page</button>';</script>";
	}
	if(isset($_GET['aggregate'])){
		$date1=date_create($_GET['date1']);
		$date2=date_create($_GET['date2']);
		$date11=$date1->format('d F Y');
		$date22=$date2->format('d M y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$append="<table style=\"width:85%;\"><tr><th>Doctor_Id</th><th>Name</th><th>Status</th><th>No. of Patients</th></tr>";
		$datestring="( ";
		for ($i=0;$i<$diff+1;$i++){
			$d=date_format($date1,"Y/m/d");
			$datestring=$datestring."\"$d\",";
			date_add($date1,date_interval_create_from_date_string("1 days"));
		}
		$datestring=$datestring." \"\")";
		$query="select p.Consulted,d.Name,c.Status,count(c.Status) from patientrecord as p inner join doctor as d inner join clientrecord as c on p.`REG.No`=c.`REG. NO` and d.UserID=p.Consulted where p.Date in ".$datestring."group by p.Consulted,c.Status";

		$result=mysqli_querY($conn,$query);
		if($result)
		While($row=mysqli_fetch_assoc($result)){
			$di=$row['Consulted'];
			$dn=$row['Name'];
			$st=$row['Status'];
			$cs=$row['count(c.Status)'];
			$append=$append."<tr><td>$di</td><td>$dn</td><td>$st</td><td>$cs</td></tr>";
		}
		$append=$append."</table>";
		setcookie("append",$append,time()+3*3600);
		echo "<script>document.getElementById('getstats').innerHTML='$append';</script>";
		echo "<script>document.getElementById('butn').innerHTML='<button name=\"print\">Print page</button>';</script>";
	}
	if(isset($_GET['print'])){
		$append=$_COOKIE['append'];
		$date1=$_COOKIE['date1'];
		$date2=$_COOKIE['date2'];
		$dates="The Record of Patients Visited from $date1 to $date2";
		echo "<script>
				document.getElementById('header').innerHTML=\"<h2 align='center'>HEALTH CENTRE: ANNA UNIVERSITY: MIT CAMPUS</h2>\";
				document.getElementById('entire').innerHTML='<h4>$dates</h4>';
				document.getElementById('getstats').innerHTML='$append';
				window.print();
				window.history.go(-2);
			</script>";
	}
	mysqli_close($conn);
?>
