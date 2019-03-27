<?php

    require_once 'sys.fd/kernel.php';

    $users = $dbJson->get("DB/users.json");
    $apiKeys = $dbJson->get("DB/apiKeys.json");
    $weight = $dbJson->get("DB/weight.json");

    // print_r($users);

    header('Content-Type: application/json');
    header('Content-Disposition:inline; filename="EasyFly API";');

    $get = $_GET;
    $nullarray = array( );

    if ($get != $nullarray) {

        if (isset($get['action'])) {

            //  [USAGE]
            //  <action> => "getUserName" & <uuid> => %uuid%
            if ($get['action'] == "getUserName") {

                if (isset($get['uuid'])) {

                    $search = $dbJson->execute("SELECT * from Users_Database WHERE uIdTag == ".$get['uuid']);
                    $result["value"] = $search[0]["uIdentifier"];
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "getUserAccess" & <uuid> => %uuid%
            elseif ($get['action'] == "getUserAccess") {

                if (isset($get['uuid'])) {

                    $search = $dbJson->execute("SELECT * from Users_Database WHERE uIdTag == ".$get['uuid']);
                    $result["value"] = $search[0]["access"];
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "getUserInfos" & <uuid> => %uuid%
            elseif ($get['action'] == "getUserInfos") {

                if (isset($get['uuid'])) {

                    $search = $dbJson->execute("SELECT * from Users_Database WHERE uIdTag == ".$get['uuid']);
                    $result["value"]["id"] = $search[0]["id"];
                    $result["value"]["uIdentifier"] = $search[0]["uIdentifier"];
                    $result["value"]["access"] = $search[0]["access"];
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "userTestConnection" & <uuid> => %uuid% & <pass> => %password%
            elseif ($get['action'] == "userTestConnection") {

                if (isset($get['uuid']) && isset($get["pass"])) {

                    $search = $dbJson->execute("SELECT * from Users_Database WHERE uIdTag == ".$get['uuid']." AND password p= ".$get['pass']);
                    $file = $dbJson->get("DB/users.json");

                    if (password_verify($get["pass"], $search[0]["password"])) {

                        $result["value"] = true;
                        $file[$search[0]["id"]]["password"] = password_hash($get["pass"], PASSWORD_BCRYPT);
                        $dbJson->updateFile($file, "DB/users.json");

                    } else {

                        $result["value"] = false;

                    }

                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "userUpdatePassword" & <uuid> => %uuid% & <oldPassword> => %oldPassword% & <newPassword> => %newPassword%
            elseif ($get['action'] == "userUpdatePassword") {

                if (isset($get['uuid']) && isset($get["oldPassword"]) && isset($get["newPassword"])) {

                    $search = $dbJson->execute("SELECT * from Users_Database WHERE uIdTag == ".$get['uuid']." AND password p= ".$get['pass']);
                    $file = $dbJson->get("DB/users.json");

                    if ( password_verify($get["password"], $search[0]["password"])) {

                        $result["value"] = true;
                        $file[$search[0]["id"]]["password"] = $get["newPassword"];
                        $dbJson->updateFile($file, "DB/users.json");

                    } else {

                        $result["value"] = false;

                    }

                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "registerAPIKey" & <prkey> => %prkey% & <regis> => %regis% & <accessLevel> = %accessLevel%
            //  regis = new apiKey to register
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "registerAPIKey") {

                if (isset($get['prkey']) && isset($get["regis"])) {

                    $access = 0x2345;
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE access == ".$access);
                    $file = $dbJson->get("DB/apiKeys.json");

                    // print_r($search)."\n";

                    $verified = false;

                    foreach ($search as $key => $val) {

                        if (password_verify($get["prkey"], $search[$key]["key"]) && !$verified) {
                            $verified = $result["value"] = "New Key Registered as ".$get["regis"];
    
                            if (!isset($get["type"])) {
                                $get["type"] = 0x1457;
                            }
    
                            $file["datas"][0]["key"] = password_hash($get["prkey"], PASSWORD_BCRYPT);
                            $file["datas"][count($apiKeys["datas"])]["key"] = password_hash($get["regis"], PASSWORD_BCRYPT);
                            $file["datas"][count($apiKeys["datas"])]["type"] = $get["type"];
                            $dbJson->updateFile($file, "DB/apiKeys.json");
                        } elseif(!password_verify($get["prkey"], $search[$key]["key"]) && !$verified) {
                            $result["value"] = false;
                        }

                    }
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "registerDataWeight" & <prkey> => %prkey% & <data> => %data%
            //  regis = new apiKey to register
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "registerDataWeight") {

                if (isset($get['prkey']) && isset($get["data"])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database Writing
                        $get["data"] = str_replace(" ", "_", $get["data"]);
                        $get["data"] = str_replace(",", ";", $get["data"]);
                        $search = $dbJson->execute("INSERT INTO Weight_Database (id,date,uuid,value) VALUES (NULL,".$dateCtl->dateSimplifier().",".password_hash($get['prkey'], PASSWORD_DEFAULT).",".$get["data"].")");
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "getDataWeight" & <prkey> => %prkey% & <max> => %max|null%
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "getDataWeight") {

                if (isset($get['prkey'])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database searching
                        if ($get["max"]) {
                            $max = $get["max"];
                        } else {
                            $max = null;
                        }
                        $search = $dbJson->execute("SELECT * from Weight_Database WHERE uuid p= ".$get['prkey']);
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            } 
            //  [USAGE]
            //  <action> => "registerDataWeight" & <prkey> => %prkey% & <data> => %data%
            //  regis = new apiKey to register
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "registerDataTemperature") {

                if (isset($get['prkey']) && isset($get["data"])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database Writing
                        $get["data"] = str_replace(" ", "_", $get["data"]);
                        $get["data"] = str_replace(",", ";", $get["data"]);
                        $search = $dbJson->execute("INSERT INTO Temperature_Database (id,date,uuid,value) VALUES (NULL,".$dateCtl->dateSimplifier().",".password_hash($get['prkey'], PASSWORD_DEFAULT).",".$get["data"].")");
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "getDataWeight" & <prkey> => %prkey% & <max> => %max|null%
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "getDataTemperature") {

                if (isset($get['prkey'])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database searching
                        if ($get["max"]) {
                            $max = $get["max"];
                        } else {
                            $max = null;
                        }
                        $search = $dbJson->execute("SELECT * from Temperature_Database WHERE uuid p= ".$get['prkey']);
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            } 
            //  [USAGE]
            //  <action> => "registerDataWeight" & <prkey> => %prkey% & <data> => %data%
            //  regis = new apiKey to register
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "registerDataGPS") {

                if (isset($get['prkey']) && isset($get["lat"]) && isset($get["lon"])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database Writing
                        $get["data"] = str_replace(" ", "_", $get["lat"]);
                        $get["data"] = str_replace(",", ";", $get["lat"]);
                        $get["data"] = str_replace(" ", "_", $get["lon"]);
                        $get["data"] = str_replace(",", ";", $get["lon"]);
                        $search = $dbJson->execute("INSERT INTO Gps_Database (id,date,uuid,Lat,Lon) VALUES (NULL,".$dateCtl->dateSimplifier().",".password_hash($get['prkey'], PASSWORD_DEFAULT).",".$get["lat"].",".$get["lon"].")");
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            }
            //  [USAGE]
            //  <action> => "getDataWeight" & <prkey> => %prkey% & <max> => %max|null%
            //  access Level = access Level for the key, base value = 0x1457
            elseif ($get['action'] == "getDataGPS") {

                if (isset($get['prkey'])) {

                    $search = array( );
                    $access = 0x1457;
                    // private key searching
                    $search = $dbJson->execute("SELECT * from Api_Keys WHERE type == ".$access." AND key p= ".$get['prkey']);

                    // private key testing
                    if ($search != array( )) {
                        // Database searching
                        if ($get["max"]) {
                            $max = $get["max"];
                        } else {
                            $max = null;
                        }
                        $search = $dbJson->execute("SELECT * from Gps_Database WHERE uuid p= ".$get['prkey']);
                        $result["value"] = $search;
                    } else {
                        // return a FATAL ERROR
                        $result["value"] = "FATAL ERROR: NO KEY FOUND WITH ".$get['prkey'];
                    }
                    
                    $result["trace"]["action"] = $get['action'];
                    $result["trace"]["uuid"] = $get['uuid'];

                }

            } else {
                $result["value"] = "API VERSION: V1.0.0";
            }

        } else {
            $result["value"] = "API VERSION: V1.0.0";
        }

    } else {
        $result["value"] = "API VERSION: V1.0.0";
    }
    $result["trace"]["time"] = date("c", time());
    $result["trace"]["id"] = md5(time().rand(0, 100));
    echo json_encode($result, JSON_PRETTY_PRINT);
