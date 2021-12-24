<?php
require_once 'connect.php';
//session_start();
date_default_timezone_set('Asia/Kolkata');
$name=mysqli_real_escape_string($conn,$_POST['name']);
$id=mysqli_real_escape_string($conn,$_POST['id']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$pass=md5(mysqli_real_escape_string($conn,$_POST['pass']));
$date=date("Y-m-d");
$time=date("h:i:s");
$s="";
if(!isset($_COOKIE['page']))
	header("Location: errorpage.php");
else if($_COOKIE['page']=="recep"){
	$query="select * from receptionist where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if($result)
	if(mysqli_num_rows($result)<1){
		$query="insert into receptionist values('$name','$id','$pass','$email','$date','$time')";
		if(!mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}
	else{
		echo "<script>
			alert('User already exists');
			window.history.go(-1);
			</script>";
	}
}
else if($_COOKIE['page']=="doc"){
	$query="select * from doctor where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if($result){
	if(mysqli_num_rows($result)<1){
		$query="insert into doctor values('$name','$id','$pass','$email','$date','$time','','',0)";
		if(mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}}
	else{
		echo "<script>
				alert('User already exists');
				window.history.go(-1);
			</script>";
	}
}
else if($_COOKIE['page']=="pharma"){
	$query="select * from pharmacist where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1){
		$query="insert into pharmacist values('$name','$id','$pass','$email','$date','$time')";
		if(mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}
	else{
		echo "<script>
				alert('User already exists');
				window.history.go(-1);
			</script>";
	}
}
else if($_COOKIE['page']=="lab"){
	$query="select * from laboratory where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1){
		$query="insert into laboratory values('$name','$id','$pass','$email','$date','$time')";
		if(mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}
	else{
		echo "<script>
				alert('User already exists');
				window.history.go(-1);
			</script>";
	}
}
else if($_COOKIE['page']=="dressing"){
	$query="select * from dressing where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1){
		$query="insert into dressing values('$name','$id','$pass','$email','$date','$time')";
		if(mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}
	else{
		echo "<script>
				alert('User already exists');
				window.history.go(-1);
			</script>";
	}
}
else if($_COOKIE['page']=="injection"){
	$query="select * from injection where UserID='$id'";
	$result=mysqli_query($conn,$query);
	if(mysqli_num_rows($result)<1){
		$query="insert into injection values('$name','$id','$pass','$email','$date','$time')";
		if(mysqli_query($conn,$query)){
			echo "<script>
				alert('User created');
				window.history.go(-2);
			</script>";
		}
		else{
			echo "<script>
				alert('Some error Occured');
				window.history.go(-1);
			</script>";
		}
	}
	else{
		echo "<script>
				alert('User already exists');
				window.history.go(-1);
			</script>";
	}
}
mysqli_close($conn);
setcookie("page","",time()-30);
//echo "<script>window.history.go(-2);</script>";
?>
