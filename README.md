
LINUX
0. logujemy się na konto administratora

su
Password ….

1.instalujemy apache2

apt-get install apache2
service apache2 status
wpisać w przegladarke localhost(powinno działać)

2.
sudo apt-get install php 7.2*

edytowanie pliku index.html

3. instalowanie mysql

 sudo apt install mysql-server -y

4. kopiujemy pliki programu do folderu /var/www/html

sudo cp -r /home/karolina/Desktop/E-porady/* ./

5.

Import bazy danych:

mysql
CREATE DATABASE new_db_name;
mysql (–u username –p) new_db_name < dump_file.sql

6.
edytuj conected.php:
$username = "root";
$password = "twoje_haslo";



































*aktualna wersja