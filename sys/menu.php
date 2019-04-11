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

            if ($_SESSION["style"] == "dark") {
                $style = "dark";
            } else {
                $style = "clear";
            }

            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/$style.css\">";
            echo "<meta name=\"viewport\" content=\"initial-scale=1, maximum-scale=1\">";

            echo "<header class=\"topBar_Header\">\r\n";
            echo "<a href=\"logout.php\"><i>déconnexion</i></a>\r\n";
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

            // ENVOIE DE LA REQUÊTE
            $apiResponse = json_decode(file_get_contents($apiRequest), true);

            // echo "<pre>";

            // print_r($apiRequest);

            // die();
            foreach ($apiResponse["value"]["id"] as $key => $value) {

                // RECUPERATION DES DONNEES RUCHE (POIDS)
                // PREPARATION DE LA REQUÊTE
                $apiRequest = "$apiUrl/?action=getDataWeight&prkey=%password%";
                $apiRequest = str_replace("%password%", $value, $apiRequest);

                // ENVOIE DE LA REQUÊTE
                $apiResponsebis = json_decode(file_get_contents($apiRequest), true);

                // RECUPERATION DES DONNEES RUCHE (POIDS)
                // PREPARATION DE LA REQUÊTE
                $apiRequest = "$apiUrl/?action=getDataGPS&prkey=%password%";
                $apiRequest = str_replace("%password%", $value, $apiRequest);

                // ENVOIE DE LA REQUÊTE
                $apiResponseter = json_decode(file_get_contents($apiRequest), true);

                // echo "<pre>";

                // print_r(count($apiResponseter));

                // die();

                echo "<div class=\"showcasePanel\">\r\n";
                echo "<h1 class=\"numberShowcase\">".$apiResponsebis["value"][count($apiResponsebis["value"]) - 1]["value"]." Kg</h1>\r\n";
                // 

                $lat = $apiResponseter["value"][count($apiResponseter["value"]) - 1]["Lat"];
                $lon = $apiResponseter["value"][count($apiResponseter["value"]) - 1]["Lon"];

                echo "<span class=\"geographicCoords\">lon ".$lon."; lat ".$lat."</span><br>\r\n";
                echo "<a href=\"https://www.google.com/maps/@$lat,$lon,16z\" target=\"_blank\"><img src=\"https://static-maps.yandex.ru/1.x/?lang=en-US&ll=".$lon.",".$lat."&z=15&l=map&size=210,100&pt=".$lon.",".$lat.",vkgrm\" \></a><br>";
                echo "<button class=\"whiteButton disabled\">Selec. cette ruche</button>";
                echo "</div>\r\n";

            }
            echo "<div class=\"showcasePanelBlue\">\r\n";
            echo "<h2>Connected Behive</h2>\r\n";

            echo "<span>Interface Homme Machine</span>\r\n";
            echo "<span>par Louis Bertrand</span>\r\n";

            echo "</div>\r\n";
            echo "</div>\r\n";

        } else {
            redirect("login.php");
        }

    } else {
        redirect("login.php");
    }

    ?>
<title>EasyFly - home</title>
