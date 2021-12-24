<?php
	if(!isset($_COOKIE['note']))	exit();
?>
<html>
	<head>
		<title>Notifications</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="Head"><h1>Notifications</h1></div>
		<div id="firstcon">
			No Notifications Available
		</div>
	</body>
</html> 
<?php
	require_once 'connect.php';
	date_default_timezone_set('Asia/Kolkata');
	$today=date("Y-m-d");
	$append="<table><tr><th>MESSAGE</th></tr>";
	$table=$_COOKIE['note'];
	$query="select * from $table";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_assoc($result)){
		$da=$row['Expiry Date'];
		$diff = date_diff(date_create($today), date_create($da));
		$d=$diff->format("%a");
		if($d<60){
			$n=$row['Name'];
			$data="The expiry date of $n is nearing . Its on $da";
			$append=$append."<tr><td>$data</td></tr>";
		}
	}
	$query="select Name,sum(`Quantity Left`) from $table group by Name";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_assoc($result)){
		$a=$row['sum(`Quantity Left`)'];
		if($a<10){
			$n=$row['Name'];
			$data="The quantity of $n is low. Only $a pieces are left.";
			$append=$append."<tr><td>$data</td></tr>";
		}
	}
	$append=$append."</table>";
	echo "<script>document.getElementById('firstcon').innerHTML='$append';</script>";
	setcookie("note","",time()-30);
?>	