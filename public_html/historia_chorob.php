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
<h2>CHOROBY</h2>
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
     
		//choroby wszystkie
	   
		$rez=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' order by chor_id");
		if(!$rez) throw new Exception($con->error);
		
		$ile=$rez->num_rows;
		
       //choroby ze statusem
        if(isset($_POST['status']) && $status!="wszystkie")
        {
		$rez2=$con->query("SELECT customers.Name,customers.Pesel,choroby.choroba,choroby_pacjenci.* FROM choroby_pacjenci JOIN customers ON customers.Id = choroby_pacjenci.cus_id JOIN choroby ON choroby.chor_id = choroby_pacjenci.chor_id WHERE customers.Id='$id' AND status='$status' order by chor_id");
		
		
			if(!$rez2) throw new Exception($con->error);
			$ile2=$rez2->num_rows;
			
			
			$ile=$rez2->num_rows;
			$rez=$rez2;
        }
        


		$licz=0; 
			
			while($row = mysqli_fetch_assoc($rez))
			{
			   $tab[$licz]=$row;
			   
			  
			    $licz++;
				 		
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
    
  
  STATUS:
  <td>  <select name="status"> 
     <?php if(isset($_POST['status_rem']))
     {?>
     <option value ="<?php echo $_POST['status_rem']?>">
      <?php if($status=="wyleczony")
                echo "wyleczony";
            else if($status=="chory")
                echo "chory";
             else
                 echo "wszystkie";
            ?>
    </option>  
     <?php
     }
     ?>
	<option value ="wszystkie">wszystkie </option>
	<option value ="wyleczony">wyleczony </option>
	<option value ="chory">chory </option>
	</select>
  </td> 
<input type="submit" value="SZUKAJ">
</form> </br>
<?php


///wyswietlenie wszystkich lekow zchorobamiw tabeli
if($ile>0 )
{

?>	<table style="width:50%;">
<tr>
    <th>CHOROBA</th>
    <th>STATUS</th>
    <th>DATA WYLECZENIA</th>
 </tr>

				
 <?php
    $i=0;
	while($i<$ile)
		{

			 ?>
                <tr>
                     <td style="text-align:center"><?php echo $tab[$i]['choroba']?></td>
                     <td style="text-align:center"><?php echo $tab[$i]['status']?></td>
                     <td style="text-align:center"><?php echo $tab[$i]['data_wyleczenia']?></td>
                </tr>

              <?php
			$i++;
			
		}
			?></table><?php
		echo "</br>";
		
	
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
