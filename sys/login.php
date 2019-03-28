<?php

    function redirect($url)
    {
        header('Location: '.$url);
    }
    session_start();

    $apiUrl = "http://rucheconnapi.free.fr/API";
    if (!$_SESSION["CONN_Uuid"] && !$_SESSION["CONN_Upassword"]) {

        $nullarray = array();

        if ($_POST != $nullarray) {

            $get["uuid"] = md5($_POST["uuid"]);
            $get["password"] = sha1($_POST["password"]);


            $apiRequest = "$apiUrl/?action=userTestConnection&uuid=%uuid%&password=%password%";
            $apiRequest = str_replace("%uuid%", $get["uuid"], $apiRequest);
            $apiRequest = str_replace("%password%", $get["password"], $apiRequest);
            // echo $apiRequest;
            $apiResponse = json_decode(file_get_contents($apiRequest), true);
            // var_dump($apiResponse); 

            if ($apiResponse["value"] == true || $apiResponse["value"] == "true") {

                $_SESSION["CONN_Uuid"] = $get["uuid"];
                $_SESSION["CONN_Upassword"] = $get["password"];

                redirect("index.php");

            } else {

                echo "<div class=\"error\">Erreur: L'identifiant ou le mot de passe n'est pas valid</div><br><br>";

            }

        }

        echo "<form action=\"\" method=\"post\">\n<input type=\"text\" name=\"uuid\"><br>\n<input type=\"password\" name=\"password\"><br><input type=\"submit\"> ";

    } else {
        redirect("logout.php");
    }

?>
<title>EasyFly - Login</title>