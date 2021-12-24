<html>
	<head>
		<title>Change Password</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database">
		<meta name="description" content="Medical Transactions">	
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="head"><h1>Change Password</h1></div><hr><br>
		<div id="cpd">
			<form method="post">
				<label for="newpass">Enter New Password:</label><br>
				&nbsp&nbsp&nbsp&nbsp <input type="Password" placeholder="New Password" id="newpass" name="newpass" required /><br><br>
				<label for="renew">Re-Enter New Password:</label><br>
				&nbsp&nbsp&nbsp&nbsp <input type="password" placeholder=" Re-enter New Password" id="renew" name="renew" required /><br><br><br>
				<div align="center"><button style="width:200px;height:35px;" name="change">Change Password</button></div>
			</form>
		</div>
	</body>
</html>
<?php
require_once 'connect.php';
if(isset($_POST['change'])){
	$cp=mysqli_real_escape_string($conn,$_POST['newpass']);
	$rn=mysqli_real_escape_string($conn,$_POST['renew']);
	if($cp==$rn){
		$log=$_COOKIE['login'];
		$user=$_COOKIE['user'];
		$query="update `$log` set Password=md5('$cp') where UserID='$user'";
		echo "<script>alert('$query');</script>";
		if(mysqli_query($conn,$query)){
			echo "<script>alert('Password Changed Successfully');
					window.history.go(-3);
				</script>";
		}
		setcookie('user',"",time()-30);
	}
	else{
		echo "<script>alert('New password and Re-entered password do not match');
				window.history.go(-1);
			</script>";
	}
}
?>