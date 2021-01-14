<?php
session_start();
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

if(!isset($_SESSION['chor']))
{
$_SESSION['ilosc_lekow']=0;  //uzywane tylko jak dodajemy nowe leki
$_SESSION['ciag_lekow']="";
$_SESSION['leki'][]="";
$_SESSION['dawki'][]="";
$_SESSION['waznosc'][]="";
}

if(isset($_POST['Choroba']))  
$_SESSION['chor']=$_POST['Choroba'];

if(isset($_SESSION['chor']))
{
    $choroba=$_SESSION['chor'];

    require_once "conected.php"; //laczymy sie z baza aby zmienic dane pacjenta
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$con= new mysqli($servername,$username,$password,$database);
		if($con->connect_errno!=0)
			throw new Exception(mysqli_connect_errno());
		
		else
		{
		    
		    $rez=$con->query("SELECT * From choroby WHERE choroba='$choroba'");
		    if(!$rez) throw new Exception($con->error);
		    
		    $rez2=$con->query("select choroby.choroba,choroby.chor_Id,leki.* from choroby_leki JOIN leki ON leki.id_lek=choroby_leki.id_lek JOIN choroby ON choroby.chor_Id=choroby_leki.chor_Id WHERE choroby.choroba='$choroba'");
		    if(!$rez2) throw new Exception($con->error);

			$ile=$rez->num_rows;
			$ile2=$rez2->num_rows;
		    
		    $licz=0; //potrzebne tutaj (musi istniec jak chcemy wprodzawic leki_pacjenci dobazy danych)
			while($row = mysqli_fetch_assoc($rez2))
			{
			   $tab2[$licz]=$row;
			    $licz++;
			}
			if($ile2>0)
			    $chor_id=$tab2[0]['chor_Id'];
			$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}			


}

if(isset($choroba))
$_SESSION['choroba_rem']=$choroba;


//echo $_SESSION['ciag_lekow']."</br>";

///baza danych

require_once "conected.php"; //laczymy sie z baza aby zmienic dane pacjenta
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
		$con= new mysqli($servername,$username,$password,$database);
		if($con->connect_errno!=0)
			throw new Exception(mysqli_connect_errno());
		
		else
		{
		 	if(isset($_POST['zapisz']) && $ile>0) //1 przypadek dodajemy chorobe ktora istnieje juz w bazie
			{
			    //dodanie do bazy choroby-pacjenci
				if($con->query("INSERT INTO choroby_pacjenci VALUES ('$chor_id', '$id', NULL, 'chory', '-')"))
				{	
				    //dodanie do bazy pacjenci_leki(moze byc pare lekow do dodania)
				    $k=0;
				    while($k<$ile2)
				    {
				        $pom2=$tab2[$k]['id_lek'];
        				if($con->query("INSERT INTO leki_pacjenci VALUES (NULL, '$id','$pom2')")) //co jesli 1 zapytanie sie wykona a drugie nie trzeba pomyslec w przyszlosci:)
        				{	
        				    $k++;
        				    if($k==$ile2)
        				    {
        					$link ="Location:pacjent_profil.php?value="."$id";
        					header("$link");
        				    }
        					//exit();
        				}
        				else
        					throw new Exception($con->error);
				    }
				}
				else
					throw new Exception($con->error);
					
	
					
		    }
			else if(isset($_POST['zapisz2'])) // 2 przypadek dodajemy nowa chorobe
			{
			        $choroba=$_SESSION['chor'];
                
                    
                    // dodaj chorobe do bazy
			        if($con->query("INSERT INTO choroby VALUES (NULL,'$choroba')"))
				{	
				    //potrzebujemy id nowej chroby
				    $rez=$con->query("SELECT chor_Id From choroby WHERE choroba='$choroba'");
		            if(!$rez) throw new Exception($con->error);
		            
		            $row = mysqli_fetch_assoc($rez);
		            $chor_id=$row['chor_Id'];
		            
				    //dodaj chorobe do pacjenta (baza choroby_pacjenci)
				    $con->query("INSERT INTO choroby_pacjenci VALUES ('$chor_id','$id',NULL,'chory','-')");
				    // w petli dodaajemy nowe leki
				    $k=0;
				    while($k<$_SESSION['ilosc_lekow'])
				    {
				        $lek=$_SESSION['leki'][$k];
				        $dawka=$_SESSION['dawki'][$k];
                        $waznosc=$_SESSION['waznosc'][$k];
				        
				        //patrzymy czy dany lek mamy juz w bazie
				        $rez=$con->query("SELECT * From leki WHERE Nazwa='$lek'");
		                if(!$rez) throw new Exception($con->error);
		                
		                $ile=$rez->num_rows;
		               
		               //jezeli leku nie ma w bazie dodajemy
		                if($ile==0)
		                {
		                $con->query("INSERT INTO leki VALUES (NULL,'$lek','$dawka','$waznosc')");
		                }
		                
		                  //potrzebene id nowego/starego leku
		                  $rez=$con->query("SELECT * From leki WHERE Nazwa='$lek'");
		                  if(!$rez) throw new Exception($con->error);
		                  
		                  $row = mysqli_fetch_assoc($rez);
		                  $lek_id=$row['id_lek'];
		                   
		               ////dodanie leku do bazy choroba_leki
        			  	$con->query("INSERT INTO choroby_leki VALUES (NULL, '$chor_id','$lek_id')");
        			  	
        				////dodanie leku do bazy leki_pacjenci
        			  	$con->query("INSERT INTO leki_pacjenci VALUES (NULL, '$id','$lek_id')"); 
        				    $k++;
        				    if($k==$_SESSION['ilosc_lekow'])
        				    {
        					$link ="Location:pacjent_profil.php?value="."$id";
        					header("$link");
        				    }
        					//exit();
				    }
				}
				else
					throw new Exception($con->error);
			}
			
		}
		$con->close();
	}
	
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}			


//wypisanie lekow do choroby jezeli istnieje choroba
?>

<html>
<body>
            
			<a href="pacjent_profil.php?value=<?php echo $id ?>"> <button>Wroc</button> </br></br> </a> 
		
			<form method="post">
		    <?php
		    $okch=true;
		   if(isset($choroba))
    		  { 
               if(ctype_alnum($choroba)==false)
                                	{
                                		$okch=false;
                                		$_SESSION['E_chor']="Zle dane!";
                                	} 
    		   }
		    
			if(!isset($_SESSION['chor']) || $okch==false )
			{ 
			?>
            Nazwa choroby: <input type="text" name="Choroba" value =""/> </br></br>
            <?php //zapamietanie pola
            
           // if(isset($choroba))
           // {
            //    echo $choroba;
           // } ?>
            
            
             <?php
                    	if(isset($_SESSION['E_chor']))
                    	{	
                    		echo '<div class="error">'.$_SESSION['E_chor'].'</div> <br>';
                    		unset($_SESSION['E_chor']);
                    	}
                    	?>
            
            <?php
			}
		
	
            if(isset($_SESSION['chor']) && $okch==true )
            {
                
              
                if($ile==0)
                {
                    $ok=true;
                     if(isset($_POST['Lek']) && !isset($_POST['gotowe']))
        		           {
        		               $lek=$_POST['Lek'];
        		               $dawka=$_POST['Dawka'];
        		               $okres=$_POST['Okres'];
        		               
        		              ///kontrola bledow
                              if($lek=="")
                            	{
                            		$ok=false;
                            		$_SESSION['E_lek']="Nazwa leku powinna sie skladac z liter i cyfr!";
                            	} 
        		              if($dawka=="")
                            	{
                            		$ok=false;
                            		$_SESSION['E_dawka']="Dawka leku powinna sie skladac z liter i cyfr!";
                            	} 
                            	if($okres=="")
                            	{
                            		$ok=false;
                            		$_SESSION['E_okres']="Okres leku powinna sie skladac z liter i cyfr!";
                            	}
                            	
                            	 //sprawdzanie czy nie przypisano tego samego leku
                		                
                    		            $i=0;
                    	    	        while($i<$_SESSION['ilosc_lekow'])
                        	    	    {
                            	    	    
                            	    	    if($_SESSION['leki'][$i]==$lek)
                            	    	    {
                            	    	        $ok=false;
                            	            	$_SESSION['E_ten_sam']="Dodano juz dany lek do listy";
                            	    	    }
                            	    	    
                            	    	    $i++;
                        	        	}
                		                //
        		           }
        		      if(!isset($_POST['gotowe']))
        		      {
                    ?>
            			Dodaj lek: <input type="text" name="Lek" value =""/> </br> </br>
            			
            			<div class="info">
                         np: 2x na dzien/ 1x co 3 dni 
                        </div>
            			
                        <?php
                    	if(isset($_SESSION['E_lek']))
                    	{	
                    		echo '<div class="error">'.$_SESSION['E_lek'].'</div> <br>';
                    		unset($_SESSION['E_lek']);
                    	}
                    	?>
                    	
            			Dawkowanie: <input type="text" name="Dawka" value =""/> </br></br>
            			
            			
            			<div class="info">
                         np: 2 dni/ 3 tygodnie
                        </div>
                        
            			 <?php
                    	if(isset($_SESSION['E_dawka']))
                    	{	
                    		echo '<div class="error">'.$_SESSION['E_dawka'].'</div> <br>';
                    		unset($_SESSION['E_dawka']);
                    	}
                    	?>
            			Okres waznosci <input type="text" name="Okres" value =""/> </br> </br>
            			 <?php
                    	if(isset($_SESSION['E_okres']))
                    	{	
                    		echo '<div class="error">'.$_SESSION['E_okres'].'</div> <br>';
                    		unset($_SESSION['E_okres']);
                    	}
                    	?>
            			<input type="submit" name="dalej" value="Dodaj lek">
            			
            			 <?php
                    	if(isset($_SESSION['E_ten_sam']))
                    	{	
                    		echo '<div class="error">'.$_SESSION['E_ten_sam'].'</div> <br>';
                    		unset($_SESSION['E_ten_sam']);
                    	}
                    	?>
            			
            			<?php  //wypisanie lekow ktore przypisujemy do nowej choroby
            		     if(isset($_POST['dalej']))
            		     {
            		         
            		         if(isset($_POST['Lek']))
            		           {
            		               $lek=$_POST['Lek'];
            		               $dawka=$_POST['Dawka'];
            		               $okres=$_POST['Okres'];
            		               
            		             
            		          if($ok==true)
                		            {  
                		               
                		                
                		              $_SESSION['leki'][$_SESSION['ilosc_lekow']]=$lek;
                		              $_SESSION['dawki'][$_SESSION['ilosc_lekow']]=$dawka;
                		              $_SESSION['waznosc'][$_SESSION['ilosc_lekow']]=$okres;
                		              $_SESSION['ilosc_lekow']++;
                		            }
            		           }
            		           
            		          
            		        	
            		     }
        		      }
                        if(!isset($_POST['gotowe']))
                        {
            			?>
            			<input type="submit" name="gotowe" value="GOTOWE"> </br></br>
            	    	<?php
                        }
                                if(isset($_POST['dalej']))
                                {
                                 //wyswietlanie na biezaco dodawanych lekow
                		            $i=0;
                	    	        while($i<$_SESSION['ilosc_lekow'])
                    	    	    {
                        	    	    //echo $_SESSION['ilosc_lekow'];
                        	    	    echo "LEK: ".$_SESSION['leki'][$i]." ";
                        	    	    echo "DAWKA: ".$_SESSION['dawki'][$i]." ";
                        	    	    echo "OKRES WAZNOSCI: ".$_SESSION['waznosc'][$i]."</br>";
                        	    	    $i++;
                    	        	}
                                }
            	    
                	    	if(isset($_POST['gotowe']))
                	    	{
                	    	?>
                	    <input type="submit" name="zapisz2" value="ZAPISZ"> </br></br>
                	    <?php
                	        	echo "CHOROBA: ".$_SESSION['chor']."</br>";
            	    	
            	    	$i=0;
            	    	while($i<$_SESSION['ilosc_lekow'])
                	    	{
                	    	   // echo $_SESSION['ilosc_lekow'];
                	    	    echo "LEK: ".$_SESSION['leki'][$i]." ";
                	    	    echo "DAWKA: ".$_SESSION['dawki'][$i]." ";
                	    	    echo "OKRES WAZNOSCI: ".$_SESSION['waznosc'][$i]."</br>";
                	    	    $i++;
                	    	}
                	    	
                	    	}
        		    }
        		    
        		else
        		{
        		    
            		 ?>
        			<input type="submit" name="zapisz" value="ZAPISZ">
        			<?php
            		
        		}
        		
            /////nizej wykonuje sie tylko raz jak pierwszy raz wchodzimy na strone
			}
			else
        		{
            		 ?>
        			<input type="submit" name="dalej" value="Dalej">
        			<?php
        		}
			?>
			</form> </br>

</body>
</html>

<?php
if(isset($_POST['Choroba']) && $ile>0 )  //1 przypadek choroba w bazie
	{

	$i=0;
	echo "CHOROBA: ".$choroba."</br>";
	echo "LEKI: "."</br>";
	while($i<$ile2)
		{	
    		echo $tab2[$i]['Nazwa'].", ". $tab2[$i]['Dawka']."</br>";
    		$i++;
		}
	}
	


?>

<style>
.error
{
color:red;
margin-top:10px;
margin-left:10px;
}


.info
{
    position:absolute;
    margin-left: 420px;
    margin-top: 5px;
    color:blue;
}

</style> 

