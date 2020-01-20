<?php


$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://besoft-mail.com/besmartOdlasci/public/besmart/autoclose");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);
curl_close($ch);


?>