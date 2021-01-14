<?php
session_start();

$tab;
$tab2;
$tab3;
$_SESSION['lekarze'][]="";
$_SESSION['dupa']="abc";
$flaga=0;
////funkcje
function tablica(&$tablica,$rezultat,$pom) //zapelniamy tablece danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			//if($pom==1)   
			    //$_SESSION['lekarze'][$licz]=$tablica[$licz];
			$licz++;
			  
		}  
}

////funkcje


if(!isset($_SESSION['zalogowany']))
{
header('location:index.php');
}


$id=$_SESSION['id'];
if(isset($_POST['d1']))
{
    $spec=$_POST['lekarz_s'];
    //echo $spec."</br>";
}

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
        
     
		// wszystkie specjalizacje (nie powinny sie powtarzac np nie moze byc2 chirurgow)
		$rez=$con->query("SELECT DISTINCT Specialization FROM lekarze");
		if(!$rez) throw new Exception($con->error);
		
		// imiona lekarzy (jeden lekarz ma tylko jedna specjalizacje)
		$rez2=$con->query("SELECT * FROM lekarze ORDER BY Name");
		if(!$rez2) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
		$ile2=$rez2->num_rows;
		
     
	    tablica($tab,$rez,$flaga); 
        tablica($tab2,$rez2,$flaga);
        
			$con->close();
		}
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
<a href="main_pacjent.php"> <button > Powrót </button> </br> </br> </a>
<body>
    
<form action="kalendarz2.php" method="post">
    
  
  WYBIERZ SPECJALIZACJE LEKARZA: </br>
  <td> 
  <select name="lekarz_s"> 
  <?php
    for($i=0;$i<$ile;$i++)
    { ?>
     <option value ="<?php echo $tab[$i]['Specialization']?>">
     <?php  echo $tab[$i]['Specialization'];
    
    }  
    ?>
    </option>  
     
	</select>
  </td> 
  <input type="submit" name="d1" value="DALEJ">
  </br></br>
</form>

<form action="kalendarz2.php" method="post">  
  
  WYBIERZ LEKARZA: </br>
    <td> 
  <select name="lekarz_n"> 
  <?php
    for($i=0;$i<$ile2;$i++)
    { ?>
     <option value ="<?php echo $tab2[$i]['Name']?>">
     <?php  echo $tab2[$i]['Name'];
    
    }  
    ?>
    </option>  
     
	</select>
  </td> 
  
<input type="submit" name="d2" value="DALEJ">
</form> </br>


</body>
</html>


