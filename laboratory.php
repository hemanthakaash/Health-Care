<?php
	if(!isset($_COOKIE['luid']))exit();
	require_once 'connect.php';
	$ud=$_COOKIE['luid'];
	$query="select Name from laboratory where UserID='$ud'";
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$n=$row['Name'];
	if(isset($_GET['newlab'])){
		setcookie("page","lab",time()+3600);
		header("Location: getnewuser.html");
	}
	if(isset($_GET['logout'])){
		setcookie("luid","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","laboratory");
		header("Location: changepass.php");
	}
	if(isset($_GET['pending'])){
		header("Location: uploads.php");
	}
	if(isset($_GET['done'])){
		$id=$_GET['done'];
		$query="update labrecord set completed =1 where Patient_ID='$id'";
		mysqli_query($conn,$query);
		echo "<script>window.history.go(-1);</script>";
	}
	if(isset($_POST['submitfile'])){
		$target_dir = "Test_Reports/";
		$tmp_file = $target_dir . basename($_FILES["fileToUpload"]['name']);
		$imageFileType = strtolower(pathinfo($tmp_file,PATHINFO_EXTENSION));
		$name=$_POST['pat'].$_POST['test'];
		$target_file=$name.".".$imageFileType;
		$new_file = $target_dir.$target_file;
		move_uploaded_file($tmp_file, $new_file);
		if($imageFileType!="htm" && $imageFileType!="jpg" && $imageFileType!="jpeg" && $imageFileType!="png" && $imageFileType!="pdf" && $imageFileType!="txt" && $imageFileType!="docx" && $imageFileType!="doc")
			echo "<script>alert('Sorry, File format not accepted.');</script>";
		else if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $new_file)) {
			$vari="The file ". basename( $_FILES["fileToUpload"]["name"]). " has not been uploaded.";
			echo "<script>alert('$vari');</script>";
		}
		else{
			echo "<script>alert('$target_file uploaded successfully.');window.history.go(-1);</script>";
		}
	}
	if(isset($_GET['urine'])){
		header("Location: urinereport.php");
	}
	if(isset($_GET['note'])){
		setcookie("note","labstock",time()+7200);
		header("Location: notification.php");
	}
	if(isset($_GET['stats'])){
		header("Location: labstats.php");
	}
	if(isset($_GET['stock'])){
		setcookie("note","labstock",time()+7200);
		header("Location: enterstocks.php");
	}
?>
<html>
	<head>
		<title>Laboratory</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCare Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="20">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div class="head"><br><h1 id="n"><?php echo $n;?></h1><hr></div>
		<div class="pharmatab">
			<form>
				<button name="newlab">Create New User</button>		
				<button name="stock">Stocks</button>
				<button name="note">Notifications</button>
				<button name="stats">Statistics</button>
				<div class="two">
					<div class="one">
						<button name="urine">Urine Examination</button>				
					</div>
				</div>
				<div class="two" style="float:right;">
					<button style="float:right;"><b>Log</b></button>
					<div class="one">
						<button name="changepass">Change Password</button>				
						<button name="logout">logout</button>
					</div>
				</div>
			</form>
		</div>
		<div style="width:45%;float:left;">
			<h1 class="head">Tests to be done</h1>
			<div id="table">
				<?php
					$query="select * from labrecord where completed = 0";
					$result=mysqli_query($conn,$query);
					$append="<form><table style=\"width:100%\" id=\"p_table\"><tr><th>ID</th><th>Name</th><th>Prescription</th><th>Done</th></tr>";
					while($row=mysqli_fetch_assoc($result)){
						$id=$row['Patient_ID'];
						$med=$row['Prescription'];
						$query="select Name from clientrecord where `Reg. No`='$id'";
						$res=mysqli_fetch_assoc(mysqli_query($conn,$query));
						$name=$res['Name'];
						$append=$append."<tr><td>$id</td><td>$name</td><td>".$med."</td><td><button name=\"done\" value=\"$id\">Done</button></td></tr>";
					}
					$append=$append."</table></form>";
					if(mysqli_num_rows($result)>0)
						echo $append;
					else echo "<h3>No pending tests left.</h3>";
					
				?>
			</div>
		</div>
		<div class="upload" >
			<form method="post" enctype="multipart/form-data">
				<h2 class="head">Upload Results:</h2>
				<input type="file" class="uploadbutton" style="width:60%;" name="fileToUpload" id="fileToUpload"><br><br>
				<input type="Number" name="pat" required>
				<select class="selectt" name="test">
					<option value="sugar">Sugar Test</option>
							<option value="blood">Blood Test</option>
							<option value="urine">Urine Examination</option>
							<option value="bio-chem">Bio-Chemistry</option>
							<option value="bio-chem1">Bio-Chemistry1</option>
							<option value="lipid">Lipid Profile</option>
							<option value="ggt">GGT</option>
							<option value="rft">RFT</option>
							<option value="lft">LFT</option>
							<option value="haemotology">Haematology</option>
							<option value="bwidal">Blood Widal</option>
							<option value="haemotology1">Haematology1</option>
							<option value="bio-chem2">Bio-Chemistry2</option>
							<option value="platelet">Blood Platelet Count</option>
							<option value="blood1">Blood1</option>
				</select><br><br>
				<input type="submit" value="Upload File" class="uploadbutton" name="submitfile">
			</form>
		</div>
	<body>
</html>
<?php
	mysqli_close($conn);
?>
