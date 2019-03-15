<?php

    class dbJson
    {

        public function get($path) {

            if (is_string($path)) {

                return json_decode(file_get_contents($path), true);

            }

        }

        public function execute(array $file, string $query)
        {

            $keys = array_keys($file);

            $result = array();

            $treatement = explode(" ", $query);

            if ($treatement[0] == "SELECT") {

                if ($treatement[1] == "*") {

                    if ($treatement[2] == "WHERE") {

                        foreach ($file as $key => $value) {

                            if ($treatement[6] == "AND") {

                                if($this->queryTester($value[$treatement[3]], $treatement[5], $treatement[4]) && $this->queryTester($value[$treatement[7]], $treatement[9], $treatement[8])){

                                    $result[] = $value;

                                }

                            }

                            if ($treatement[6] == "OR") {

                                if($this->queryTester($value[$treatement[3]], $treatement[5], $treatement[4]) || $this->queryTester($value[$treatement[7]], $treatement[9], $treatement[8])){

                                    $result[] = $value;

                                }

                            } else {

                                if($this->queryTester($value[$treatement[3]], $treatement[5], $treatement[4])){

                                    $result[] = $value;

                                }
                            }

                        }

                    }

                } else {

                    $error[] = "use only *";

                }

            }

            return $result;

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
            elseif ($comparator === "#=") {
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

            if (is_string($path)) {

                file_put_contents($path, json_encode($array, JSON_PRETTY_PRINT));

            }

        }

    }