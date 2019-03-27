<?php

    header("Content-type: text/plain");

    // phpinfo();

    define("serverName", "sql.free.fr");
    define("DBUser", "rucheconnapi");
    define("DBPass", "GvtrWc2Z9JiI1an");
    define("DBBase", DBUser);

    // $host = "mysql:host=".serverName.";dbname=".DBBase;

    echo serverName."\r\n".DBUser."\r\n".DBPass."\r\n".DBBase."\r\n";
    $conn = new mysqli(serverName, DBUser, DBPass, DBBase);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    echo "Connected successfully";