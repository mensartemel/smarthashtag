<?php
$number = count($_POST["keyword"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["keyword"][$i] != ''))
		{
			// $sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";
			echo $_POST["keyword"][$i]." ". $_POST["profilesearch"][$i] ."</br>";
		}
	}
}
else
{
	echo "Please Enter Keyword";
}
