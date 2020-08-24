<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
	<head>
		<title>Covid-19 Tracer</title>
	</head>

	<body>
		<h1>Covid-19 Tracer</h1>

		<h2>Input visit details</h2>

		<?php

		 $db_host = '192.168.2.12';
		 $db_name = 'fvision';
		 $db_user = 'webuser';
		 $db_passwd = 'insecure_db_pw';

		 $pdo_dsn = "mysql:host=$db_host;dbname=$db_name";
		 $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
		 

		if(isset($_POST['add'])) {
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email'];
			$time_of_visit = $_POST['time_of_visit'];
  
			$q = $pdo->query("INSERT INTO contact VALUES ('$fname','$lname','$email','$time_of_visit')");
			$q->fetch();
			  
		  }
		?>

		<form method = "post" action = "<?php $_PHP_SELF ?>">
			<table width = "400" border = "0" cellspacing = "1" cellpadding = "2">
			<tr>
				<td width = "100">First Name</td>
				<td><input name = "fname" type = "text" id = "fname"></td>
			</tr>
			<tr>
				<td width = "100">Last Name</td>
				<td><input name = "lname" type = "text" id = "lname"></td>
			</tr>
			<tr>
				<td width = "100">Email Address</td>
				<td><input name = "email" type = "text" id = "email"></td>
			</tr>
			<tr>
				<td width = "100">Date and Time of Visit</td>
				<td><input name = "time_of_visit" type = "text" id = "time_of_visit"></td>
			</tr>
			<tr>
				<td width = "100"> </td>
				<td> </td>
			</tr>

			<tr>
				<td width = "100"> </td>
				<td>
				<input name = "add" type = "submit" id = "add" value = "Add Visit">
				</td>
			</tr>
			</table>
        </form>

		<p>Showing contents of contact table:</p>

		<table border="2">
		<tr><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Date and Time of Visit</th></tr>

		  <?php
		  $q = $pdo->query("SELECT * FROM contact");

		  while($row = $q->fetch()) {
			  echo "<tr><td>".$row["fname"]."</td><td>".$row["lname"]."</td><td>".$row["email"]."</td><td>".$row["time_of_visit"]."</td></tr>\n";
		  }
		  ?>
		</table>
	</body>

</html>
