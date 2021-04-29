CHECK OUT HOW IT WORKS HERE
https://obsluga-pacjent.000webhostapp.com/

AS USER - create account

AS DOCTOR - login below (this is one example , there are more doctors:))

LOGIN: michka@gmail.com
PASSWORD :Hello69dg



# INSTALLATION LINUX

##### 0. Loggin as administartor :
``
su [nazwa_administratora]
``
##### Install apache2
```
apt-get install apache2
service apache2 status
```

tap localhost in your browser, you should see apache page.

##### 2. Install php
```
apt-get install php 7.2* //version may differ
```

##### 3. Install mysql
```
apt install mysql-server -y
```

##### 4. Copy dowloaded files into /var/www/html
```
cp -r [path]/* ./
```

##### 5. Database inport:
```
mysql
CREATE DATABASE new_db_name;
mysql (–u username –p) new_db_name < dump_file.sql
```

##### 6. Edit file conected.php:
```
$username = "root";
$password = "your password";
```
