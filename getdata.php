<?php 

$conn = new mysqli('localhost', 'root', '', 'test');

$name = $_POST['name'];
$age = $_POST['age'];
$email = $_POST['email'];

$query = "INSERT INTO users VALUES(NULL, '$name', '$age', '$email')";

//code to execute query
	if ($conn -> query($query)) {
		echo "Succesfully Inserted record";
	}else{
		echo "error occured" . mysql_error($con);
	}

 ?>