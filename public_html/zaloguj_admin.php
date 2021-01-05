<?php
session_start();

if((!isset($_POST['login_ad'])) || (!isset($_POST['haslo_ad'])))
{
header('Location:admin_layout.php');
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
    	
    	exit();
}
else
{ 

    $login=$_POST['login_ad'];
	$haslo=$_POST['haslo_ad'];
 	
	$sql="SELECT* FROM admini WHERE Mail='$login' AND haslo='$haslo'";
	if($rezultat=$con->query($sql))
	{
	$ilu_us=$rezultat->num_rows;
        
		if($ilu_us>0)
		{
			$_SESSION['zalogowany_3']=true;
			$wiersz=$rezultat->fetch_assoc(); //wrzucenie kolumn wiersza do tablicy
			$_SESSION['id_ad']=$wiersz['ID'];
			$_SESSION['Name']=$wiersz['Imie'];

			unset($_SESSION['blad']);

			$rezultat->close();
			header('Location:admin.php');
			
		}
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:admin_layout.php');
		}
	}
	$con->close();

}
?>
