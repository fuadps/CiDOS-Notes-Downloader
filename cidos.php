<?php
	//Initialize the connection between user and lmspbu
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
  curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
	curl_setopt($login, CURLOPT_COOKIESESSION, true);
  curl_setopt($login, CURLOPT_TIMEOUT, 40000);
  curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($login, CURLOPT_URL, "http://lmsjpp.cidos.edu.my/login/index.php");
  curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0");
  curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($login, CURLOPT_POST, TRUE);
  curl_setopt($login, CURLOPT_POSTFIELDS, "submitlms.x=38&submitlms.y=55&username=$user&password=$pwd);
	
	//get resource after login
	curl_exec($login);
?>
