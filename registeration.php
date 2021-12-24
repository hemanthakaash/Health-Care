<?php
if(!isset($_POST['sub'])){
	echo "<script>window.history.go(-1);</script>";
	exit();
}
require_once "connect.php";
$name=mysqli_real_escape_string($conn,$_POST['name']);
$id=mysqli_real_escape_string($conn,$_POST['reg']);
$dob=mysqli_real_escape_string($conn,$_POST['dob']);
$status=mysqli_real_escape_string($conn,$_POST['status']);
$gender=mysqli_real_escape_string($conn,$_POST['gender']);
$bg=mysqli_real_escape_string($conn,$_POST['bg']);
$cn=mysqli_real_escape_string($conn,$_POST['cn']);
$pg=mysqli_real_escape_string($conn,$_POST['pg']);
$add=mysqli_real_escape_string($conn,$_POST['address']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$s="";
if($email!=""){
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
	echo "<script>alert(\"Email not Valid\");
			window.history.go(-1);
		</script>";
		exit();
}}
$allergy=mysqli_real_escape_string($conn,$_POST['allergy']);
$query="select * from clientrecord where `REG. NO` = '$id'";
$result=mysqli_query($conn,$query);
if($result && mysqli_num_rows($result)>0){
	echo "<script>
			alert('User already Exists');
			window.history.go(-1);
		</script>";
	exit();
}
$allergy=str_replace("\\r"," ",$allergy);
$allergy=str_replace("\\n","  ",$allergy);
$add=str_replace("\\r"," ",$add);
$add=str_replace("\\n","  ",$add);
$query="insert into clientrecord values('$name','$id','$status','$dob','$gender','$bg','$cn','$pg','$add','$email','$allergy')";
//echo $query;
if(mysqli_query($conn,$query)){
	$s1="Registration Successful";
	echo "<script>alert(\"$s1\");</script>";
}
mysqli_close($conn);
if($s=='error occured')
	$s="Registration Successful";

echo "<script>
	window.history.go(-2);
	</script>";

?>

