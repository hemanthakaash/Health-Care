<html>
<head>
	<title>New User</title>
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
		<div class="disco" style="width:300px"><h1 align="center"><p>Enter Details</p></h1></div><hr>
		<div class="firstdiv">
			<label> Name :</label><br><br><br>
			<label> User-Id :</label><br><br>
			<label> Passowrd :</label><br><br><br>
			<label> Email-Id :</label><br><br>
		</div>
		<form action="createnew.php" method="post">
			<div class="seconddiv">
				<input type="text" name="name" placeholder="Name" required /><br><br><br>
				<input type="text" name="id" placeholder="User ID" required /><br><br>
				<input type="password" name="pass" placeholder="Password" required /><br><br><br>
				<input type="text" name="email" placeholder="Email-Id" required /><br><br>
			</div>
			<div style="height:30px;width:20%;clear:both;">
				<button type="submit" style="margin:auto;float:right;">submit</button>
			</div>	
		</form>
	</body>
</html>