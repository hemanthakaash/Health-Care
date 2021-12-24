<html>
	<head>
		<title>View Profile</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database">
		<meta name="description" content="Medical Transactions">	
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="head" style="width:30%;"><h1 align="center">View Details</h1></div><hr><br><br>
		<div style="width:55%;">
			<form>
				<select class="selectt" name="person">
					<option value="stud" id="stud">Patient</option>
					<option value="doc" id="doc">Doctor</option>
				</select>
				<select class="selectt" name="typho">
					<option value="REG. NO" id="regno">Register Number</option>
					<option value="Contact Number" id="phone">Phone No.</option>
				</select>

				<input type="number" name="id" style="float:right;" required><br><br>
				<button name="view" style="height:40px;">submit</button>
			</form>
		</div>
		<div id="disp" class="details">
			<div>
				<div style="width:50%;float:left;"><label>Name :</label></div>
				<div style="width:50%;float:left;"><label id="na1"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Status :</label></div>
				<div style="width:50%;float:left;"><label id="na3"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>REG. NO :</label></div>
				<div style="width:50%;float:left;"><label id="na12"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>D. O. B :</label></div>
				<div style="width:50%;float:left;"><label id="na4"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Gender :</label></div>
				<div style="width:50%;float:left;"><label id="na5"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Blood Group :</label></div>
				<div style="width:50%;float:left;"><label id="na6"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;clear:both;float:left;"><label>Contact Number :</label></div>
				<div style="width:50%;float:left;"><label id="na7"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;clear:both;float:left;"><label>Parent/Guardian :</label></div>
				<div style="width:50%;float:left;"><label id="na8"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Address :</label></div>
				<div style="width:50%;float:left;"><label id="na11"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Email-Id :</label></div>
				<div style="width:50%;float:left;"><label id="na9"></label></div>
			</div><br><br>
			<div>
				<div style="width:50%;float:left;"><label>Particular Medical Details :</label></div>
				<div style="width:50%;float:left;"><label id="na10"></label></div>
			</div><br><br>
			<br><br>
		</div>
		<div id="disd" class="details">
			<div>
				<div style="width:40%;float:left;"><label>Name :</label></div>
				<div style="width:50%;float:left;"><label id="do1"></label></div>
			<div><br><br>
			<div>
				<div style="width:40%;float:left;"><label>Specializes in :</label></div>
				<div style="width:50%;float:left;"><label id="do2"></label></div>
			<div><br><br>
			<div>
				<div style="width:40%;float:left;"><label>Visiting Hours:</label></div>
				<div style="width:50%;float:left;"><label id="do3"></label></div>
			<div><br><br>
			<div>
				<div style="width:40%;float:left;"><label>Last Logged On :</label></div>
				<div style="width:50%;float:left;"><label id="do4"></label></div>
			<div><br><br>
			<div>
				<div style="width:40%;float:left;"><label>Currently Available :</label></div>
				<div style="width:50%;float:left;"><label id="do5"></label></div>
			<div><br><br>
		</div>
	</body>
</html>
<?php
	require_once 'connect.php';
	if(isset($_GET['view'])){
		$reg=$_GET['id'];
		$type=$_GET['person'];
		$typho=$_GET['typho'];
		if($type=="stud"){
			echo "<script>document.getElementById('stud').selected=\"true\";</script>";
			$query="select * from clientrecord where `$typho`='$reg'";
			$result=mysqli_query($conn,$query);
			if($result){
			if(mysqli_num_rows($result)<1){
				echo "<script>document.getElementById('disp').innerHTML=\"<h2 class='head' style='color:#000;'> User Not Found</h2>\";
					document.getElementById('disp').style.display=\"block\";
					</script>";
			}
			else{
				$row=mysqli_fetch_assoc($result);
				$name=$row['Name'];
				$ID=$reg;
				$REG=$row['REG. NO'];
				$status=$row['Status'];
				$DOB=$row['D.O.B'];
				$gender=$row['Gender'];
				$bg=$row['Blood Group'];
				$cn=$row['Contact Number'];
				$pg=$row['Parent/Guardian'];
				$email=$row['Email-id'];
				$address=$row['Address'];
				$note=$row['Specific medical details'];
				echo "<script>
					document.getElementById('na1').innerHTML='$name';
					document.getElementById('na3').innerHTML='$status';
					document.getElementById('na4').innerHTML='$DOB';
					document.getElementById('na5').innerHTML='$gender';
					document.getElementById('na6').innerHTML='$bg';
					document.getElementById('na7').innerHTML='$cn';
					document.getElementById('na8').innerHTML='$pg';
					document.getElementById('na9').innerHTML='$email';
					document.getElementById('na10').innerHTML='$note';
					document.getElementById('na11').innerHTML='$address';
					document.getElementById('na12').innerHTML='$REG';
					document.getElementById('disp').style.display=\"block\";
				</script>";			
			}
			}
		}
		else if($type="doc"){
			echo "<script>document.getElementById('doc').selected=\"true\";</script>";
			$query="select * from doctor where UserId='$reg'";
			$result=mysqli_query($conn,$query);
			if(mysqli_num_rows($result)<1){
				echo "<script>document.getElementById('disd').innerHTML=\"<h2 class='head' style='color:#000;'> User Not Found</h2>\";
						document.getElementById('disd').style.display=\"block\";
					</script>";
			}
			else{
				$row=mysqli_fetch_assoc($result);
				$name=$row['Name'];
				$speciality=$row['Speciality'];
				$visit=$row['Visiting Hours'];
				$date=$row['Date'];
				$time=$row['Time'];
				$available=$row['Status'];
				if($available==1)
					$available="Yes";
				else $available="No";
				echo "<script>document.getElementById('do1').innerHTML=\"$name\";
							document.getElementById('do2').innerHTML=\"$speciality\";
							document.getElementById('do3').innerHTML=\"$visit\";
							document.getElementById('do4').innerHTML=\"$date $time\";
							document.getElementById('do5').innerHTML=\"$available\";
							document.getElementById('disd').style.display=\"block\";	
					</script>";
			}
		}
	}
?>
