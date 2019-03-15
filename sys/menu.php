<?php

    // echo file_get_contents("http://127.0.0.1:81/?action=userTestConnection&uuid=%uuid%&password=%password%");

    function redirect($url)
    {
        header('Location: '.$url);
    }

    session_start();

    $apiUrl = "http://rucheconnapi.free.fr/API/";

    if ($_SESSION["CONN_Uuid"] && $_SESSION["CONN_Upassword"]) {

        $get["uuid"] = $_SESSION["CONN_Uuid"];
        $get["password"] = $_SESSION["CONN_Upassword"];


        $apiRequest = "$apiUrl/?action=userTestConnection&uuid=%uuid%&pass=%password%";
        $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
        $apiRequest = str_replace("%password%", $get["password"], $apiRequest);
        $apiResponse = json_decode(file_get_contents($apiRequest), true);
        // var_dump($apiResponse); 

        if ($apiResponse["value"] == true) {

            $apiRequest = "$apiUrl/?action=getUserInfos&uuid=%uuid%&password=%password%";
            $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
            $apiRequest = str_replace("%password%", $get["password"], $apiRequest);
            $apiResponse = json_decode(file_get_contents($apiRequest), true);

            echo "<pre>";
            echo "<a href=\"logout.php\"><i>déconexion</i></a>\n";
            echo "Bonjour <i>".$apiResponse["value"]["uIdentifier"]."</i>\n";
            echo "</pre>";

        } else {
            redirect("login.php");
        }

    } else {
        redirect("login.php");
    }

    ?>
<title>EasyFly - home</title>
