<?php
	session_start();
?>
<html>
	<head>
		<title>Update Profile</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database">
		<meta name="description" content="Medical Transactions">	
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="head"><h1 align="center">Update Details</h1></div><hr><br><br>
		<div style="width:45%;">
			<form>
				<select class="selectt" style="float:left;" name="person">
					<option value="pat" id="pat">Patient</option>
					<option value="doc" id="doc">Doctor</option>
				</select>
				<input type="number" name="id" style="float:right;" required><br><br>
				<button name="update" style="height:40px;">submit</button>
			</form>
		</div>
		<div id="uppat">
			<div class="firstdiv"  style="width:350px;">
				<label for="bg" >Blood Group : </label><br><br>
				<label for="cn" >contact Number : </label><br><br>
				<label for="pg" >Parent/Guardian Contact Number : </label><br><br><br>
				<label for="address">Address : </label><br><br><br><br><br>
				<label for="email" >Email-Id : </label><br><br><br>
				<label for="allergy" >Particular Specific medical details : </label><br><br>
			</div>
			<form method="post">
				<div style="seconddiv">
					<input type="text" class="new" id="bg" name="bg"><br><br>
					<input type="text" class="new" id="cn" name="cn"><br><br>
					<input type="text" class="new" id="pg" name="pg"><br><br>
					<textarea class="new" style="height:100px;width:300px;" id="address" name="address" placeholder="Address"></textarea>
					<br><br>
					<input type="text" class="new" id="email" name="email"><br><br>
					<textarea class="new" style="height:100px;width:300px;" id="allergy" name="allergy" placeholder="Any kind of allergic reaction to any substance- mention here."></textarea>
					<br><br>
				</div>
				<div style="width:30%;">
					<button type="submit" style="width:150px;height:50px;float:right;" name="sub1">Update</button>
				</div>
			</form><br><br>
		</div>
		<div id="updoc">
			<div class="firstdiv"  style="width:350px;">
				<label for="email" >Email-Id : </label><br><br>
				<label for="special" >Speciality : </label><br><br>
				<label for="visit" >Visiting Hours : </label><br><br>
			</div>
			<form method="post">
				<div style="seconddiv">
					<input type="text" class="new" id="email" name="email"><br><br>
					<!--input type="text" class="new" id="special" name="special"><br><br-->
					<select class="selectt" style="width:10%;" id="special" name="special">
						<option value="ENT">ENT</option>
						<option value="ortho">Ortho</option>
						<option value="Diabetologist">Diabetologist</option>
						<option value="Skin">Skin</option>
						<option value="Eye">Eye</option>
						<option value="Physiotherapist">Physiotherapist</option>
					</select><br><br>
					<textarea class="new" id="visit" name="visit"></textarea>
					<br><br>
				</div>
				<div style="width:30%;">
					<button type="submit" style="width:150px;height:50px;float:right;" name="sub2">Update</button>
				</div><br><br>
			</form>
		</div>
	</body>
</html>
<?php   
	require_once 'connect.php';
	if(isset($_GET['update'])){
		$id=$_GET['id'];
		setcookie("reg",$id,time()+3600);
		$type=$_GET['person'];
		if($type=="pat"){
			echo "<script>
					document.getElementById('pat').selected=\"true\";
					document.getElementById('uppat').style.display=\"block\";</script>";
		}
		if($type=="doc"){
			echo "<script>
					document.getElementById('doc').selected=\"true\";
					document.getElementById('updoc').style.display=\"block\";</script>";
		}
	}
	if(isset($_POST['sub1'])){
		$reg=$_COOKIE['reg'];
		$bg=mysqli_real_escape_string($conn,$_POST['bg']);
		if($bg!=""){
			$query="update clientrecord set `Blood Group`='$bg' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		$cn=mysqli_real_escape_string($conn,$_POST['cn']);
		if($cn!=""){
			$query="update clientrecord set `Contact Number`='$cn' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		$pg=mysqli_real_escape_string($conn,$_POST['pg']);
		if($pg!=""){
			$query="update clientrecord set `Parent/Guardian`='$pg' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		$email=mysqli_real_escape_string($conn,$_POST['email']);
		if($email!=""){
			$query="update clientrecord set `Email-id`='$email' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		$address=mysqli_real_escape_string($conn,$_POST['address']);
		if($address!=""){
			$address=str_replace("\\r"," ",$address);
			$address=str_replace("\\n","  ",$address);
			$query="update clientrecord set `Address`='$address' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		$allergy=mysqli_real_escape_string($conn,$_POST['allergy']);
		if($allergy!=""){
			$allergy=str_replace("\\r"," ",$allergy);
			$allergy=str_replace("\\n","  ",$allergy);
			$query="update clientrecord set `Specific medical details`='$allergy' where `REG. NO`='$reg'";
			mysqli_query($conn,$query);
		}
		echo "<script>window.history.go(-2);</script>";
	}
	if(isset($_POST['sub2'])){
		$reg=$_COOKIE['reg'];
		$special=mysqli_real_escape_string($conn,$_POST['special']);
		if($special!=""){
			$query="update doctor set `Speciality`='$special' where `UserID`='$reg'";
			mysqli_query($conn,$query);
		}
		$visit=mysqli_real_escape_string($conn,$_POST['visit']);
		if($visit!=""){
			$query="update doctor set `Visiting Hours`='$visit' where `UserID`='$reg'";
			mysqli_query($conn,$query);
		}
		$email=mysqli_real_escape_string($conn,$_POST['email']);
		if($email!=""){
			$query="update doctor set `Email`='$email' where `UserID`='$reg'";
			mysqli_query($conn,$query);
		}
		echo "<script>window.history.go(-2);</script>";
	}
?>