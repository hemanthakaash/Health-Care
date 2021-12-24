<html>
	<head>
		<title>Bill</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="3600">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
		<style>
			input{
				width:180px;
			}
			button{
				height:45px;
			}
		</style>
	</head>
	<body>
		<table id="tabler" style="width:60%;float:left"></table>
		<div id="printer">
			<form>
				<button name="print" style="float:right">Print</button>
			</form>
		</div>
	</body>
</html>
<?php
require_once 'connect.php';
if(isset($_GET['med'])){
	$val=$_GET['cnt1'];
	$pat=$_GET['pat'];
	$bool=true;
	$append="<tr><th>Medicine</th><th>Quantity</th><th>Notes</th></tr>";
	date_default_timezone_set('Asia/Kolkata');
	$time=date("h:i:s");
	$date=date("Y-m-d");
	$query="select Status from clientrecord where `REG. NO`='$pat'";
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$status=$row['Status'];
	for($i=1;$i<=$val;$i++){
		$name=$_GET['med'.$i];
		$quan=$_GET['qan'.$i];
		$append.="<tr><td>$name</td><td>$quan</td><td></td></tr>";
		$query="select sum(`Quantity Left`) from stock where Name='$name'";
		$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
		$bal=$row['sum(`Quantity Left`)'];
		if ($bal<$quan){
			echo "<script>alert(\"$name not enough stock is present.\");
				</script>";
			$bool=false;
		}
		else{
			$query="select count(Name) from stock where Name='$name'";
			$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$line=$row['count(Name)'];
			$quanti=$quan;
			for($j=0;$j<$line;$j++){
				$query="select `Quantity Left` from stock where Name='$name' order by `Expiry Date` asc limit 1";
				$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
				$q=$row['Quantity Left'];
				if($q<=$quan){
					$query="delete from stock where Name='$name' order by `Expiry Date` asc limit 1";
					mysqli_query($conn,$query);
					$quan-=$q;
					if($quan==0)
						break;
				}
				else{
					$query="update stock set `Quantity Left`=`Quantity Left`-$quan where Name='$name'order by `Expiry Date` asc limit 1";
					mysqli_query($conn,$query);
					break;
				}
			}
			$query="insert into medicinerecord values('$date','$time','$pat','$name','$quanti','$bal')";
			mysqli_query($conn,$query);
		}
	}
	if ($bool){
		$query="delete from distribute where `Patient_ID`='$pat'";
		mysqli_query($conn,$query);
	}
	unset($_GET['med']);
	setcookie("medappend",$append,time()+3600);
	echo "<script>document.getElementById('tabler').innerHTML=\"$append\";</script>";
}
if(isset($_GET['print'])){
	$append=$_COOKIE['medappend'];
	echo "<script>
			document.getElementById('printer').innerHTML='';
			document.getElementById('tabler').innerHTML=\"$append\";
			window.print();
			window.history.go(-2);
		</script>";
}
?>