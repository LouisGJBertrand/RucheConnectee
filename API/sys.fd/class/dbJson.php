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

                $result = array();

                $treatement = explode(" ", $query);

                if ($treatement[0] == "SELECT") {

                    if ($treatement[1] == "*") {

                        if ($treatement[2] == "from") {

                            if (isset($this->DB_FILES[$treatement[3]])) {

                                $file = $this->DB_FILES[$treatement[3]];

                            } else {

                                return "FATAL ERROR: THIS DATABASE DOES NOT EXISTS";

                            }

                        }
                        if ($treatement[count($treatement)-2] == "MAX") {

                            $max = intval($treatement[count($treatement)-1]);

                        }
                        if ($treatement[4] == "WHERE") {

                            $a = 0;
                            foreach ($file as $key => $value) {

                                if (isset($treatement[8]) && $treatement[8] == "AND") {

                                    if($this->queryTester($treatement[7], $value[$treatement[5]], $treatement[6]) && $this->queryTester($treatement[11], $value[$treatement[9]], $treatement[10])){

                                        $result[] = $value;

                                    }

                                } elseif (isset($treatement[8]) && $treatement[8] == "OR") {

                                    if($this->queryTester($treatement[7], $value[$treatement[5]], $treatement[6]) || $this->queryTester($treatement[11], $value[$treatement[9]], $treatement[10])){

                                        $result[] = $value;

                                    }

                                } else {

                                    if($this->queryTester($treatement[7], $value[$treatement[5]], $treatement[6])){

                                        $result[] = $value;

                                    }
                                }
                            }

                            return $result;

                        } elseif (!isset($treatement[4])){

                            return $file;

                        }
                        else {

                            $error[] = "FATAL ERROR: MISSING ARGUMENTS AT WORD POSITION 5 (MUST BE WHERE OR NOTHING)";

                        }

                    } else {

                        $error[] = "FATAL ERROR: MISSING ARGUMENTS AT WORD POSITION 2 (MUST BE *)";

                    }

                    if (is_array($error)) {
                        return $error;
                    }

                }
                // INSERT METHOD
                elseif ($treatement[0] == "INSERT") {

                    if ($treatement[1] == "INTO") {

                        if (isset($this->DB_FILES[$treatement[2]])) {

                            $file = $this->DB_FILES[$treatement[2]];

                        } else {

                            print_r($this->DB_FILES);
                            return "FATAL ERROR: THIS DATABASE DOES NOT EXISTS";

                        }

                        $colomns = explode(" ", str_replace(",", " ", str_replace("(", "", str_replace(")", "", $treatement[3]))));

                        if ($treatement[4] == "VALUES") {

                            $values = explode(" ", str_replace(",", " ", str_replace("(", "", str_replace(")", "", $treatement[5]))));

                        } else {

                            return "FATAL ERROR: MISSING VALUES";

                        }

                    } else {

                        return "FATAL ERROR: MISSING INTO";

                    }

                    foreach ($colomns as $key => $val) {

                        if ($values[$key] == "NULL") {
                            $values[$key] = count($file);
                        }
                        $inserts[$val] = $values[$key];

                    }

                    $file[] = $inserts;

                    return $this->updateFile($file, $this->DB_FILES_PATHS[$treatement[2]]);
                    // return $file;

                }
            }
            else {

                return "FATAL ERROR: NOT A STRING GIVEN FOR QUERY";

            }

        }

        public function queryTester($value1, $value2, $comparator)
        {

            if ($comparator === "==") {
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