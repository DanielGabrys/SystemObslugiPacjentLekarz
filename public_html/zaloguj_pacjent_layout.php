<?php
	session_start();
	if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
	header('Location:main_pacjent.php');
	exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>E-Porady</title>
</head>

<body>


<h2>Jestem pasjentem </h2>
<form action="zaloguj_pacjent.php" method="post">
	
	Login: <br/> <input type="text" name="login_pacjent"/> <br/>
 	Hasło: <br/> <input type="password" name="haslo_pacjent"/> <br/> <br/>
        <input type="submit" value= "Zaloguj sie jako pacjent"/>
  	<br/><br/>
	<a href="rejestracja.php"> <button type="button">Nie mam jeszcze konta</button> </a>
</form>


<?php
if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>





</body>
</html> 
