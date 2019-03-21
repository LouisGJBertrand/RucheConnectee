<?php

    class dbJson extends console
    {

        public function __construct($DB_PATH, $FILES = "*") {

            $this->DB_PATH = $DB_PATH;

            if ($FILES == "*"){

                $FILES = scandir($DB_PATH);

            }

            foreach ($FILES as $key => $value) {

                if (strpos($value, ".json")) {
                    $step1 = $this->get($this->DB_PATH."/".$value);
                    $DB_FILES_PATHS[$step1["settings"]["fileName"]] = $this->DB_PATH."/".$value;
                    $DB_FILES[$step1["settings"]["fileName"]] = $step1["datas"];
                }

            }

            $this->DB_FILES = $DB_FILES;
            $this->DB_FILES_PATHS = $DB_FILES_PATHS;

        }

        public function get($path) {

            if (is_string($path)) {

                return json_decode(file_get_contents($path), true);

            }

        }

        public function execute($query)
        {

            if(is_string($query)){

                $query = " ".$query;
                $result = array();

                if (strpos($query, "SELECT", $offset) == 1) {
                    
                    $action = "SELECT";

                } elseif (strpos($query, "INSERT", $offset) == 1) {
                    
                    $action = "INSERT";

                } elseif (strpos($query, "UPDATE", $offset) == 1) {
                    
                    $action = "UPDATE";

                }

                $returns["action"] = $action;

                $LogicOperations = ["AND", "OR"];

                foreach ($LogicOperations as $key => $value) {

                    if (substr_count($query, $value) > 0) {
                    
                        $offset = 0;
                        $max = substr_count($query, $value);
                        $a = 0;
    
                        while(strpos($query, $value, $offset) && $a < $max){
    
                            $operators[intval(strpos($query, $value, $offset))] = array("operations" => $value);
                            $offset = strpos($query, $value, $offset) + 3;
                            $a++;
    
                        }
    
                        $returns["operators"] = $operators;
    
                    }

                }

                $TestOperations = ["=", ">=", "<=", "<", ">", "p=", "!="];

                foreach ($TestOperations as $key => $value) {

                    if (substr_count($query, $value) > 0) {
                    
                        $offset = 0;
                        $max = substr_count($query, $value);
                        $a = 0;
    
                        while(strpos($query, $value, $offset) && $a < $max){
    
                            $Testoperators[intval(strpos($query, $value, $offset))] = array("operations" => $value);
                            $offset = strpos($query, $value, $offset) + 3;
                            $a++;
    
                        }
    
                        $returns["Testoperators"] = $Testoperators;
    
                    }

                }



                ksort($returns["operators"]);
                ksort($returns["Testoperators"]);

                return $returns;

            }
            else {

                return "FATAL ERROR: NOT A STRING GIVEN FOR QUERY";

            }

        }

        public function queryTester($value1, $value2, $comparator)
        {

            if ($comparator === "=") {
                if ($value1 == $value2) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($comparator === ">=") {
                if ($value1 >= $value2) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($comparator === "<=") {
                if ($value1 <= $value2) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($comparator === ">") {
                if ($value1 > $value2) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($comparator === "<") {
                if ($value1 < $value2) {
                    return true;
                } else {
                    return false;
                }
            }
            // WARNING: NOT A STANDARD TEST
            //          DO NOT USE IN NORMAL TESTS
            elseif ($comparator === "p=") {
                if (password_verify($value1,$value2)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }

        public function updateFile(array $array, $path)
        {

            $file = json_decode(file_get_contents( $path) , true );

            $newfile["settings"] = $file["settings"];
            $newfile["settings"]["lastUpdated"] = date("c");
            $newfile["datas"] = $array;

            // print_r($newfile);

            // return false;

            if (is_string($path)) {

                $dataWeight = file_put_contents("tmp/data.json", json_encode($array[count($array) - 1], JSON_PRETTY_PRINT));
                $return["fileWeight"] = file_put_contents($path, json_encode($newfile, JSON_PRETTY_PRINT));
                $return["dataWeight"] = $dataWeight;
                $return["LineInserted"] = count($array[count($array) - 1]);
                return $return;

            }

            return false . " : Failure";

        }

    }