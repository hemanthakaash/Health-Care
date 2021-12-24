<html>
	<head>
		<title>Change Password</title>
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
		<div class="head"><h1>Forgot Password</h1></div><hr>
		<div id="cpd">
			<form method="get">
				<label for="userid">Enter User Id:</label><br>
				&nbsp&nbsp&nbsp&nbsp<input type="text" placeholder="User-ID" id="user" name="user" required/><br><br>
				<div align="center"><button style="width:200px;height:35px;" name="mail">Submit</button></div>
			</form>
		</div>
		<div id="gen" class="cph">
			<p class="l1">A Mail has been sent to you... </p>
			<div>
				<form method="post">
					<label for="userid">Enter the OTP:</label><br>
					&nbsp&nbsp&nbsp&nbsp<input type="text" placeholder="OTP" id="rand" name="rand" required/><br><br>
					<div align="center"><button style="width:200px;height:35px;" name="otp">change Password</button></div>
				</form>
			</div>
		</div>
	</body>
</html>
<?php  
require_once 'connect.php';
	if(isset($_GET['mail'])){
		$login=$_COOKIE['login'];
		$user=$_GET['user'];
		setcookie('user',$user);
		$query="select `Email` from $login where UserID=$user";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_assoc($result);
		$email=$row['Email'];
		$otp=rand(100000,999999);
		$msg="Do not share this OTP with anyone.Your OTP is $otp";
		$headers = "From: hemanthakaash27@gmail.com" . "" ;
		echo "<script>alert(\"$email,$msg,$headers\");</script>";
		//ini_set('SMTP','myserver');
		//ini_set('smtp_port',25);
		//ini_set('sendmail_from', 'hemanthakaash27@gmail.com');
		mail($email,"Health Centre OTP...",$msg,$headers);
		setcookie("otp",$otp,180);
		echo "<script>document.getElementById('gen').style.display=\"block\";</script>";
	}
	if(isset($_POST['opt'])){
		$otp=$_POST['rand'];
		if($otp==$_COOKIE['otp']){
			header("Location: getpassword.php");
		}
		else{
			setcookie('user',"",-10);
			echo "<script>alert('OTP does not match');
				window.history.go(-1);
			</script>";
		}
	}
?>