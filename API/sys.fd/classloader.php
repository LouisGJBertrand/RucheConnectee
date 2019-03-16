<?php

    require_once "class/console.php";
    require_once "class/dbJson.php";

    $console = $obj["console"] = new console;
    $dbJson = $obj["dataBaseCommunicatorJson"] = new dbJson("DB", "*");