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
			<h1 class="head" id="heading">View Statistics</h1><hr>
		</div><br>
		<div style="float:right;">
			<form id="butn">
			</form>
		</div>
		<div id="entirepage">
			<div style="font-size: 20;font-weight: bold">
				Choose dates from current academic year..
			</div><br><br>
			<div style="padding-left:2em;width:75%;">
				<form method="get" style="width:100%">
					<div style="float:left;">
						<div style="width:100%float:left;">
							<div style="float:left;width:50%;">
								<label for="date1">From:</label>
							</div>
							<div style="float:right;width:40%;">
								<div style="">
									<label for="date2" >To:</label><br>
								</div>
							</div>
						</div>
						<div style="width:100%;">
							<div style="float:left;widht:50%;">
								<input type="date" name="date1" required ><br><br>
							</div>
							<div style="float:right;width:40%;">
							<div style="float:left;width:50%;">
								<input type="date" name="date2" required><br><br>
							</div>
							</div>
						</div>
					</div>
					<div style="clear:both;">
						<button name="completeview" class="statbutton">Complete Record </button>
						<button name="gross" class="statbutton">Gross Record </button>
						<button name="aggregate"  class="statbutton">Aggregate Record </button>
						<button name="overall"  class="statbutton">Overall Report </button>
					</div>
				</form>
			</div><br><br><br>
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
		$date11=$date1->format('d M Y');
		$date22=$date2->format('d M Y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$append="<table style=\"width:100%\"><tr><th>Date</th><th>Time</th><th>REG.No</th><th>Medicine</th><th>Quantity</th></tr>";
		for ($i=0;$i<$diff+1;$i++){
			$query="select * from medicinerecord where Date=\"".date_format($date1,"Y/m/d")."\"";
			//echo $query;
			$result=mysqli_query($conn,$query);
			while($row=mysqli_fetch_assoc($result)){
				$d=$row['Date'];
				$t=$row['Time'];
				$r=$row['REG.No'];
				$m=$row['Medicine'];
				$q=$row['Quantity'];
				$append=$append."<tr><td>$d</td><td>$t</td><td>$r</td><td>$m</td><td>$q</td></tr>";
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
		$date11=$date1->format('d M Y');
		$date22=$date2->format('d M Y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$datestring="( ";
		for ($i=0;$i<$diff+1;$i++){
			$d=date_format($date1,"Y/m/d");
			$datestring=$datestring."\"$d\",";
			date_add($date1,date_interval_create_from_date_string("1 days"));
		}
		$datestring=$datestring." \"\")";
		$query="select m.Type,sum(r.Quantity) from medicine m inner join medicinerecord r on m.Name=r.Medicine where r.Date in ".$datestring."group by Type";
		$result=mysqli_query($conn,$query);
		$append="<table style=\"width:80%\"><tr><th>Type</th><th>Quantity Sold</th></tr>";
		while($row=mysqli_fetch_assoc($result)){
			$t=$row['Type'];
			$q=$row['sum(r.Quantity)'];
			$append=$append."<tr><td>$t</td><td>$q</td></tr>";
		}
		$append=$append."</table>";
		setcookie("append",$append,time()+3*3600);
		echo "<script>document.getElementById('getstats').innerHTML='$append';</script>";
		echo "<script>document.getElementById('butn').innerHTML='<button name=\"print\">Print page</button>';</script>";
	}
	if(isset($_GET['gross'])){
		$date1=date_create($_GET['date1']);
		$date2=date_create($_GET['date2']);
		$date11=$date1->format('d M Y');
		$date22=$date2->format('d M Y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$datestring="( ";
		for ($i=0;$i<$diff+1;$i++){
			$d=date_format($date1,"Y/m/d");
			$datestring=$datestring."\"$d\",";
			date_add($date1,date_interval_create_from_date_string("1 days"));
		}
		$datestring=$datestring." \"\")";
		$append="<table style=\"width:80%\"><tr><th>Medicine</th><th>Starting_Balance</th><th>Ending_Balance</th><th>Quantity Sold</th></tr>";
		$query="select distinct Medicine from medicinerecord";
		$result=mysqli_query($conn,$query);
		if($result)
		while($row=mysqli_fetch_assoc($result)){
			$name=$row['Medicine'];
			$query="select Starting_Balance from medicinerecord where Medicine='$name' and Date in ".$datestring." order By Date,Time asc limit 1";
			$row1=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$start=$row1['Starting_Balance'];
			$query="select Quantity,Starting_Balance from medicinerecord where Medicine='$name' and Date in ".$datestring." order By Date,Time desc limit 1";
			$row1=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$end=$row1['Starting_Balance']-$row1['Quantity'];
			$sold=$start-$end;
			$append=$append."<tr><td>$name</td><td>$start</td><td>$end</td><td>$sold</td></tr>";
		}
		$append=$append."</table>";
		setcookie("append",$append,time()+3*3600);
		echo "<script>document.getElementById('getstats').innerHTML='$append';</script>";
		echo "<script>document.getElementById('butn').innerHTML='<button name=\"print\">Print page</button>';</script>";
	}
	if(isset($_GET['overall'])){
		$date1=date_create($_GET['date1']);
		$date2=date_create($_GET['date2']);
		$date11=$date1->format('d M Y');
		$date22=$date2->format('d M Y');
		setcookie("date1",$date11,time()+3600);
		setcookie("date2",$date22,time()+3600);
		$diff=date_diff($date1,$date2);
		$diff=$diff->format("%a");
		$datestring="( ";
		for ($i=0;$i<$diff+1;$i++){
			$d=date_format($date1,"Y/m/d");
			$datestring=$datestring."\"$d\",";
			date_add($date1,date_interval_create_from_date_string("1 days"));
		}
		$datestring=$datestring." \"\")";
		$i=0;$tableValue=array();
		$special=array('ENT','Ortho','Diabetologist','Skin','Eye','Physiotherapist');
		for($j=0;$j<6;$j++){
			$query="select count(p.`REG.No`) as count from patientrecord p inner join doctor d on d.UserID=p.Consulted And d.Speciality='$special[$j]' inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Staff' and p.Date in ".$datestring."";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);
				$tableValue[$i++]=$row['count'];
			}
			else{
				$tableValue[$i++]=0;
			}
			$query="select count(p.`REG.No`) as count from patientrecord p inner join doctor d on d.UserID=p.Consulted And d.Speciality='$special[$j]' inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Student' and p.Date in ".$datestring."";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);
				$tableValue[$i++]=$row['count'];
			}
			else{
				$tableValue[$i++]=0;
			}
			$tableValue[$i]=$tableValue[$i-1]+$tableValue[$i-2];
			$i++;
		}
		$special=array("injectrec","dressrec","labrecord","ecg");
		$value=array();$i=0;
		for($j=0;$j<4;$j++){
			$query="select count(i.Patient_ID) as count from clientrecord c inner join $special[$j] i on i.Patient_ID=c.`REG. NO` and c.Status='Staff' and i.Date in ".$datestring."";
			echo $query;
			//echo "<script>alert(\"$query\");</script>";
			$result=mysqli_query($conn,$query);
			if($result && mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);
				$value[$i++]=$row['count'];
			}
			else{
				$value[$i++]=0;
			}
			$query="select count(i.Patient_ID) as count from clientrecord c inner join $special[$j] i on i.Patient_ID=c.`REG. NO` and c.Status='Student' and i.Date in ".$datestring."";
			echo $query;
			$result=mysqli_query($conn,$query);
			//echo "<script>alert(\"$query\");</script>";
			if($result && mysqli_num_rows($result)>0){
				$row=mysqli_fetch_assoc($result);
				$value[$i++]=$row['count'];	
			}
			else{
				$value[$i++]=0;
			}
			$value[$i]=$value[$i-1]+$value[$i-2];
			$i++;
		}
		$query="select count(p.`Reg.No`) as count from patientrecord p inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Staff' and p.type=1 and p.Date in".$datestring."";
		$result=mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result)>0){
			$row=mysqli_fetch_assoc($result);
			$value[$i++]=$row['count'];	
		}
		else{
			$value[$i++]=0;
		}
		$query="select count(p.`Reg.No`) as count from patientrecord p inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Student' and p.type=1 and p.Date in".$datestring."";
		$result=mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result)>0){
			$row=mysqli_fetch_assoc($result);
			$value[$i++]=$row['count'];	
		}
		else{
			$value[$i++]=0;
		}
		$value[$i]=$value[$i-1]+$value[$i-2];
			$i++;
		$query="select count(p.`Reg.No`) as count from patientrecord p inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Staff' and p.type=0 and p.Date in".$datestring."";
		$result=mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result)>0){
			$row=mysqli_fetch_assoc($result);
			$value[$i++]=$row['count'];	
		}
		else{
			$value[$i++]=0;
		}
		$query="select count(p.`Reg.No`) as count from patientrecord p inner join clientrecord c on c.`REG. NO`=p.`REG.No` and c.Status='Student' and p.type=0 and p.Date in".$datestring."";
		$result=mysqli_query($conn,$query);
		if($result && mysqli_num_rows($result)>0){
			$row=mysqli_fetch_assoc($result);
			$value[$i++]=$row['count'];	
		}
		else{
			$value[$i++]=0;
		}
		$value[$i]=$value[$i-1]+$value[$i-2];
			$i++;
		$append="<table style=\"width:100%\"><tr><th>SI. NO</th><th>PARTICULARS</th><th>STAFF</th><th>STUDENTS</th><th>TOTAL</th></tr>";
		$append.="<tr><td>1</td><td>Out Patients</td><td>$value[12]</td><td>$value[13]</td><td>$value[14]</td></tr>";
		$append.="<tr><td>2</td><td>In Patients</td><td>$value[15]</td><td>$value[16]</td><td>$value[17]</td></tr>";
		$append.="<tr><td>3</td><td>Injection</td><td>$value[0]</td><td>$value[1]</td><td>$value[2]</td></tr>";
		$append.="<tr><td>4</td><td>Dressing</td><td>$value[3]</td><td>$value[4]</td><td>$value[5]</td></tr>";
		$append.="<tr><td>5</td><td>ENT</td><td>$tableValue[0]</td><td>$tableValue[1]</td><td>$tableValue[2]</td></tr>";
		$append.="<tr><td>6</td><td>Ortho</td><td>$tableValue[3]</td><td>$tableValue[4]</td><td>$tableValue[5]</td></tr>";
		$append.="<tr><td>7</td><td>Diabetologist</td><td>$tableValue[6]</td><td>$tableValue[7]</td><td>$tableValue[8]</td></tr>";
		$append.="<tr><td>8</td><td>Skin</td><td>$tableValue[9]</td><td>$tableValue[10]</td><td>$tableValue[11]</td></tr>";
		$append.="<tr><td>9</td><td>Eye</td><td>$tableValue[12]</td><td>$tableValue[13]</td><td>$tableValue[14]</td></tr>";
		$append.="<tr><td>10</td><td>Physiotherapist</td><td>$tableValue[15]</td><td>$tableValue[16]</td><td>$tableValue[17]</td></tr>";
		$append.="<tr><td>11</td><td>Lab</td><td>$value[6]</td><td>$value[7]</td><td>$value[8]</td></tr>";
		$append.="<tr><td>12</td><td>ECG</td><td>$value[9]</td><td>$value[10]</td><td>$value[11]</td></tr>";
		$append.="</table>";
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
			document.getElementById('header').innerHTML=\"<h3 align='center'>HEALTH CENTRE: ANNA UNIVERSITY:MIT CAMPUS<hr></h3>\";
			document.getElementById('entirepage').innerHTML=\"$dates\";
			document.getElementById('getstats').innerHTML='$append';
			window.print();
			window.history.go(-2);
		</script>";
	}
	mysqli_close($conn);
?>
