<?php
    if(isset($_SERVER["QUERY_STRING"])){
        $query_str = $_SERVER["QUERY_STRING"];
        if(preg_match("/^[0-9+\-*\/()]+$/",$query_str)){
            $formatted = "echo ".$query_str.";";
            eval($formatted);
        } else {
            echo "ERROR";
        }
    }
?>