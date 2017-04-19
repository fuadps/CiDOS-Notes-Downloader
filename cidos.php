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
	
	//////////////////////////////////////
	//list all course
	/////////////////////////////////////
	
	$pattern_name = '/<div class="logininfo">You are logged in as (.*?)\(<a href=/';
	
	preg_match_all($pattern_name, $data, $name, PREG_SET_ORDER, 0);
	
	echo "Log in as ".$name[0][1]."\n";
	
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
	//////////////////////////////////////
	//list all notes
	/////////////////////////////////////
	
	curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    	curl_setopt($login, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0");
    	curl_setopt($login, CURLOPT_TIMEOUT, 40);
    	curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    	curl_setopt($login, CURLOPT_URL, $course_link);
	
	$notes_web = curl_exec($login);
	
	//detect notes by using ppt images
	//bad practice i guess.will come up with new pattern later.	
	$pattern = '/href="(http:\/\/lmspbu\.cidos\.edu\.my\/mod\/resource\/view\.php\?id=\d{4})"><img src="http:\/\/lmspbu\.cidos\.edu\.my\/theme\/image\.php\?theme=essential&amp;component=core&amp;image=f%2Fpowerpoint-24" class="iconlarge activityicon" alt=" " role="presentation" \/><span class="instancename">(.*?)<span/';
	preg_match_all($pattern, $notes_web, $notes, PREG_SET_ORDER, 0);
	
	//checking dump 
	//var_dump($notes);
	
	echo "List of notes : \n\n";
	
	//check course array length
	$notes_length = count($notes) ;
	
	//display all the course student have enroll
	for($i = 0; $i < $notes_length ;$i++ ) {
		
		echo $i+1 . ".\t". htmlspecialchars_decode($notes[$i][2]) . "\n";
		
	}
	
	////////////////////////////////////////
	//DOWNLOAD FILE
	///////////////////////////////////////
	
	echo "\nChoose your notes [1-".$notes_length."] : ";
	$notes_num = trim(fgets(STDIN,1024));
	
	if ($notes_num < $notes_length) {
		
		// -1 becoz the numbering start 1 huhu
		$notes_link = $notes[$notes_num-1][1];
		
	} else if ($notes_num > $notes_length){
		echo "Number not in range!";
	}

	curl_setopt($login, CURLOPT_URL, $notes_link);
	curl_setopt($login, CURLOPT_RETURNTRANSFER, 1);
	
	$rawdata = curl_exec($login);

	/*
	///////////////////////////////////////////////////////////////
	Problem with this block : 
	Need to download 2 times the file just to detect extension LUL
	extremely not recommended
	just comment it for reasons.
	/////////////////////////////////////////////////////////////
	$mime_type =  curl_getinfo($login, CURLINFO_CONTENT_TYPE);
	curl_close ($login);
	
	//checking extension using curlinfo content type
	//will update later for more file extension
	if ($mime_type == "application/vnd.ms-powerpoint") {
		$ext = 'ppt';
	}
	*/
	
	//list extension available
	$mime_type = array (
	
		//source : https://www.sitepoint.com/web-foundations/mime-types-summary-list/
		//ms office
		'doc' => 'application/msword',
        	'rtf' => 'application/rtf',
        	'xls' => 'application/vnd.ms-excel',
        	'ppt' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpointtd>',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'

	);
	
	//save downloaded file
	//destination : /files
	$destination = "./files/{$notes[$notes_num-1][2]}";
	$file = fopen($destination, "w+");
	fputs($file, $rawdata);
	fclose($file);
	
	
	//detect mime type of the file after save.
	$mimeheader = mime_content_type("./files/{$notes[$notes_num-1][2]}");
	
	//seach mime types of the file
	$ext = array_search($mimeheader,$mime_type);
	
	//add file extension name after detect it mime type.
	rename("./files/{$notes[$notes_num-1][2]}","./files/{$notes[$notes_num-1][2]}.$ext");
	
	//show destinations of the file
	echo "File saved at : files/".$notes[$notes_num-1][2].".".$ext;

?>
