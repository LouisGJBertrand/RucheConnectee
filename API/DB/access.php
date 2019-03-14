<?php

    require_once '../sys.fd/kernel.php';

    $filename = "access.json";

    // settings

    $file["settings"]["method"] = "JSONDataBase";
    $file["settings"]["fileName"] = "Access Database";
    $file["settings"]["creator"] = "Louis Bertrand";
    $file["settings"]["normeMethod"] = "PY-0001-JSONDataBase";
    $file["settings"]["MysqlCompatible"] = true;
    $file["settings"]["encoding"] = "UTF-8";
    $file["settings"]["isoEncoding"] = "ISO/CEI 10646";
    $file["settings"]["headers"] = array("id", "code", "displayName", "alias");
    $file["settings"]["lastUpdated"] = date('c', time());

    // datas

    $file["datas"][0]["id"] = 0;
    $file["datas"][0]["code"] = 0xffff;
    $file["datas"][0]["displayName"] = "SuperAdmin";
    $file["datas"][0]["alias"] = "*";

    $file["datas"][1]["id"] = 0;
    $file["datas"][1]["code"] = 0x0001;
    $file["datas"][1]["displayName"] = "Agent de maintenance";
    $file["datas"][1]["alias"] = "adm";

    $file["datas"][2]["id"] = 0;
    $file["datas"][2]["code"] = 0xff00;
    $file["datas"][2]["displayName"] = "Intervenant Réseau";
    $file["datas"][2]["alias"] = "ir";

    $file["datas"][3]["id"] = 0;
    $file["datas"][3]["code"] = 0x4000;
    $file["datas"][3]["displayName"] = "Responsable de Vols";
    $file["datas"][3]["alias"] = "rdv";

    $dbJson->updateFile($file, $filename);