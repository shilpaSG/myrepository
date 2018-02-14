<?php 

$conn = new mysqli('localhost', 'root', '', 'test');
	

$userObj  = mysqli_query($conn , 'SELECT * FROM users');

if(isset($_POST['data'])){
	$dataArr = $_POST['data'] ; 

	foreach($dataArr as $id){
		mysqli_query($conn , "DELETE FROM users where id='$id'");
	}
	echo 'record deleted successfully';
}

?>