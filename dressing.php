<?php
	if(!isset($_COOKIE['druid']))	exit();
	setcookie("note","dress",time()-30);
	require_once 'connect.php';
	if(isset($_GET['logout'])){
		setcookie("druid","",time()-3600);
		echo "<script>window.history.go(-2);</script>";
	}
	if(isset($_GET['changepass'])){
		setcookie("cp","dressing",time()+(18*3600));
		header("Location: changepass.php");
	}
	if(isset($_GET['new_user'])){
		setcookie("page","dressing",time()+(18*3600));
		echo "<script>document.location='getnewuser.html';</script>";
	}
	if(isset($_GET['stock'])){
		setcookie("note","dressstock",time()+7200);
		header("Location: enterstocks.php");
	}
	if(isset($_GET['note'])){
		setcookie("note","dressstock",time()+7200);
		header("Location: notification.php");
	}
	if(isset($_GET['stats'])){
		setcookie("stats","dress",time()+3600);
		header("Location: dressstat.php");
	}
	if(isset($_GET['update'])){
		$q=$_GET['quantity'];
		$m=$_GET['changestock'];
		$query="select sum(`Quantity Left`) from dressstock where Name='$m'";
		$result=mysqli_query($conn,$query);
		$row=mysqli_fetch_assoc($result);
		$left=$row['sum(`Quantity Left`)'];
		if($left<$q){
			echo "<script>alert(\"$m not enough stock is present.\");
				</script>";
		}
		else{
			$query="select count(Name) from dressstock where Name='$m'";
			$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
			$line=$row['count(Name)'];
			$quanti=$q;
			for($j=0;$j<$line;$j++){
				$query="select `Quantity Left` from dressstock where Name='$m' order by `Expiry Date` asc limit 1";
				$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
				$quan=$row['Quantity Left'];
				if($quan<=$q){
					$query="delete from dressstock where Name='$m' order by `Expiry Date` asc limit 1";
					mysqli_query($conn,$query);
					$q-=$quan;
					if($q==0)
						break;
				}
				else{
					$query="update dressstock set `Quantity Left`=`Quantity Left`-$q where Name='$m' limit 1";
					mysqli_query($conn,$query);
					break;
				}
				
			}
		}
		echo "<script>window.history.go(-1);</script>";
	}
	if(isset($_GET['dressdone'])){
		$id=$_GET['pid'];
		$query="select Name from clientrecord where `REG. NO` = $id";
		if(mysqli_num_rows(mysqli_query($conn,$query))<1){
			echo "<script>alert('User Not found');
					window.history.go(-1);
				</script>";
			exit();
		}
		$proc=$_GET['proced'];
		$proc=str_replace("\\r"," ",$proc);
		$proc=str_replace("\\n"," ",$proc);
		date_default_timezone_set('Asia/Kolkata');
		$date=date("Y-m-d");
		$time=date("h:i:s");
		$query="insert into dressrec values('$date','$time','$id','$proc')";
		mysqli_query($conn,$query);
		echo "<script>window.history.go(-1);</script>";
	}
?>
<html> 
	<head>
		<title>Dressing</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="HealthCentre Database Management">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="description" content="Medical Transactions">	
		<meta http-equiv="refresh" content="3600">
		<link rel="stylesheet" href="style.css"/>
		<link href="title.png" rel="icon" type="image/png" />
	</head>
	<body>
		<div class="head"><h1 id="f">
		<?php
			$query="select Name from dressing where UserID=".$_COOKIE['druid'];
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
				<h2 align="center" style="color:lime">Dressing</h2>
				<form method="GET">
					<label class="l1" for="pid">Patient-Id:</label><br>
					&nbsp&nbsp&nbsp&nbsp <input type="text" placeholder="Patient-Id" id="pid" name="pid" required /><br><br>
					<label class="l1" for="proced">Procedure:</label><br>
					&nbsp&nbsp&nbsp&nbsp
					<textarea id="proced" name="proced" placeholder="Procedure"></textarea><br><br>
					<div align="center"><button style="width:100px;height:35px;" name="dressdone">Done</button></div> 
				</form><br><br>
				<form>
					<label for="changestock" class="l1"/>Pharmaceutical:-</label><br><br>
					<select id="changestock" class="selectt" name="changestock">
					<?php
						$query="select Name from medicine1";
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
			<div style="width:50%;float:left;">
				<h2 align="center" style="color:lime">Ambulance</h2>
			</div>
		</div>
	</body>
</html>
<?php
	
?>