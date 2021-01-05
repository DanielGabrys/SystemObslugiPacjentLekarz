<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
header('location:index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>System ubslugi pacjenta</title>
</head>
<a href="main_pacjent.php"> <button > Powrót </button> </br> </br> </a>
<h2>LEKI</h2>
<body>
<?php
$id=$_SESSION['id'];

if(isset($_POST['status']))
{
$status=$_POST['status'];
$_POST['status_rem']=$status;
}

if(isset($_POST['choroba']))
{
$choroba=$_POST['choroba'];
$_POST['choroba_rem']=$choroba;
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
     
		//choroby
	   
		$rez2=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' order by chor_id");
		if(!$rez2) throw new Exception($con->error);
		
		$rezx=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' order by chor_id");
		if(!$rezx) throw new Exception($con->error);
		
		$ile2=$rez2->num_rows;
		$ilex=$rezx->num_rows;
		
			$licz=0; //normalnie reszta rezulatow jest da dole jest ten musibyc widoczny
			while($row = mysqli_fetch_assoc($rezx))
			{
			   $tab[$licz]=$row;
			    $licz++;
				 		
			}
	
		
		
        if(isset($_POST['status']) && $status!="wszystkie")
        {
		$rez2_2=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' AND status='$status' order by chor_id");
		
		
			if(!$rez2_2) throw new Exception($con->error);
			$ile2=$rez2_2->num_rows;
			$rez2=$rez2_2;
        }
        

	


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
            if(isset($_POST['choroba']) && $_POST['choroba']!="wszystkie")
            {
                $element='"';
                $element.=$_POST['choroba'];
                $element.='"';
            }
//echo $element;

            
            if($ile2>0) //wykonujemy jezeli pancjnet ma chorobyw przeciwnym razie nie moze miec lekow
            {
            $rez3=$con->query("select choroby.choroba,choroby.chor_Id,leki.* from choroby_leki JOIN leki ON leki.id_lek=choroby_leki.id_lek JOIN choroby ON choroby.chor_Id=choroby_leki.chor_Id WHERE choroba IN ($element)");
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
	/////////
?>

<form method="post">
    
   Choroba:
  <td>  <select name="choroba">
     <?php if(isset($_POST['choroba_rem']))
     {?>
     <option value ="<?php echo $_POST['choroba_rem'] ?>"><?php echo $_POST['choroba_rem'] ?> </option>  
     <?php
     }
     ?>
     
     <option value ="wszystkie">wszystkie </option>
    <?php
    for($i=0;$i<$ilex;$i++)
    {?>
	<option value ="<?php echo $tab[$i]['choroba'];?>"> <?php echo $tab[$i]['choroba']; ?> </option>
	<?php
	}?>
	</select>
  </td>
  
  Leki:
  <td>  <select name="status"> 
     <?php if(isset($_POST['status_rem']))
     {?>
     <option value ="<?php echo $_POST['status_rem']?>">
      <?php if($status=="wyleczony")
                echo "niebiezace";
            else if($status=="chory")
                echo "biezace";
             else
                 echo "wszystkie";
            ?>
    </option>  
     <?php
     }
     ?>
	<option value ="wszystkie">wszystkie </option>
	<option value ="wyleczony">niebiezace </option>
	<option value ="chory">biezace </option>
	</select>
  </td> 
<input type="submit" value="SZUKAJ">
</form> </br>
<?php


///wyswietlenie wszystkich lekow zchorobamiw tabeli
if($ile2>0 )
{

	$licz=0;
	while($row = mysqli_fetch_assoc($rez3))
		{
		  $tab3[$licz]=$row;
		  $licz++;
		}
if($tab3[0]['chor_Id']!=$tab2[0]['chor_id'] && $ile2==1)
{
    echo "nie znaleziono";
}
else
{
	$i=0;
?>	<table style="width:50%;">
<tr>
    <th>LEK</th>
    <th>DAWKOWANIE</th>
    <th>WAZNOSC</th>
 </tr>
<?php
	echo "CHOROBA: ".$tab3[$i]['choroba']."</br>";
	$zm=$tab3[$i]['choroba'];
	  ?>
				
				
				    <?php
	while($i<$ile3)
			{
			if($zm!=$tab3[$i]['choroba'])
				{?>
				<table style="width:50%;">
			     <tr>
                        <th>LEK</th>
                        <th>DAWKOWANIE</th>
                        <th>WAZNOSC</th>
                    </tr>
                    
                    <?php
				echo "</br>"."CHOROBA: ".$tab3[$i]['choroba']."</br>";
				$zm=$tab3[$i]['choroba'];
				}

              
				   
				    ?>
                <tr>
                     <td style="text-align:center"><?php echo $tab3[$i]['Nazwa']?></td>
                     <td style="text-align:center"><?php echo $tab3[$i]['Dawka']?></td>
                     <td style="text-align:center"><?php echo $tab3[$i]['Waznosc']?></td>
                </tr>
                
                
                
               <?php
		//	echo "Lek: ".$tab3[$i]['Nazwa']." ".$tab3[$i]['Dawka']." ".$tab3[$i]['Waznosc']."</br>";
			$i++;
			
			}
			?></table><?php
		echo "</br>";
		
	
	unset($_POST['leki']);
}	
}

?>

</body>
</html>

<style>
table, th, td 
{
  border: 1px solid black;
 
}
</style>
