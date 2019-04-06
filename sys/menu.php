<?php

    // echo file_get_contents("http://127.0.0.1:81/?action=userTestConnection&uuid=%uuid%&password=%password%");

    function redirect($url)
    {
        header('Location: '.$url);
    }

    session_start();

    $apiUrl = "http://rucheconnapi.free.fr/API/";

    if ($_SESSION["CONN_Uuid"] && $_SESSION["CONN_Upassword"]) {

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

            // RECUPERATION DES DONNEES UTILISATEUR
            // PREPARATION DE LA REQUÊTE
            $apiRequest = "$apiUrl/?action=getUserInfos&uuid=%uuid%&password=%password%";
            $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
            $apiRequest = str_replace("%password%", $get["password"], $apiRequest);
            
            // ENVOIE DE LA REQUÊTE
            $apiResponse = json_decode(file_get_contents($apiRequest), true);

            echo "<header class=\"topBar_Header\">\r\n";
            echo "<a href=\"logout.php\"><i>déconexion</i></a>\r\n";
            echo "</header>";

            echo "<div class=\"contrainer\">\r\n";
            echo "<div class=\"FullSizePannel\">\r\n";
            echo "<h1>Bonjour <i>".$apiResponse["value"]["uIdentifier"]."</i>.</h1>\r\n";
            echo "</div>\r\n";

            // RECUPERATION DES RUCHES DE UTILISATEUR
            // PREPARATION DE LA REQUÊTE
            $apiRequest = "$apiUrl?action=listUserBeeHives&uuid=%uuid%&prkey=%password%";
            $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
            $apiRequest = str_replace("%password%", "1c52d5c2f1bb27eaabf306701212b1a28cf79dcc", $apiRequest);
            echo $apiRequest;


            // ENVOIE DE LA REQUÊTE
            $apiResponse = json_decode(file_get_contents($apiRequest), true);
            print_r($apiResponse);

            foreach ($apiResponse["value"] as $key => $value) {

                // RECUPERATION DES RUCHES DE UTILISATEUR
                // PREPARATION DE LA REQUÊTE
                $apiRequest = "$apiUrl/?action=getDataWeight&uuid=%uuid%&prkey=%password%";
                $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
                $apiRequest = str_replace("%password%", "699d0dd2eb6b939f21f41b3bbf8c5d093fddcc99", $apiRequest);

                // ENVOIE DE LA REQUÊTE
                $apiResponse = json_decode(file_get_contents($apiRequest), true);

                echo "<div class=\"HalfSizePannel\">\r\n";
                print_r($apiResponse);
                echo "<h1 class=\"numberShowcase\">".$apiResponse["value"][count($search)]["value"]."</h1>\r\n";
                echo "</div>\r\n";
                echo "</div>\r\n";

            }

        } else {
            redirect("login.php");
        }

    } else {
        redirect("login.php");
    }

    ?>
<title>EasyFly - home</title>
