This README explains how to use the HTTP Webserver for our project

before we can start you need to install php
	1.you can install it from the following link: https://www.php.net/ 
	2.now you add the file to your path
	3.next you open cmd and type: php -v
	4.now php is installed and you can continue

the final thing we need to build the Webserver is your IP-adress
	1.open cmd
	2.type: ipconfig
	3.search for: Wireless LAN adapter Wi-Fi
	4.here you will find an IPv4-adress, this is the IP-adress we need

now for the Webserver
	1.go to your folder containing the php filles
	2.open cmd from this folder
	3.then you type: php -S <your IP-adress>:80
	4.this wil start your server, now you go to the external device

when on the external device
	1.go to your browser
	2.make sure you are connected to the same network as the device with the webserver
	3.serch for http://<your IP-adress>/home.php
	4.now you should be on the working site
