<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
header('location:index.php');
}
unset($_SESSION['Spec']);
unset($_SESSION['d2']);
unset($_SESSION['lek_rem']);
unset($_SESSION['lek_rem2']);
unset($_SESSION['lekarz_imie']);
unset($_SESSION['lekarz_id']);
unset($_SESSION['nowy_mail']);



/////////sprawdzamy czy sa nieprzeczytane wiadomosci

require_once "conected.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$con= new mysqli($servername,$username,$password,$database);
		if($con->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}

		else
		{
     

	    $id=$_SESSION['id'];
		$rez=$con->query("SELECT *from powiadomienia_pacjent where pacjent_id='$id'AND status='nieprzeczytane' ");
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
		
        }
			$con->close();

	}
		
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}
	
/////////
?>

<!DOCTYPE html>
<html>
<head>
	<title>System ubslugi pacjenta</title>
</head>

<body>
<?php
unset($_SESSION['lekarze']);  //zmienna z umow
?>

<div id="imie">
    <div id="text">
    <?php
        echo "</br> Witaj ".$_SESSION['Name'];
        echo "<br/> <br/>";
    ?>
    </div>
</div>


<a href="powiadomienia_pacjent.php"> <button  id="block">Powiadomienia
<?php
if($ile>0)
    echo "(".$ile." nowe)";
?>
</button> </a>  
<a href="kalendarz.php"> <button id="block2">kalendarz</button> </a> 
<a href="historia_chorob.php"> <button id="block2">Historia chorob</button> </a> 
<a href="aktualne_leki.php"> <button id="block2">Aktualne leki</button> </a> 
<a href="umow.php"> <button id="block2">Umow wizyte!</button> </a>
<a href="logout.php"> <button id="block3">Wyloguj!</button> </a> </br> 

 
</body>
</html> 

<style>

A {text-decoration: none;}

#block, #block2, #block3
{
    display: block;
    border: none;
    color: white;
    padding: 14px 28px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    float:left;
}

#block, #block2
{
    width: 18%; 
}

#block3
{
    width: 10%; 
}

#block
{
    <?php
    if($ile!=0)
        {?> 
        background-color: #4CAF50;
        <?php }
    else
        {?>
        background-color: grey; 
        <?php }
    ?>
}

#block2, #block3
{
  background-color: grey;
}

#block:hover,#block2:hover,#block3:hover
{
  background-color: #ddd;
  color: black;
}

#imie
{
    background-color:#ddd;
    
}

#text
{
    margin-left:10px;
}

</style