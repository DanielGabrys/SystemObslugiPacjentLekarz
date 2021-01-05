<?php
session_start();
unset($_SESSION['ilosc_lekow']);
unset($_SESSION['leki']);
unset($_SESSION['chor']);
unset($_SESSION['dawki']);
unset($_SESSION['waznosc']);
unset($_SESSION['E_chor']);
////pobieramy id przez get
if(isset($_GET['value']) && is_numeric($_GET['value']))
{
	$id = $_GET['value'];
	//$id2 = htmlentities($id);
	//echo $id;
	unset($_GET['value']);
}
else
{
header('Location:zaloguj_doctor_layout.php');
exit();
}

$ile0=0;
$ile2=0;
$il3=0;

//////////////////////////////////////////// laczenie sie z baza aby wyszukac informacje
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
                ////////////dane kontaktowe
		$rez=$con->query("SELECT * From customers WHERE Id='$id'");
		if(!$rez) throw new Exception($con->error);

		//choroby
		$rez2=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' order by chor_id");
		if(!$rez2) throw new Exception($con->error);
		//leki
		
			$ile=$rez->num_rows;
			$ile2=$rez2->num_rows;
			


		$licz=0; //normalnie reszta rezulatow jest da dole jest ten musibyc widoczny
		$element='"'; //tworzymy ciag do zapytania nr 3
		$element2='","';	
		$element3='"';			
			while($row = mysqli_fetch_assoc($rez2))
			{
			   $tab2[$licz]=$row;
			   $element.=$tab2[$licz]['choroba']; 
			   if($licz!=$ile2-1 )
			   $element.=$element2;
			   else
			    $element.=$element3;
			    $licz++;
				 		
			}
      			//echo $element;

//$rez3=$con->query("SELECT customers.Name,customers.Id,leki.Nazwa FROM leki_pacjenci JOIN customers ON customers.Id = leki_pacjenci.cus_id JOIN leki ON leki.id_lek = leki_pacjenci.id_lek where customers.Id='$id'");

if($ile2>0) //wykonujemy jezeli pancjnet ma chorobyw przeciwnym razie nie moze miec lekow
{
$rez3=$con->query("select choroby.choroba,choroby.chor_Id,leki.Nazwa,leki.Id_lek from choroby_leki JOIN leki ON leki.id_lek=choroby_leki.id_lek JOIN choroby ON choroby.chor_Id=choroby_leki.chor_Id WHERE choroba IN ($element)");
		if(!$rez3) throw new Exception($con->error);

		$ile3=$rez3->num_rows;
}
			$con->close();


		}
	}

	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}	
   		

if(isset($_GET['x'])) //oznacza ze przeszlismy do edycji choroby, nie mamy narazie walidacji danych wprowadzanych przez lekarza
{

if(isset($_POST['status']))
	$st=$_POST['status'];

if(isset($_POST['Data']))
	$Data=$_POST['Data'];

$y=$_GET['x'];



require_once "conected.php"; //laczymy sie z baza aby zmienic dane pacjenta
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$con= new mysqli($servername,$username,$password,$database);
		if($con->connect_errno!=0)
			throw new Exception(mysqli_connect_errno());
		
		else
		{
		 	if(isset($_POST['zapisz']))
			{
				if($con->query("update choroby_pacjenci set status='$st',data_wyleczenia='$Data' WHERE cus_id='$id' AND chor_id='$y'"))
				{	
					//$_SESSION['udana_rej']=true;
					//echo "zaktualizowano.<br/><br/>";
					$link ="Location:pacjent_profil.php?value="."$id";
					header("$link");
					//exit();
				}
				else
					throw new Exception($con->error);
					
			}
			$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}			



//unset($_GET['x']);			
?>
<html>
<body>
			<a href="pacjent_profil.php?value=<?php echo $id ?>"> <button>Wroc</button> </br></br> </a> 
		
			<form method="post">

			Status:
			  <td>  <select name="status" value="wyleczony">
				<option value ="wyleczony">wyleczony
				<option value ="chory">chory
				</select>
			  </td> </br> </br>
			Data wyleczenia: <input type="text" name="Data" value ="-"/>  </br></br>
			<input type="submit" name="zapisz" value="Zapisz zmiany">
			</form> </br>
				
<?php
}



else
{
    
?> 
<a href="pacjenci.php"> <button > Powrót </button> </br> </br> </a>

<?php
////pokazujemydane kontaktowe
	{

	$licz=0;
				
		while($row = mysqli_fetch_assoc($rez))
			{
			  $tab[$licz]=$row;
			  $licz++;
					   		
			}


	$i=0;
	while($i<$ile)
		{	
		echo "Imie i Nazwisko: ".$tab[$i]['Name']."</br>";
		echo "Mail: ".$tab[$i]['Mail']."</br>";
		echo "Telefon: "."</br></br>";
		$i++;
		}
	}
?>

<form method="post">
  <input type="submit" name="choroby" value="Choroby">
  <input type="submit" name="leki" value="Leki"> </br>

</form> 

<?php



if(isset($_POST['choroby']) )
	{

	$i=0;
	while($i<$ile2)
		{	
		echo "Choroba: ".$tab2[$i]['choroba']." ";
		?> <a href="pacjent_profil.php?value=<?php echo $id?>&x=<?php echo $tab2[$i]['chor_id']?>"> <button>Edytuj</button> </br></a>
		<?php
		echo "Status: ".$tab2[$i]['status']."</br>";
		echo "Data Wyleczenia: ".$tab2[$i]['data_wyleczenia']."</br> </br>";
		$i++;
		
		
		}

	unset($_POST['choroby']);

	?><a href="dodaj_choroba.php?value=<?php echo $id?>"> <button>Dodaj chorobe</button> </br></br> </a>
	<?php
	}

if(isset($_POST['leki']) && $ile2>0 )
{

	$licz=0;
				
			while($row = mysqli_fetch_assoc($rez3))
			{
			  $tab3[$licz]=$row;
			   $licz++;
				   		
			}

	$i=0;
		echo "CHOROBA: ".$tab3[$i]['choroba']."</br>";
		$zm=$tab3[$i]['choroba'];
		while($i<$ile3)
			{
			if($zm!=$tab3[$i]['choroba'])
				{
				echo "</br>"."CHOROBA: ".$tab3[$i]['choroba']."</br>";
				$zm=$tab3[$i]['choroba'];
				}
			echo "Lek: ".$tab3[$i]['Nazwa']."</br>";
			$i++;
			}
		echo "</br>";
		
	
	unset($_POST['leki']);
	
}
}
?>

</body>
</html>



