<?php
	if(!isset($_COOKIE['uid']))	exit();
	require_once 'connect.php';
?> 
<?php
	if(isset($_GET['logout'])){
		setcookie("uid","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","receptionist",time()+(18*3600));
		header("Location: changepass.php");
	}
	if(isset($_GET['new_recep'])){
		setcookie("page","recep",time()+(18*3600));
		echo "<script>document.location='getnewuser.html';</script>";
	}
	if(isset($_GET['new_doc'])){
		setcookie("page","doc",time()+(18*3600));
		echo "<script>document.location='getnewuser.html';</script>";
	}
	if(isset($_GET['send'])){
		$reg=$_GET['hidden'];
		$doc=mysqli_real_escape_string($conn,$_GET['listofdoc']);
		$cause=mysqli_real_escape_string($conn,$_GET['cause']);
		$cause=str_replace("\\r"," ;",$cause);
		$cause=str_replace("\\n","  ",$cause);
		$query="select `Name` from `clientrecord` where `Reg. No`='$reg'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_assoc($result);
		$name=$row['Name'];
		date_default_timezone_set('Asia/Kolkata');
		$time=date("G:i:s");
		$date=date("Y-m-d");
		$query="insert into patients values('$date','$time','$reg','$name','$doc','$cause')";
		mysqli_query($conn,$query);
		$query="insert into patientrecord values('$date','$time','$reg','$doc','$cause','','',1)";
		mysqli_query($conn,$query);
		setcookie("reg","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
?>
<!DOCTYPE html>
<HTML>
	<head>
		<title>Receptionist</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="3600">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
		<!--link href="https://www.w3schools.com/w3css/4/w3.css" rel="stylesheet" type="text/css" /-->
	</head>
	<body>
		<div class="head"><h1 id="f">
			<?php
				$query="select Name from receptionist where UserID=".$_COOKIE['uid'];
				$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
				$append="Welcome ".$row['Name'];
				echo $append;
			?>
		</h1></div><hr>
		<div class="tab">
			<div class="two">
				<button class="recepbut" name="profile">Profile</button>
				<div class="one">
					<button class="recepbut" name="viewdetails" onclick="viewprof()">View Profile</button><br>			
					<button class="recepbut" name="updateprofile" onclick="upprof()">Update Profile</button>
				</div>
			</div>
			<button class="recepbut" name="new_pat" onclick="newpat()">Register a patient</button>
			<button class="recepbut" name="patientstat" onclick="patstat()">Patients</button>
			<!--button class="recepbut" name="assignspec" onclick="assign()">Assign Specialization</button-->
			<form>
				<button class="recepbut" name="new_recep">create new Receptionist</button>
				<button class="recepbut" name="new_doc">create new Doctor</button>
				<div class="two" style="float:right;">
					<button class="recepbut"><b>Log</b></button>
					<div class="one">
						<button class="recepbut" name="changepass">Change Password</button>				
						<button class="recepbut" name="logout">logout</button>
					</div>
				</div>
			</form>
		</div><br><br>
		<div style="width:40%;float:left;padding-bottom:30px;">
			<form method="get">
				<div>
					<label for="reg" id="label1">Search for:</label>
					<input type="text" id="reg" placeholder="Enter the REG. NO" name="reg" required /><br><br>
					<input type="submit" name="GO" value="Search"/><br><br>
				</div>
			</form>
			<form>
				<div style="padding-top:25px;">
					<label for="cause">Cause of visit:</label><br>
					<textarea name="cause" id="cause" style="width:250px;height:75px;"></textarea><br><br>
					<label for="lisofdoc">Available Doctors</label>&nbsp&nbsp&nbsp
					<input type="hidden" name="hidden" id="hidden"/>
					<select id="listofdoc" name="listofdoc" class="selectt" style="width:100px;" required/>
						<?php 
							$query="select * from doctor where `Status`=1";
							$append="";
							$result=mysqli_query($conn,$query);
							while($row=mysqli_fetch_assoc($result)){
								$user=$row['Name'];
								$value=$row['UserID'];
								$append=$append."<option value=$value>$user</option>";
							}
							echo $append;	
						?>
					</select><br><br>
					<input type="submit" name="send" value="send">
				</div>
			</form>
		</div>
		<div style="width:60%;float:right;">
			<p><b id="student_name"></b></p>                    
			<p id="student"> </p><br>  
		</div>
		<script>
			function assign(){
				window.location.href="assignSpec.php";
			}
			function patstat(){
				window.location.href="statistics.php";
			}
			function upprof(){
				window.location.href="updateprofile.php";
			}
			function viewprof(){
				window.location.href="viewdetails.php";
			}
			function newpat(){
				window.location.href="register.html";
			}
		</script>
		<?php
		if(isset($_GET['GO'])){
		$reg=mysqli_real_escape_string($conn,$_GET['reg']);
		$query="select Name from `clientrecord` where `REG. NO`='$reg'";
		echo "<script>document.getElementById('hidden').value=$reg;</script>";
		$result=mysqli_query($conn,$query);
		if($result){
			if(mysqli_num_rows($result)<1){
				echo "<script>
						document.getElementById('student').innerHTML='<b><u>User Not Found<u></b>';
						</script>";
			}
			else{
				$row=mysqli_fetch_assoc($result);
				$n=$row['Name'];
				echo "<script>
						document.getElementById('student_name').innerHTML='<b>$n</b>';
					</script>";
				$query="select * from patientrecord where `REG.No`='$reg'";
				$result=mysqli_query($conn,$query);
				if($result){
					if(mysqli_num_rows($result)<1){
						echo "<script>
								document.getElementById('student').innerHTML='NO Records Found';
							</script>";
					}
					else{
						$append="<table><tr><th>Date</th><th>Time</th><th>Consulted</th><th>Reason</th><th>Prescribed Medicine</th></tr>";
						while($row=mysqli_fetch_assoc($result)){
							$date=$row['Date'];
							$time=$row['Time'];
							$consulted=$row['Consulted'];
							$reason=$row['Reason'];
							$prescription=$row['Prescription'];
							$append=$append."<tr><td>$date</td><td>$time</td><td>$consulted</td><td>$reason</td><td>$prescription</td></tr>";
						}
						$append=$append."</table>";
						echo "<script>
								document.getElementById('student').innerHTML='$append';
							</script>";
					}
				}
			}
		}
		else
			header("Location: errorpage.php");
		}?>
	</body>
</html>
