<?php
    $string = $_SERVER['QUERY_STRING'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://127.0.0.1:5000/".$string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $curlresult=curl_exec ($ch);
      curl_close ($ch);
    $onlyconsonants = str_replace( "\\", "",$curlresult);
    echo $onlyconsonants
?>