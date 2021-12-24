<html>
	<head>
		<title>Appointment</title>
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
		<div style="width:39%;float:left;">
			<div style="width:80%;" class="head"><h1 style="text-align:center;">Enter Details</h1></div><hr>
			<div class="firstdiv" >
				<label> Reg.NO : </label><br><br><br>
				<label> Reason : </label><br><br><br>
				<label> Doctor : </label><br><br>
			</div>
			<form>
				<div class="seconddiv" style="width:200px;">
					<input type="text" class="new" name="reg" placeholder="Reg. NO" required /><br><br>
					<textarea class="new" name="cause" style="height:65px;width:180px" placeholder="Reason" required ></textarea><br><br>
					<select id="doc" class="selectt" style="width:150px;"name="doc"></select><br><br>
				</div>	
				<div style="width:50%;clear:both;"><button type="submit" style="float:right;" name="submit">submit</button></div>
			</form>
		</div>
		<div style="float:right;width:60%;">
			<div style="width:80%;" class="head"><h1 style="text-align:center;">Appointments</h1></div><hr>
			<div id="tabe"></div>
		</div>
		<script>
			var x=document.getElementsByClassName('new');
			var i;
			for(i=0;i<x.length;i++){
				x[i].value='';
			}
		</script>
	</body>
</html>
<?php
	require_once 'connect.php';
	$query="select * from doctor";
	$result=mysqli_query($conn,$query);
	$append="";
	while($row=mysqli_fetch_assoc($result)){
		$n=$row['Name'];
		$u=$row['UserID'];
		$append=$append."<option value=\"$u\">$n</option>";
	}
	echo "<script>document.getElementById('doc').innerHTML='$append';</script>";
	$query="select * from Appointments order by Date asc";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1)
		echo "<script>document.getElementById('tabe').innerHTML=\"No Appointments left.\"</script>";
	else{
		$append="<form><table style=\"width:95%;\"><th>Date</th><th>REG.No</th><th>Doctor</th><th>PASS</th><th>Cancel</th>";
		while($row=mysqli_fetch_assoc($result)){
			$d=$row['Date'];
			$r=$row['REG.No'];
			$c=$row['Consulted'];
			$rs=$row['Reason'];
			$query="select Name from doctor where UserID='$c'";
			$result=mysqli_query($conn,$query);
			$name=mysqli_fetch_assoc($result)['Name'];
			$append=$append."<tr><td>$d</td><td>$r</td><td>$name</td><td><button class=\"th1\" name=\"pass\" value=\"$r:$c:$rs:$d\">PASS</button></td><td><button class=\"th1\" name-\"cancel\" value=\"$r:$c:$rs:$d\">Cancel</button></td></tr>";
		}
		$append=$append."</table></form>";
		echo "<script> document.getElementById('tabe').innerHTML='$append'; </script>";
	}
	if(isset($_GET['pass'])){
		$value=explode(":",$_GET['pass']);
		date_default_timezone_set('Asia/Kolkata');
		$time=date("h:i:s");
		$date=date("Y-m-d");
		$query="insert into patientrecord values('$date','$time','$value[0]','$value[1]','$value[2]','')";
		mysqli_query($conn,$query);
		$query="select Name from clientrecord where `REG. NO` = '$value[0]'";
		$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
		$name=$row['Name'];
		$query="insert into patients values('$date','$time','$value[0]','$name','$value[1]','$value[2]')";
		mysqli_query($conn,$query);
		$query="delete from appointments where Date='$value[3]' and `REG.No`=$value[0] and Consulted='$value[1]' and Reason='$value[2]'";
		mysqli_query($conn,$query);
	}
	if(isset($_GET['cancel'])){
		$value=explode(":",$_GET['cancel']);
		$query="delete from appointments where Date='$value[3]' and `REG.No`=$value[0] and Consulted='$value[1]' and Reason='$value[2]'";
		mysqli_query($conn,$query);
	}
	if(isset($_GET['submit'])){
		$doc=mysqli_real_escape_string($conn,$_GET['doc']);
		$reg=mysqli_real_escape_string($conn,$_GET['reg']);
		$query="select Name from clientrecord where `REG. NO`=$reg";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)<1){
			echo "<script>alert(\" User Not registered. \");
					window.history.go(-1);
				</script>";
		}
		$cause=mysqli_real_escape_string($conn,$_GET['cause']);
		date_default_timezone_set('Asia/Kolkata');
		$date=date("Y-m-d");
		$query="insert into Appointments values('$date','$reg','$doc','$cause')";
		mysqli_query($conn,$query);
		echo "<script>window.history.go(-2);</script>";
	}
?>