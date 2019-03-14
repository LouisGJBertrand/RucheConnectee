<?php

    class dbJson
    {

        public function get($path) {

            if (is_string($path)) {

                return json_decode(file_get_contents($path), true);

            }

        }

        public function selectEquals(array $array, $key, $value, $test = "==", $max = 0)
        {

            $keys = array_keys($array);

            $result = 0;
            $a = 0;
            while ($a < count($array)) {

                if ($test === "==") {
                    if ($array[$keys[$a]][$key] == $value) {
                        $results[] = $array[$keys[$a]];
                        $result++;
                    }
                }elseif ($test === ">") {
                    if ($array[$keys[$a]][$key] > $value) {
                        $results[] = $array[$keys[$a]];
                        $result++;
                    }
                }elseif ($test === ">=") {
                    if ($array[$keys[$a]][$key] >= $value) {
                        $results[] = $array[$keys[$a]];
                        $result++;
                    }
                }elseif ($test === "<") {
                    if ($array[$keys[$a]][$key] < $value) {
                        $results[] = $array[$keys[$a]];
                        $result++;
                    }
                }elseif ($test === "<=") {
                    if ($array[$keys[$a]][$key] <= $value) {
                        $results[] = $array[$keys[$a]];
                        $result++;
                    }
                }

                if ($max > 0 && ($max - 1) < $result) {
                    $a = count($array);
                }

                $a++;

            }

            return $results;

        }

        public function updateFile(array $array, $path)
        {

            if (is_string($path)) {

                file_put_contents($path, json_encode($array, JSON_PRETTY_PRINT));

            }

        }

    }