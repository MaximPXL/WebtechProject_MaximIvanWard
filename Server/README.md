This README explains how to use the HTTP Webserver for our project

- before we can start you need to install php:
  - you can install it from the following link: https://www.php.net/
  - now you add the file to your path
  - next you open cmd and type: php -v
  - now php is installed and you can continue

- the final thing we need to build the Webserver is your IP-adress:
  - open cmd
  - type: ipconfig
  - search for: Wireless LAN adapter Wi-Fi
  - here you will find an IPv4-adress, this is the IP-adress we need

- now for the Webserver:
  - go to your folder containing the php filles (you can find these filles in the folder Webpage)
  - open cmd from this folder
  - then you type: php -S <your IP-adress>:80
  - this wil start your server, now you go to the external device

- when on the external device:
  - go to your browser
  - make sure you are connected to the same network as the device with the webserver
  - serch for http://<your IP-adress>/home.php
  - now you should be on the working site
