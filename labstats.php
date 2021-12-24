<html>
	<head>
		<title>Statistics</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="60">
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
				Choose dates from current academic year..<br>
			</div><br>
			<div style="padding-left:2em;width:65%;">
				<form method="get">
					<div style="width:30%;float:left;">
						<label for="date1">From:</label><br>
						<input type="date" name="date1" required ><br><br>
						<button name="completeview" class="recepbut" style="background-color:#999;">Complete Record </button>
					</div>
					<div style="width:30%;float:left;">
						<label for="date2" >To:</label><br>
						<input type="date" name="date2" required><br><br>
						<button name="aggregate" class="recepbut" style="background-color:#999;">Aggregate Record </button><br><br><br>
					</div>
				</form>
			</div>
		</div>
		<div id="getstats" style="clear:both;">
		</div>
	</body>
</html>
<?php
	require_once 'connect.php';
	if(isset($_GET['completeview'])){
		$date1=date_create($_GET['date1']);
		$date2=date_create($_GET['date2']);
		$date11=$date1->format('d M Y');
		$date22=$date2->format('d M Y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$append="<table><tr><th>Date</th><th>Time</th><th>REG.No</th><th>Name</th><th>Test Taken</th></tr>";
		for ($i=0;$i<$diff+1;$i++){
			$query="select d.Date,d.Time,d.Patient_ID,c.Name,d.Prescription from labrecord d inner join clientrecord c on d.Patient_ID=c.`REG. NO` AND d.completed=1 AND d.Date=\"".date_format($date1,"Y/m/d")."\"";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_assoc($result)){
				$date=$row['Date'];
				$time=$row['Time'];
				$reg=$row['Patient_ID'];
				$name=$row['Name'];
				$prod=$row['Prescription'];
				$append=$append."<tr><td>$date</td><td>$time</td><td>$reg</td><td>$name</td><td>$prod</td></tr>";
			}
			date_add($date1,date_interval_create_from_date_string("1 days"));
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
		$append="<table><tr><th>Date</th><th>Status</th><th>Count of Patients</th></tr>";
		for($i=0;$i<$diff+1;$i++){
			$d=date_format($date1,"Y/m/d");
			$query="select count(d.Patient_ID),c.Status from labrecord as d inner join clientrecord as c on d.Patient_ID=c.`REG. NO` where Date='$d' group by c.Status";
			echo "<script>alert(\"$query\");</script>";
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_assoc($result)){
				$c=$row['count(d.Patient_ID)'];
				$s=$row['Status'];
				if($c>0){
				$append=$append."<tr><td>$d</td><td>$s</td><td>$c</td></tr>";
				}
			}
			date_add($date1,date_interval_create_from_date_string("1 days"));
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
				document.getElementById('entire').innerHTML='';
				document.getElementById('getstats').innerHTML='$append';
				window.print();
				window.history.go(-2);
			</script>";
	}
	mysqli_close($conn);
?>
