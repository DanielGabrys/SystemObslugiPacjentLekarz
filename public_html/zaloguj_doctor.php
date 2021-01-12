<?php
session_start();

if((!isset($_POST['login_doctor'])) || (!isset($_POST['haslo_doctor'])))
{
header('Location:zaloguj_doctor_layout.php');
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
//	echo "Connected successfully";
        $login=$_POST['login_doctor'];
	$haslo=$_POST['haslo_doctor'];
 	
	$sql="SELECT* FROM lekarze WHERE Mail='$login' AND haslo='$haslo'";
	if($rezultat=$con->query($sql))
	{
	$ilu_us=$rezultat->num_rows;
        
		if($ilu_us>0)
		{
			$_SESSION['zalogowany2']=true;
			$wiersz=$rezultat->fetch_assoc(); //wrzucenie kolumn wiersza do tablicy
			$_SESSION['Id']=$wiersz['ID'];
			$_SESSION['Name']=$wiersz['Name'];
			$_SESSION['Specjalizacja']=$wiersz['Specialization'];

			unset($_SESSION['blad']);

			$rezultat->close();
			header('Location:main_dc.php');
			
		}
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:zaloguj_doctor_layout.php');
		}
	}
	$con->close();

}
?>
