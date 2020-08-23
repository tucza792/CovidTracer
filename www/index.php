<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
	<head>
		<title>Covid-19 Tracer</title>
	</head>

	<body>
		<h1>Covid-19 Tracer</h1>

		<h2>Input visit details</h2>

		<p>Showing contents of contact table:</p>

		<table border="2">
		<tr><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Date and Time of Visit</th></tr>

		<?php

		 $db_host = '192.168.2.12';
		 $db_name = 'fvision';
		 $db_user = 'webuser';
		 $db_passwd = 'insecure_db_pw';

		 $pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

		 $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

		 $q = $pdo->query("SELECT * FROM contact");

		while($row = $q->fetch()) {
		    echo "<tr><td>".$row["fname"]."</td><td>".$row["lname"]."</td><td>".$row["email"]."</td><td>".$row["time_of_visit"]."</td></tr>\n";
		}

		?>
		</table>
	</body>

</html>
