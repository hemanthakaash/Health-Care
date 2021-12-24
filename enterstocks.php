<?php
	if(!isset($_COOKIE['note']))	exit();
?>
<html>
	<head>
		<title>Stocks</title>
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
		<div style="width:50%;float:left;">
			<h2 align="center">Current Available Stocks</h2><hr>
			<form>
				<button class="recepbut" name="medicine">Medicine List</button><br><br>
			</form>
			<form><table id="p_table"></table></form>
		</div>
		<div style="width:33%;float:right;">
			<h2 align="center">Enter Details</h2>
			<div class="firstdiv" style="width:45%;">
				<label>Name :</label><br><br>
				<label>Type : </label><br><br>
				<label>Supplier :</label><br><br>
				<label>Quantity :</label><br><br>
				<label>Expiry Date: </label><br><br>
			</div>
			<div class="seconddiv" id ="stockenter"style="width:34%;float:right;">
				<form>
					<input type="text" class="new" name="tablet" required><br><br>
					<Select name="type" style="width:100%;">
						<option value="Tablet" selected="selected">Tablet/Capsule</option>
						<option value="Oinment">Oinment</option>
						<option value="Syrup">Syrup</option>
						<option value="Injection">Injection</option>
						<option value="Drops">Drops</option>
						<option value="Pharmaceutical">Pharmaceutical</option>
						<option value="Ohters">Others</option>
					</select><br><br>
					<input type="text" class="new" name="supplier" required><br><br>
					<input type="number" class="new" name="quantity" required><br><br>
					<input type="Date" class="new" name="expiry" required><br><br>
					<button type="submit" name="add" style="width:100%;"> ADD </button>
				</form>
			</div>
		</div>
		<script>
			var x = document.getElementsByClassName("new");
			var i;
			for(i=0;i<x.length;i++){
				x[i].value='';
			}
		</script>
	</body>
</html>
<?php
require_once 'connect.php';
$table=$_COOKIE['note'];
if($table=="stock")
	$medicine="medicine";
else if($table=="dressstock")
	$medicine="medicine1";
else if($table=="injectstock")
	$medicine="medicine2";
else if($table=="labstock")
	$medicine="medicine3";
	if(isset($_GET['add'])){
		$tablet=mysqli_real_escape_string($conn,$_GET['tablet']);
		$type=mysqli_real_escape_string($conn,$_GET['type']);
		$supplier=mysqli_real_escape_string($conn,$_GET['supplier']);
		$quantity=mysqli_real_escape_string($conn,$_GET['quantity']);
		$expiry=mysqli_real_escape_string($conn,$_GET['expiry']);
		$query="insert into $table values('$tablet','$type','$supplier','$quantity','$expiry')";
		mysqli_query($conn,$query);
		$query="select * from $medicine where Name='$tablet'";
		if(mysqli_num_rows(mysqli_query($conn,$query))<1){
			$query="insert into $medicine values('$tablet','$type')";
			mysqli_query($conn,$query);
		}
		echo "<script>
				window.history.go(-1); 
			</script>";
	}
$append="<tr><th>Name</th><th>Type</th><th>Supplier</th><th>Quantity</th><th>Expiry</th><th>Remove</th></tr>";
$query="select * from $table order by Name asc";
$result=mysqli_query($conn,$query);
while($row=mysqli_fetch_assoc($result)){
	$name=$row['Name'];
	$type=$row['Type'];
	$supplier=$row['Supplier'];
	$quantity=$row['Quantity Left'];
	$expiry=$row['Expiry Date'];
	$append=$append."<tr><td>$name</td><td>$type</td><td>$supplier</td><td>$quantity</td><td name=\"new\">$expiry</td><td><button class=\"th1\" name=\"remo\" value=\"$name:$expiry \">Remove</button></td></tr>";
}
echo "<script>document.getElementById('p_table').innerHTML='$append';</script>";
if(isset($_GET['remo'])){
	$a=$_GET['remo'];
	unset($_GET['remo']);
	$a=explode(":",$a);
	$query="delete from $table where Name='$a[0]' and `Expiry Date`='$a[1]'";
	mysqli_query($conn,$query);
	echo "<script>window.history.go(-1);</script>";
}
if(isset($_GET['medicine'])){
	$query="select * from $medicine";
	$result=mysqli_query($conn,$query);
	$append="<tr><th>Name</th><th>Type</th></tr>";
	while($row=mysqli_fetch_assoc($result)){
		$n=$row['Name'];
		$t=$row['Type'];
		$append=$append."<tr><td>$n</td><td>$t</td></tr>";
	}
	echo "<script>document.getElementById('p_table').innerHTML='$append';</script>";
}
?>  