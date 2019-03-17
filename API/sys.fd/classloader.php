<?php

    require_once "class/console.php";
    require_once "class/dateControle.php";
    require_once "class/dbJson.php";

    $console = $obj["console"] = new console;
    $dateCtl = $obj["dateController"] = new dateControle;
    $dbJson = $obj["dataBaseCommunicatorJson"] = new dbJson("DB", "*");