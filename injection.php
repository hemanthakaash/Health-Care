<?php
	if(!isset($_COOKIE['inuid']))	exit();
	setcookie("note","inject",time()-30);
	require_once 'connect.php';
	if(isset($_GET['logout'])){
		setcookie("inuid","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","injection",time()+(18*3600));
		header("Location: changepass.php");
	}
	if(isset($_GET['new_user'])){
		setcookie("page","injection",time()+(18*3600));
		echo "<script>document.location='getnewuser.html';</script>";
	}
	if(isset($_GET['stock'])){
		setcookie("note","injectstock",time()+7200);
		header("Location: enterstocks.php");
	}
	if(isset($_GET['note'])){
		setcookie("note","injectstock",time()+7200);
		header("Location: notification.php");
	}
	if(isset($_GET['stats'])){
		header("Location: injectstats.php");
	}
	if(isset($_GET['injectdone'])){
		$id=$_GET['pid'];
		$query="select Name from clientrecord where `REG. NO` = $id";
		if(mysqli_num_rows(mysqli_query($conn,$query))<1){
			echo "<script>alert('User Not found');
					window.history.go(-1);
				</script>";
			exit();
		}
		$inject=$_GET['inj'];
		$inject=str_replace("\\r"," ",$inject);
		$inject=str_replace("\\n"," ",$inject);
		date_default_timezone_set('Asia/Kolkata');
		$date=date("Y-m-d");
		$time=date("h:i:s");
		$query="insert into injectrec values('$date','$time','$id','$inject')";
		mysqli_query($conn,$query);
		if(isset($_GET['inp']) && $_GET['inp']=='yes'){
			$query="update patientrecord set type=0 where `REG.No`='$id' order by Date desc,time desc limit 1";
			mysqli_query($conn,$query);
		}
		echo "<script>window.history.go(-1);</script>";
	}
	if(isset($_GET['ecgdone'])){
		$id=$_GET['pid'];
		$query="select Name from clientrecord where `REG. NO` = $id";
		if(mysqli_num_rows(mysqli_query($conn,$query))<1){
			echo "<script>alert('User Not found');
					window.history.go(-1);
				</script>";
			exit();
		}
		$treat=$_GET['treat'];
		date_default_timezone_set('Asia/Kolkata');
		$date=date("Y-m-d");
		$time=date("h:i:s");
		$query="insert into $treat values('$date','$time','$id')";
		mysqli_query($conn,$query);
		echo "<script>window.history.go(-1);</script>";
	}
	if(isset($_GET['update'])){
		$q=$_GET['quantity'];
		$m=$_GET['changestock'];
		$query="select sum(`Quantity Left`) from injectstock where Name='$m'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_assoc($result);
		$left=$row['sum(`Quantity Left`)'];
		if($left<$q){
			echo "<script>alert(\"$m not enough stock is present.\");
				</script>";
		}
		else{
			$query="select count(Name) from injectstock where Name='$m'";
			$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$line=$row['count(Name)'];
			$quanti=$q;
			for($j=0;$j<$line;$j++){
				$query="select `Quantity Left` from injectstock where Name='$m' order by `Expiry Date` asc limit 1";
				$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
				$quan=$row['Quantity Left'];
				if($quan<=$q){
					$query="delete from injectstock where Name='$m' order by `Expiry Date` asc limit 1";
					mysqli_query($conn,$query);
					$q-=$quan;
					if($q==0)
						break;
				}
				else{
					$query="update injectstock set `Quantity Left`=`Quantity Left`-$q where Name='$m' limit 1";
					mysqli_query($conn,$query);
					break;
				}
				
			}
		}
		echo "<script>window.history.go(-1);</script>";
	}
?>
<html> 
	<head>
		<title>Injection</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="30">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div class="head"><h1 id="f">
		<?php
			$query="select Name from injection where UserID=".$_COOKIE['inuid'];
			$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$append="Welcome ".$row['Name'];
			echo $append;
		?>
		</h1></div><hr>
		<div class="tab">
			<form>
				<button class="recepbut" name="new_user">create new User</button>
				<button class="recepbut"name="stock">Stocks</button>
				<button class="recepbut" name="note">Notifications</button>
				<button class="recepbut" name="stats">Statistics</button>
				<div class="two" style="float:right;">
					<button class="recepbut"><b>Log</b></button>
					<div class="one">
						<button class="recepbut" name="changepass">Change Password</button>				
						<button class="recepbut" name="logout">logout</button>
					</div>
				</div>
			</form>
		</div>
		<div>
			<div style="width:30%;float:left;">
				<h2 align="center" style="color:lime">Injection</h2>
				<form method="GET">
					<label class="l1" for="pid">Patient-Id:</label><br>
					<input type="text" placeholder="Patient-Id" id="pid" name="pid" style="margin-left:30px;"required /><br>
					<span style="margin-left:30px;"><label><input style="width:10px;" name="inp" value="yes" type="checkbox" >In-Patient</label><br><br></span>
					<label class="l1" for="inj">Injection:</label><br>
					<textarea id="inj" name="inj" style="margin-left:30px;" placeholder="Prescribed-Injection"></textarea><br><br>
					<div align="center"><button style="width:100px;height:35px;" name="injectdone">Done</button></div> 
				</form><br><br>
				<form>
					<label for="changestock" class="l1"/>Pharmaceutical:-</label><br><br>
					<select id="changestock" class="selectt" name="changestock">
						<?php
							$query="select Name from injectstock";
							$result=mysqli_query($conn,$query);
							$append="";
							while($row=mysqli_fetch_assoc($result)){
								$name=$row['Name'];
								$append=$append."<option value='$name'>$name</option>";
							}
							echo $append;
						?>
					</select><br><br>
					<label for="quantity" class="l1"/>Quantity-Taken:-</label><br><br>
					&nbsp&nbsp&nbsp&nbsp <input type="Number" placeholder="Quantity-Used" id="quantity" name="quantity" required /><br><br>
					<div align="center"><button style="width:100px;height:35px;" name="update">Update</button></div> 
				</form>
			</div>
			<div style="width:30%;float:left;" align="">
				<h2 align="right" style="color:lime">Others:</h2>
				<form method="GET" style="float:right;">
					<select class="selectt" name="treat">
						<option value="ecg">ECG</option>
					</select><br><br>
					<label class="l1" for="pid">Patient-Id:</label><br>
					&nbsp&nbsp&nbsp&nbsp <input type="text" placeholder="Patient-Id" id="pid" name="pid" required /><br><br>
					<div align="center"><button style="width:100px;height:35px;" name="ecgdone">Done</button></div> 
				</form>
			</div>
		</div>
	</body>
</html>
<?php
	
?>