<?php

    class console
    {

        public function log($log)
        {

            echo("<script>console.log(\"");
            print_r($log);
            echo("\")</script>");

        }

    }
    