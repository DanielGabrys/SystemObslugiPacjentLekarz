<?php
session_start();

if((!isset($_POST['login_pacjent'])) || (!isset($_POST['haslo_pacjent'])))
{
header('Location:zaloguj_pacjent_layout.php');
exit();
}

require_once"conected.php"; //zalaczenie pliku z kodem
// Create connection
$con =new mysqli($servername, $username, $password, $database);

// Check connection
if($con->connect_errno!=0)
{
	echo "Error: Unable to connect to MySQL. <br>" . PHP_EOL;
    	echo "Debugging errno: " . mysqli_connect_errno() ."<br>". PHP_EOL;
    	//echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	//echo $con->connect_errno;
    	exit;
}
else
{ 
	//echo "Connected successfully";
        $login=$_POST['login_pacjent'];
	$haslo=$_POST['haslo_pacjent'];
	
	$login=htmlentities($login,ENT_QUOTES,"UTF-8"); //ochrona przed wstrzykiwaniem sql
 	
	$sql="SELECT* FROM customers WHERE Mail='%s'";
	if($rezultat=$con->query(sprintf($sql,mysqli_real_escape_string($con,$login))))
	{
	$ilu_us=$rezultat->num_rows;
        
		if($ilu_us>0)
		{

			$wiersz=$rezultat->fetch_assoc(); //wrzucenie kolumn wiersza do tablicy
				
			if(password_verify($haslo,$wiersz['Password']))
			{
				$_SESSION['zalogowany']=true;
				$_SESSION['id']=$wiersz['Id'];
				$_SESSION['Name']=$wiersz['Name']; //usuwamy z sesji

				unset($_SESSION['blad']);

				$rezultat->close();
				header('Location:main_pacjent.php');
			}
			else
			{
				$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
				header('Location:zaloguj_pacjent_layout.php');
			}
			
		}
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:zaloguj_pacjent_layout.php');
		}
	}
	$con->close();
//echo "<br>";

	//$sql = "SELECT ID, Name, Age,Password,Mail FROM customers";
	//$result = mysqli_query($con, $sql);

	//if (mysqli_num_rows($result) > 0)
 	//{
  	//	// output data of each row
  	//	while($row = mysqli_fetch_assoc($result))
	//	{
   	 //		echo "ID: " . $row["ID"]. " - Name: " . $row["Name"]. " " . $row["Age"]. "<br>";
  	//	}
	//} 
	//else 
	//{
  	//echo "0 results";
	//}

//mysqli_close($con);
}
?>
