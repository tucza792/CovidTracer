<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
        <head>
	        <link rel="stylesheet" href="styles.css">
		<title>Covid-19 Tracer</title>
	</head>
	
	<body>
	<div class="interface">
		<h1>Covid-19 Tracer</h1>

		<h3>Input visit details:</h3>

		<?php
		 # RDS Database information
		 $db_host = 'covid-tracer-db-instance.c3v1xy5xbrif.us-east-1.rds.amazonaws.com';
		 $db_port = '3306';
		 $db_name = 'sample';
		 $db_user = 'admin';
		 $db_passwd = 'a1b2c3d4';

		 $pdo_dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";
		 $pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
		 
		 # Check if contact table already exists, if not then create it.
         $q = $pdo->query("DESCRIBE contact");
         if (empty($q)) {
         	$q = $pdo->query("CREATE TABLE contact (fname VARCHAR(20) NOT NULL, lname VARCHAR(20) NOT NULL,email VARCHAR(50) PRIMARY KEY,time_of_visit DATETIME NOT NULL)");
         }

		// If user submits contact information, send it to the dbserver
		if(isset($_POST['add'])) {
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email'];
			$time_of_visit = date("Y-m-d H:i:s",strtotime($_POST['time_of_visit']));
  
			$q = $pdo->query("INSERT INTO contact VALUES ('$fname','$lname','$email','$time_of_visit')");
			$q->fetch();
			
			// If user presses 'Alert Contacts' button, trigger the REST API on alertserver to send emails out
		  } else if(isset($_POST['alertbtn'])) {
			$xml = file_get_contents('http://10.0.0.97:5000/sendEmail');
			// If user presses 'Clear Contacts' button, delete all rows from the contact table
		  }  else if (isset($_POST['clearbtn'])) {
			$q = $pdo->query("DELETE FROM contact");
			$q->fetch();
	  	  }
		?>

		<!-- Contact details form -->
		<form method = "post" class="form" action = "<?php $_PHP_SELF ?>">
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
				<td><input name = "time_of_visit" type = "datetime-local" id = "time_of_visit"></td>
			</tr>
			<tr>
				<td width = "100"> </td>
				<td> </td>
			</tr>

		  	<!-- Button to submit contact details -->
			<tr>
				<td width = "100"> </td>
				<td>
				<input name = "add" type = "submit" id = "add" value = "Add Visit">
				</td>
			</tr>
			<tr>
				<td width = "100"> </td>
				<td> </td>
			</tr>
			<tr>
                <td width = "100"> </td>
                <td> </td>
            </tr>
			</table>

			
			<div class="alertbutton">
				<!-- Button to alert contacts if user tests positive for Covid-19 -->
                <input style="background-color: red;" type="submit" name="alertbtn" value="ALERT CONTACTS" />
				<!-- Button to delete all contacts in the table -->
                <input style="background-color: red;" type="submit" name="clearbtn" value="CLEAR CONTACTS" />
            </div>
        </form>

		
		<!-- Table displaying details of all recent contacts -->
		<table border="2">
		<tr><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Date and Time of Visit</th></tr>

		  <?php
		  $q = $pdo->query("SELECT * FROM contact");

		  while($row = $q->fetch()) {
			  echo "<tr><td>".$row["fname"]."</td><td>".$row["lname"]."</td><td>".$row["email"]."</td><td>".$row["time_of_visit"]."</td></tr>\n";
		  }
		  ?>
		</table>
	</div>
	</body>

</html>
