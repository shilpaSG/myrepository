<?php
$conn = new mysqli('localhost', 'root', '', 'test');

if(isset($_POST['email'])){
	
	$name = $_POST['name'];
	$age = $_POST['age'];
	$email = $_POST['email'];
	$id = $_POST['id'];

	//  query to update data 
	 
	$result  = mysqli_query($conn , "UPDATE users SET name='$name' , age='$age' , email = '$email' WHERE id='$id'");
	if($result){
		echo 'data updated';
	}

}
?>