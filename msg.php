<?php

$username = "ak.enterprise6874@gmail.com";
	$hash = "8f166b2804793ce6abc6a06c1a83ab96b92a37933c571dd7f12637a2c2ec36de";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";



    $sender = "HKHMSG"; // This is who the message appears to be from.
	$numbers = "919164503045"; // A single number or a comma-seperated list of numbers
	$message = "HI This is test";

echo $message;

    	$message = urlencode($message);
$data = "username= ".$username." &hash= ".$hash." &message= ".$message."&sender= ".$sender."&numbers= ".$numbers." &test= ".$test;
	$ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
	curl_close($ch);

  echo $result;

 ?>
