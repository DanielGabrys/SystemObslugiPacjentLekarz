# LINUX

##### 0. Najlepiej zalogować się od razu na konto administratora:
su [nazwa_administratora]

W innym wypadku przed większością komend trzeba dodawać 
sudo


##### 1. Instalujemy apache2
apt-get install apache2
service apache2 status

Po zainstalowaniu wpisujemy w przegladarkę localhost, w przypadku poprawnej instalacji pojawi się strona główna apache.

##### 2. Instalujemy php
apt-get install php 7.2*

##### 3. Instalujemy mysql
apt install mysql-server -y

##### 4. Kopiujemy pobrane pliki programu do folderu /var/www/html
cp -r [ścieżka do plików]/* ./

##### 5. Import bazy danych:
mysql
CREATE DATABASE new_db_name;
mysql (–u username –p) new_db_name < dump_file.sql

##### 6. Edytujemy plik conected.php:
$username = "root";
$password = "twoje_haslo";



































*aktualna wersja
