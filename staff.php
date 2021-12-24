<html>
	<head>
		<title>Login DESK</title>
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
		<div class="head"><br><h1 align="center">LOGIN FORM</h1><br>
		<div align="center" id="ta"></div><hr></div><br><br>
		<div class="login" style="background:#53802d;">
			<form method="post" style="background:#53802d;">
				<div class="firstdiv">
					<label for="ID">ID :</label><br>
				</div>
				<div class="seconddiv">
					<input type="text" id="ID" name="name" required ><br><br>
				</div>
				<div class="firstdiv">
					<br><label for="pass" >Password :</label><br><br>
				</div>
				<div class="seconddiv"><br>
					<input type="password" id="pass" name="pwd" required ><br><br>
				</div><br><br>
				<div style="clear:both;" >
					<div style="width:65%;">
						<button type="submit" name="submit" style="float:right;"><b>Login</b></button><br>
					</div>
					<a href="passwordmail.php" style="color:#000;"><h5 id="fp">Forgot Password?</h5></a>
				</div>
			</form>
		</div>
	</body>
</html>
<?php
$table=$_COOKIE['login'];
if($table=='receptionist')
	$disp="Receptionist";
elseif($table=='doctor')
	$disp="Doctor";
elseif($table=='laboratory')
	$disp="Laboratory";
elseif($table=='pharmacist')
	$disp='Pharmacist';
elseif($table=='dressing')
	$disp='Dressings';
elseif($table=='injection')
	$disp='Injections';
echo "<script>
			document.getElementById('ta').innerHTML='$disp';
		</script>";
if(isset($_POST['submit'])){
	require_once 'connect.php';
	$table=$_COOKIE['login'];
	$name=mysqli_real_escape_string($conn,$_POST['name']);
	$pwd=mysqli_real_escape_string($conn,$_POST['pwd']);
	$query="select `Password` from `$table` where UserID=$name";
	$result=mysqli_query($conn,$query);
	if(!$result){
		echo "<script>
				alert('Some error has occured in connecting with the database.\n Please try after some time');
				window.history.go(-1);
			</script>";
			exit();
	}
	$result_check=mysqli_num_rows($result);
	if($result_check<1)
	{
		echo "<script>alert('User Not Found');
				window.history.go(-1);
			</script>";
		exit();
	}
	if($row=mysqli_fetch_assoc($result)){
		if($row['Password']==md5($pwd)){
			date_default_timezone_set('Asia/Kolkata');
			$date=date("Y-m-d");
			$time=date("h:i:s");
			$query="update `$table` set `Date`='$date',`Time`='$time' where `UserId`='$name'";
			mysqli_query($conn,$query);
			if($table=="receptionist"){
				setcookie("uid","$name",time()+(3600*18));
				echo "<script>
						location.replace('reception.php');
					</script>";
			}
			else if($table=="doctor"){
				setcookie("duid","$name",time()+(3600*18));
				$query="update `$table` set Status=1 where UserID=$name";
				mysqli_query($conn,$query);
				echo "<script>
						location.replace('Doctor_desk.php');
					</script>";
			}
			else if($table=="pharmacist"){
				setcookie("puid","$name",time()+(3600*18));
				echo "<script>
						location.replace('pharmacist.php');
					</script>";
			}
			else if($table=="laboratory"){
				setcookie("luid","$name",time()+(3600*18));
				echo "<script>
						location.replace('laboratory.php');
					</script>";
			}
			else if($table=="dressing"){
				setcookie("druid","$name",time()+(3600*18));
				echo "<script>
						location.replace('dressing.php');
					</script>";
			}
			else if($table=="injection"){
				setcookie("inuid","$name",time()+(3600*18));
				echo "<script>
						location.replace('injection.php');
					</script>";
			}
		}
		else{
			echo "<script>alert('Password Does not match');
					window.history.go(-1);
				</script>";
		}
	}
	if(mysqli_close($conn))
		echo "<br>Connection closed";
}
?> 