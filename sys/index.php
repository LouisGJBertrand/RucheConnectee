<?php

    // echo file_get_contents("http://127.0.0.1:81/?action=userTestConnection&uuid=%uuid%&password=%password%");

    function redirect($url)
    {
        header('Location: '.$url);
    }

    session_start();
    
    $apiUrl = "http://rucheconnapi.free.fr/API/";

    if (!is_null($_SESSION["CONN_Uuid"]) && !is_null($_SESSION["CONN_Upassword"])) {

        // TEST DE CONNECTION
        // PREPARATION DE LA REQUÊTE
        $get["uuid"] = $_SESSION["CONN_Uuid"];
        $get["password"] = $_SESSION["CONN_Upassword"];

        $apiRequest = "$apiUrl/?action=userTestConnection&uuid=%uuid%&password=%password%";
        $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
        $apiRequest = str_replace("%password%", $get["password"], $apiRequest);

        // ENVOIE DE LA REQUÊTE
        $apiResponse = json_decode(file_get_contents($apiRequest), true);
        // var_dump($apiResponse); 

        if ($apiResponse["value"] == true) {

            redirect("menu.php");

        }

    } else {
        redirect("login.php");
    }