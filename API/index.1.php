<?php

    require_once 'sys.fd/kernel.1.php';

    $users = $dbJson->get("DB/users.json");
    $apiKeys = $dbJson->get("DB/apiKeys.json");
    $weight = $dbJson->get("DB/weight.json");

    // print_r($users);

    header('Content-Type: application/json');
    header('Content-Disposition:inline; filename="EasyFly API";');

    $get = $_GET;
    $nullarray = array( );

    $search = $dbJson->execute("SELECT * from Users_Database WHERE id = 0 AND OR uIdTag = 5ad34a45215e35afb109c54d196bd742 AND ok");
    $result["value"] = $search;
    $result["trace"]["time"] = date("c", time());
    $result["trace"]["id"] = md5(time().rand(0, 100));
    echo json_encode($result, JSON_PRETTY_PRINT);
