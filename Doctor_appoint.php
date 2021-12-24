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
		<div>
			<div style="width:55%;" class="head"><h1 style="text-align:center;">Appointments</h1></div><hr>
			<div id="tabe"></div>
		</div>
	</body>
</html>
<?php
	require_once 'connect.php';
	$query="select * from Appointments where Consulted=".$_COOKIE['duid']." order by Date asc";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1)
		echo "<script>document.getElementById('tabe').innerHTML=\"No Appointments left.\"</script>";
	else{
		$append="<form><table style=\"width:55%;\"><th>Date</th><th>REG.No</th><th>Reason</th>";
		while($row=mysqli_fetch_assoc($result)){
			$d=$row['Date'];
			$r=$row['REG.No'];
			$rs=$row['Reason'];
			$append=$append."<tr><td>$d</td><td>$r</td><td>$rs</td></tr>";
		}
		$append=$append."</table></form>";
		echo "<script> document.getElementById('tabe').innerHTML='$append'; </script>";
	}
?>