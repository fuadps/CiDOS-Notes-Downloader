<?php
	
	//student authentication
	$uname = "";
	$pwd = "";
	
	//Initialize the connection between user and lmspbu
	$login = curl_init();
	curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
	curl_setopt($login, CURLOPT_COOKIESESSION, true);
    	curl_setopt($login, CURLOPT_TIMEOUT, 40000);
    	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    	curl_setopt($login, CURLOPT_URL, "http://lmspbu.cidos.edu.my/login/index.php");
    	curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0");
    	curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
    	curl_setopt($login, CURLOPT_POST, TRUE);
    	curl_setopt($login, CURLOPT_POSTFIELDS, "submitlms.x=38&submitlms.y=55&username=$uname&password=$pwd");
	
	//get resource after login
	$data = curl_exec($login);
	
	//grab list of course student enroll from drop down menu
	$pattern = '/href="(http:\/\/lmspbu\.cidos\.edu\.my\/course\/view\.php\?id=\d{1,3})">(.*?)<\/a>/';
	preg_match_all($pattern, $data, $course, PREG_SET_ORDER, 0);
	
	//checking $course dump
	//var_dump($course);
	
	echo "List of course enroll : \n\n";
	
	//check course array length
	$course_length = count($course) ;
	
	//display all the course student have enroll
	for($i = 0; $i < $course_length ;$i++ ) {
		
		echo $i+1 . ".\t". htmlspecialchars_decode($course[$i][2]) . "\n";
		
	}
	
	//user input course 
	course_pick : 
	echo "\nChoose your course [1-".$course_length."] : ";
	$course_num = trim(fgets(STDIN,1024));
	
	if ($course_num < $course_length) {
		
		// -1 becoz the numbering start 1 huhu
		$course_link = $course[$course_num-1][1];
		
	} else if ($course_num > $course_length){
		echo "Number not in range!";
		goto course_pick;
	} 

?>
