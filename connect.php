<?php
$host="localhost";
$user="root";
$pwd="";
$db="newhealthcentre";
$conn=mysqli_connect($host,$user,$pwd,$db) or die("ERROR :".mysqli_connect_error());
if(!$conn){
	echo '<script>
			alert("Some error has occured in connecting with the database./n Please try after some time");
			</script>';
	}	
?>
