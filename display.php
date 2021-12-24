<!DOCTYPE html>
<?php
	if(!isset($_COOKIE['duid']))
		exit();
	require_once 'connect.php';
	$reg=$_GET['reg'];
	$query="select Name,`D.O.B`,`Blood Group`,`Specific medical details` from clientrecord where `REG. NO`='$reg'";
	$result=mysqli_query($conn,$query);
	$row=mysqli_fetch_assoc($result);
	$n=$row['Name'];
	$a=$row['D.O.B'];
	$diff = date_diff(date_create($a), date_create(date("Y-m-d")));
	$a=$diff->format('%y');
	$b=$row['Blood Group'];
	$m=$row['Specific medical details'];
?> 
<html>
	<head>
		<title>Display</title>
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
		</style>
		<script src="jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<div><h2 style="color:lime">Patient Details</h2></div><hr>
		<script>
			function testrest(){
				var reg=<?php echo $reg;?>;
				var file=reg+document.getElementById('tests').value+".pdf";
				window.location.href="Test_Reports/"+file;
			}
		</script>
		<div>
			<div style="width:35%;float:left;">
				<div style="">
					<div style="width:50%;float:left;"><label > Name:</label></div>
					<div style="width:50%;float:left;"><label id="name"><?php echo $n; ?></label></div>
				</div><br><br>
				<div style="">
					<div style="width:50%;float:left;"><label > Reg. No:</label></div>
					<div style="width:50%;float:left;"><label id="reg"><?php echo $reg; ?></label></div>
					
				</div><br><br>
				<div style="">
					<div style="width:50%;float:left;"><label > Age:</label></div>
					<div style="width:50%;float:left;"><label id="age"><?php echo $a; ?></label></div>
				</div><br><br>
				<div style="">
					<div style="width:50%;float:left;"><label > Blood Group:</label></div>
					<div style="width:50%;float:left;"><label id="bg"><?php echo $b; ?></label></div>
				</div><br><br>
				<div style="">
					<div style="width:50%;float:left;"><label > Medical Specifications:</label></div>
					<div style="width:50%;float:left;"><label id="medical"><?php echo $m; ?></label></div>
				</div><br><br><br>
				<div style="">
					<div style="width:50%;float:left;">
						<select class="selectt" id="tests" style="width:90%;" name="tests">
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
						</select>
					</div>
					<div style="width:50%;float:left;">
						<button name="view" style="height:35px" onclick="testrest()">View</button>
					</div><br><br>
				</div>	<br><br>
			</div>
			<div style="float:left;width:65%;hn " id="tabler" class="disco">
				<?php
					$query="select * from patientrecord where `REG.No`='$reg' order by `Date` desc"; 
					$result=mysqli_query($conn,$query);
					if(!$result){
				?>
					<p>NO Records Found</p>
				<?php
					}
					else{
						$append="<table><tr><th>Date</th><th>Consulted</th><th>Reason</th><th>Prescribed Medicine</th><th>Remarks</th></tr>";
						while($row=mysqli_fetch_assoc($result)){
							$date=$row['Date'];
							$consulted=$row['Consulted'];
							$reason=$row['Reason'];
							$prescription=$row['Prescription'];
							$remark=$row['Remarks'];
							$append=$append."<tr><td>$date</td><td>$consulted</td><td>$reason</td><td>$prescription</td><td>$remark</td></tr>";
						}
						$append=$append."</table>"; 
						echo $append;
					}
				?>
			</div><br><br>
		</div>
		<div style="clear:both;"><hr>
			<form method="post">
				<div>
					<h3 style="color:lime;font-size:22px;">Remarks:</h3>
					<textarea name="remark" style="width:25%;height:50px"></textarea>
				</div>
				<div style="width:45%;float:left;" id="prescribe">
					<h3 style="color:lime;font-size:22px;">Prescribed Medicines:</h3>
					<!--select class="selectt" name="med1"-->
					<?php
						$app="";
							$query="select Name from medicine union select Name from medicine1 UNION select Name from medicine2 union select Name from medicine3 order by Name";
							$result=mysqli_query($conn,$query);
							while($row=mysqli_fetch_assoc($result)){
								$n=$row['Name'];
								$app=$app."<option value=\"$n\">$n</option>";
							}
						//echo $app;
					?><!--/select>&nbsp
					<input type="number" name="count1"/--><br>
					<p id="abd" style="font-family: cursive;">Add</p>
					<input type="hidden" name="cnt1" id="cnt1">
					<script>
						var str1,cnt=0;
						document.getElementById('cnt1').value=0;
						$(document).ready(function(){
							$("#abd").click(function(){
								cnt++;	
str1='<br><select class="selectt" name="med'+cnt+'"><?php echo $app  ?></select>&nbsp&nbsp<input type="number" name="count'+cnt+'" required/><br>';
								$(this).before(str1);
								document.getElementById('cnt1').value=cnt;
							});
							document.getElementById('cnt1').value=cnt;
						});
					</script>
				</div>
				<div  style="float:right;width:55%;" id="labtests">
					<h3 style="color:lime;font-size:22px;">Laboratory Tests:</h3>
					<textarea name="labtests" style="width:90%;height:100px;"></textarea>
					<input type="hidden" name="reg1" id="reg1"/>
				</div><br>
				<div style="width:40%;">
					<button name="submit" style="clear:both;float:right;">Submit</button>
				</div><br><br>
				<script>
					document.getElementById('reg1').value=<?php echo $reg;?> ;
				</script>
			</form>
		</div>
		<?php	
			if(isset($_POST['submit'])){
				$reg=$_POST['reg1'];
				$cnt1=$_POST['cnt1'];
				$insert="";
				$remark=$_POST['remark'];
				for($i=1;$i<=$cnt1;$i++){
					$name="med".$i;
					$count="count".$i;
					$name=mysqli_real_escape_string($conn,$_POST[$name]);
					$count=mysqli_real_escape_string($conn,$_POST[$count]);
					$insert=$insert."$name : $count;";
				}
				$query="insert into distribute values('$reg','$insert')";
				if($insert!="")
				if(!mysqli_query($conn,$query))
					header("Location: errorpage.php");
				$query="update patientrecord set Remarks='$remark' , Prescription='$insert' where `REG.No`='$reg' order by Date desc,Time desc Limit 1 ";
				$lab=mysqli_real_escape_string($conn,$_POST['labtests']);
				$lab=str_replace("\\r"," ;",$lab);
				$lab=str_replace("\\n","  ",$lab);
				$date=date("Y-m-d");
				$time=date("h:i:s");
				if($lab!="")
				$query="insert into labrecord values('$date','$time','".$reg."','$lab',0)";
				if(!mysqli_query($conn,$query))
					header("Location: errorpage.php");
				$doc=$_COOKIE['duid'];
				$query="delete from patients where Concerned_Doctor='$doc' and Patient_ID='$reg'";
				if(!mysqli_query($conn,$query)){
		?>
		<script>window.location.href="errorpage.php";</script>
		<?php
				}
				echo "<script>window.history.go(-2);</script>";
			}
		?>
	</body>
</html>
