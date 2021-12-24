<?php
	if(!isset($_COOKIE['puid']))	exit();
	setcookie("note","",time()-30);
	require_once 'connect.php';
	$ud=$_COOKIE['puid'];
	$query="select Name from pharmacist where UserID='$ud'";
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$name="Welcome ".$row['Name'];
	$append="";
	if(isset($_GET['newpharma'])){
		setcookie("page","pharma",time()+3600);
		header("Location: getnewuser.html");
	}
	if(isset($_GET['stock'])){
		setcookie("note","stock",time()+3600);
		header("Location: enterstocks.php");
	}
	if(isset($_GET['note'])){
		setcookie("note","stock",time()+3600);
		header("Location: notification.php");
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","pharmacist");
		header("Location: changepass.php");
	}
	if(isset($_GET['logout'])){
		setcookie("puid","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
	$app="";
	$query="select Name from medicine";
	$result=mysqli_query($conn,$query);
	while($row=mysqli_fetch_assoc($result)){
		$n=$row['Name'];
		$app=$app."<option value=\"$n\">$n</option>";
	}
?>
<html>
	<head>
		<title>Pharmacist</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="3600">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
		<style>
			#abd:hover{
				color:red;
			}
			input{
				width:180px;
			}
			button{
				height:45px;
			}
		</style>
		<script src="jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<div class="head"><h1 id="n"><?php echo $name?></h1><hr></div>
		<div class="pharmatab" >
			<form>
				<button name="newpharma">Create New User</button>
				<button name="stock">Stocks</button>
				<button name="note">Notifications</button>
				<div class="two" style="float:right;">
					<button style="float:right;"><b>Log</b></button>
					<div class="one">
						<button name="changepass">Change Password</button>				
						<button name="logout">logout</button>
					</div>
				</div>
			</form>
			<button name="stats" onclick="medst();">Statistics</button>
		</div>
		<h1 class="head">To Be Distributed</h1>
		<div id="table" style="width:45%;float:left;">
			<?php
				$query="select d.Patient_Id,c.Name,d.Prescription from distribute d LEFT join clientrecord c on d.Patient_Id=c.`REG. NO`";
				$result=mysqli_query($conn,$query);
				if($result){
				if(mysqli_num_rows($result)<1){
			?>
				<h3>No pending Distributions</h3>
			<?php
				}
				else{
					$append="<table style=\"width:100%\"><tr><th>ID</th><th>Name</th><th>Prescription</th></tr>";
					while($row=mysqli_fetch_assoc($result)){
						$id=$row['Patient_Id'];
						$med=$row['Prescription'];
						$name=$row['Name'];
						$append=$append."<tr><td>$id</td><td>$name</td><td>".$med."</td></tr>";
					}
					$append=$append."</table>";
				}}
				echo $append;
			?>
		</div>
		<div id="rightcont" style="width:50%;float:right;">
			<form action="printbill.php">
				<input type="text" id="pat" name="pat" placeholder="Enter Reg. No" required><br><hr><hr>
				<select class="selectt" name="med1">
					<?php
						echo $app;
					?>
				</select>&nbsp
					<input type="number" name="qan1" required/><br><br>
					<p id="abd" style="font-family: cursive;">Add</p>
					<input type="hidden" name="cnt1" id="cnt1">
				<button type="submit" name="med">Done</button>
			</form>
		</div>
		<script>
				var str1;var cnt=1;
				document.getElementById('cnt1').value=1;
				$(document).ready(function(){
					$("#abd").click(function(){
						cnt++;	
str1='<br><select class="selectt" name="med'+cnt+'"><?php echo $app  ?></select>&nbsp&nbsp<input type="number" name="qan'+cnt+'" required/><br>';
						$(this).before(str1)
						document.getElementById('cnt1').value=cnt;
					});
				});
				function medst(){
					window.location.href="medstat.php";
					return false;
				}
		</script>
	</body>
	<script></script>
</html>
