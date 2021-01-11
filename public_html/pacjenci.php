<?php

session_start();
//zakladamy ze dane sa poprawne
$ID=$_SESSION['Id'];
$tab[][]=NULL;
////

require_once "conected.php"; //jak pierwszy raz wchodzimy do widzimy liste wszystkich pacjentow
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
		    //szukamy pacjentow przypisanych do danego lekarza
		    $rezx=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,lekarze.Name FROM pacjenci JOIN customers ON customers.Id = pacjenci.cus_id JOIN lekarze ON lekarze.ID = pacjenci.lekarz_id WHERE lekarze.ID='$ID' ");
    		if(!$rezx) throw new Exception($con->error);
    		
            $ilex=$rezx->num_rows;
            //// ciag id do pokazania pacjentow przypisanych do danego lekarza

             $licz=0; //normalnie reszta rezulatow jest da dole jest ten musibyc widoczny
             $element="";
	         $element2=',';	
			while($row = mysqli_fetch_assoc($rezx))
			{
			   $tabx[$licz]=$row;
			   $element.=$tabx[$licz]['Id']; 
			   if($licz!=$ilex-1 )
			     $element.=$element2;
			   
			    $licz++;
				 		
			}
	        //echo $element;
	        if($ilex==0) //jezeli lekarz nie ma przypisanych pacjentow
	        $element=0;
            ////
            
    		$rez0=$con->query("SELECT Name,Pesel,Id From customers WHERE Id IN ($element)");
    		if(!$rez0) throw new Exception($con->error);
    		
    		$ile0=$rez0->num_rows;
    					$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}

/////////
if(isset($_POST['Pesel']) )
{
$ok=true;
$pesel=$_POST['Pesel'];
$choroba=$_POST['Choroba'];
$Status=$_POST['status'];
//laczenie z baza
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
        //wszyscy
		$rez0=$con->query("SELECT Name,Pesel,Id From customers WHERE Id IN ($element)");
		if(!$rez0) throw new Exception($con->error);

        //pesel,puste,puste
		$rez=$con->query("SELECT * FROM customers WHERE Pesel='$pesel' AND Id IN ($element)"); 
		if(!$rez) throw new Exception($con->error);

        //puste,choroba,puste
		$rez2=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE choroby.choroba='$choroba' AND customers.Id IN ($element)");
		if(!$rez2) throw new Exception($con->error);

        //pesel,choroba,puste
		$rez3=$con->query("SELECT customers.*,choroby.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id where choroby.choroba='$choroba' AND customers.Pesel='$pesel'AND customers.Id IN ($element)");
		if(!$rez3) throw new Exception($con->error);

        //puste,puste,status
		$rez1_2=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE choroby_pacjenci.status='$Status' AND customers.Id IN ($element)");
		if(!$rez1_2) throw new Exception($con->error);

        //pesel,choroby,puste
		$rez2_2=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Pesel='$pesel' AND choroby_pacjenci.status='$Status'AND customers.Id IN ($element)");
		if(!$rez2_2) throw new Exception($con->error);

        //puste,choroba,status
		$rez2_3=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE choroby.choroba='$choroba' AND choroby_pacjenci.status='$Status'AND customers.Id IN ($element)");
		if(!$rez2_3) throw new Exception($con->error);
        
        //pesel,choroba,status
		$rez3_1=$con->query("SELECT customers.Name,customers.Pesel,customers.Id,choroby.choroba FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Pesel='$pesel' AND choroby.choroba='$choroba' AND choroby_pacjenci.status='$Status'AND customers.Id IN ($element)");
		if(!$rez3_1) throw new Exception($con->error);

		
			$ile0=$rez0->num_rows;
			$ile=$rez->num_rows;
			$ile2=$rez2->num_rows;
			$ile3=$rez3->num_rows;
			$ile1_2=$rez1_2->num_rows;
			$ile2_2=$rez2_2->num_rows;
			$ile2_3=$rez2_3->num_rows;
			$ile3_1=$rez3_1->num_rows;

            
			if($ile==0 && $ile2==0 && $Status=="a")       //puste,puste,wszyscy
			{
			 $ok=false;
			 $_SESSION['blad']="nie znaleziono";

			}
		
		      //jezeli spelnione wszystkie warunki
			
			if($ok==true)
			{
				if($ile2==0 && $choroba=="" && $Status=="a") //pesel,puste,wszyscy
				{
				$wiersz=$rez->fetch_assoc();
					
			
					$_SESSION['pesel']=$wiersz['Pesel'];
					$_SESSION['name']=$wiersz['Name'];
					$_SESSION['id']=$wiersz['Id'];
					$rez->close();
				}

				else if($ile==0 && $pesel=="" && $Status=="a") //puste,choroba,wszyscy
				{
				$licz=0;
				
					while($row = mysqli_fetch_assoc($rez2))
					{
					    $tab[$licz]=$row;
					    $licz++;
					   		
					}
					$_SESSION['choroba']=1;	
					$rez2->close();
					
				}

				else if($ile!=0 && $ile2!=0 && $ile3!=0 && $Status=="a") //pesel,choroba,wszyscy
				{
				$wiersz=$rez3->fetch_assoc();
					
			
					$_SESSION['pesel']=$wiersz['Pesel'];
					$_SESSION['name']=$wiersz['Name'];
					$_SESSION['id']=$wiersz['Id'];
					$rez3->close();
				}

				else if($pesel=="" && $choroba=="") //puste,puste,chory/wyleczony
				{
				$licz=0;
					while($row = mysqli_fetch_assoc($rez1_2))
					{
					    $tab[$licz]=$row;
					    $licz++;						
					}
					$_SESSION['pusty_pusty_chorzy_wyzdr']=1;	
					$rez1_2->close();
				}

				else if($ile2_2>0 && $choroba=="") //pesel,puste,chory/wyleczony
				{
				$wiersz=$rez2_2->fetch_assoc();
					
			
					$_SESSION['pesel']=$wiersz['Pesel'];
					$_SESSION['name']=$wiersz['Name'];
					$_SESSION['id']=$wiersz['Id'];
					$rez2_2->close();
				}

				else if($pesel=="" && $ile2_3>0) //puste,choroba,chory/wyleczony
				{
				$licz=0;
				
					while($row = mysqli_fetch_assoc($rez2_3))
					{
					    $tab[$licz]=$row;
					    $licz++;
					}

					$_SESSION['pusty_choroba_chorzy_wyzdr']=1;	
					$rez2_3->close();
					
				}

				else if($ile2_2>0 && $ile2_3>0 && $ile3_1>0) //pesel,choroba,chory/wyleczony
				{
				$wiersz=$rez3_1->fetch_assoc();
					
			
					$_SESSION['pesel']=$wiersz['Pesel'];
					$_SESSION['name']=$wiersz['Name'];
					$_SESSION['id']=$wiersz['Id'];
					$rez3_1->close();
				}

				else
				{
				$ok=false;
				$_SESSION['blad']="nie znaleziono";				
				}
	
                        }
			$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}
}	
   		
				
		
?>
<!DOCTYPE html>
<html>
<body>
    
<a href="main_dc.php"> <button > Powrót </button> </br> </a>
<h2>Pacjenci</h2>
<form method="post">
  Pesel: <input type="text" name="Pesel" value =""/> 
  Choroba: <input type="text" name="Choroba" value="" />
  Status:
  <td>  <select name="status">
	<option value ="a">wszystkie
	<option value ="wyleczony">wyleczony
	<option value ="chory">chory
	</select>
  </td> </br> </br>
<input type="submit" value="SZUKAJ">
<input type="submit" name="wszyscy" value="Pokaz wszystkich pacjentow"> </br>
</form> </br>
<?php

if(isset($_SESSION['blad']) && !isset($_POST['wszyscy'])) //jezeli blad
	{	
		echo $_SESSION['blad'];
		unset($_SESSION['blad']);
	}

if(isset($_SESSION['pesel']) )
	{	

		echo $_SESSION['name']."\t".$_SESSION['pesel'];
		?> <a href="pacjent_profil.php?value=<?php echo $_SESSION['id']?>"> <button>Podglad</button> </br></br> </a> 
		<?php
		unset($_SESSION['name']);
		unset($_SESSION['pesel']);
		unset($_SESSION['id']);
	}
?>

<?php
if(isset($_SESSION['choroba']) ) //gdy znalezlismy choroby wypisujemy tablice 
	{
	$i=0;
	while($i<$ile2)
		{	
		echo "Pesel: ".$tab[$i]['Pesel'].", "."Imie: ".$tab[$i]['Name']."</br>";
		?> <a href="pacjent_profil.php?value=<?php echo $tab[$i]['Id']?>"> <button>Podglad</button> </br></br> </a> 
		<?php
		$i++;
		}
	unset($_SESSION['choroba']);
	}

if(isset($_SESSION['pusty_pusty_chorzy_wyzdr']) )
	{
	$i=0;
	while($i<$ile1_2)
		{	
		echo "Pesel: ".$tab[$i]['Pesel'].", "."Imie: ".$tab[$i]['Name']."</br>";
		?> <a href="pacjent_profil.php?value=<?php echo $tab[$i]['Id']?>"> <button>Podglad</button> </br></br> </a> 
		<?php
		$i++;
		}
	unset($_SESSION['pusty_pusty_chorzy_wyzdr']);
	}

if(isset($_SESSION['pusty_choroba_chorzy_wyzdr']) )
	{
	$i=0;
	while($i<$ile2_3)
		{	
		echo "Pesel: ".$tab[$i]['Pesel'].", "."Imie: ".$tab[$i]['Name']."</br>";
		?> <a <a href="pacjent_profil.php?value=<?php echo $tab[$i]['Id']?>"> <button>Podglad</button> </br></br> </a> 
		<?php
		$i++;
		}
	unset($_SESSION['pusty_choroba_chorzy_wyzdr']);
	}



if(isset($_POST['wszyscy']) || !isset($_POST['Pesel'])  ) //jezeli kliknieto przycisk pokaz wszystkich pascjentow
	{
		if($ile0>0)
		{
		unset($_SESSION['blad']);
		$licz=0;
				
			while($row = mysqli_fetch_assoc($rez0))
			{
			    $tab[$licz]=$row;
				$licz++;		
			}
		}	

	$i=0;
	while($i<$ile0)
		{	
		echo "Pesel: ".$tab[$i]['Pesel'].", "."Imie: ".$tab[$i]['Name']."</br>"
		?> <a href="pacjent_profil.php?value=<?php echo $tab[$i]['Id']?>"> <button>Podglad</button> </br></br> </a> 
		<?php
		$i++;
		}
	}
?>



</body>
</html>
