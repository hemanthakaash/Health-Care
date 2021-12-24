<?php
	session_start();
?>
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
				<label for="curpass">Enter Current Password:</label><br>
				&nbsp&nbsp&nbsp&nbsp <input type="password" placeholder="Current Password" id="curpass" name="curpass" required /><br><br>
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
	$cp=mysqli_real_escape_string($conn,$_POST['curpass']);
	$np=mysqli_real_escape_string($conn,$_POST['newpass']);
	$rp=mysqli_real_escape_string($conn,$_POST['renew']);
	$table=$_COOKIE['cp'];
	if($rp!=$np){
		echo "<script>
				alert('New password and the Re-entered password does not match');
				window.history.go(-1);
				exit();
			</script>";
	}
	if($table=="receptionist")
		$uid=$_COOKIE['uid'];
	elseif($table=="doctor")
		$uid=$_COOKIE['duid'];
	elseif($table=="pharmacist")
		$uid=$_COOKIE['puid'];
	elseif($table=="laboratory")
		$uid=$_COOKIE['luid'];
	else if($table=="dressing")
		$uid=$_COOKIE['druid'];
	else if($table=="injection")
		$uid=$_COOKIE['inuid'];
	$query="select Password from $table where UserID='$uid'";
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$p=$row['Password'];
	if($p!=md5($cp)){
		echo "<script>
				alert('Current Password does not match');
				window.history.go(-1);
				exit();
			</script>";
	}
	$query="update $table set Password=md5('$np') where UserID='$uid'";
	mysqli_query($conn,$query);
	echo "<script>
				alert('Password Changed');
				window.history.go(-1);
			</script>";
}
?>