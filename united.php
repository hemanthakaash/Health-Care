<?php
	$query="select * from patientrecord where `REG.No`='$reg' order by `Date` desc"; 
	$result=mysqli_query($conn,$query);
	if(!$result){
		echo "<script>
				document.getElementById('tabler').innerHTML='<p>NO Records Found</p>';
			</script>";
	}
	else{
		$append="<table><tr><th>Date</th><th>Consulted</th><th>Reason</th><th>Prescribed Medicine</th></tr>";
		while($row=mysqli_fetch_assoc($result)){
			$date=$row['Date'];
			$consulted=$row['Consulted'];
			$reason=$row['Reason'];
			$prescription=$row['Prescription'];
			$append=$append."<tr><td>$date</td><td>$consulted</td><td>$reason</td><td>$prescription</td></tr>";
		}
		$append=$append."</table>"; 
		echo "<script>
				document.getElementById('tabler').innerHTML=\"$append\";
			</script>";
	}
if(isset($_GET['submit'])){
	$med=mysqli_real_escape_string($conn,$_GET['prescription']);
	$lab=mysqli_real_escape_string($conn,$_GET['labtests']);
	$med=str_replace(" "," ",$med);
	$med=str_replace("\\r"," ;",$med);
	$med=str_replace("\\n","  ",$med);
	$lab=str_replace("\\r"," ;",$lab);
	$lab=str_replace("\\n","  ",$lab);
	$doc=$_COOKIE['duid'];
	$reg=$_COOKIE['reg'];
	$query="select Status from clientrecord where `REG. NO`='$reg'";
	$row=mysqli_fetch_assoc(mysqli_query($conn,$query));
	$status=$row['Status'];
	$query="update patientrecord set `Prescription`='$med' where `consulted`='$doc' and `REG.No`='$reg' order by Date desc,Time desc limit 1";
	mysqli_query($conn,$query);
	$query="delete from patients whe
}
?>