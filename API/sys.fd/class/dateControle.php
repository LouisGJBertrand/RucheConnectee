<?php

    class dateControle extends console
    {

        public function dateSimplifier(int $time = null)
        {

            if ($time == null) {

                $time = time();

            }

            $date = date("Y-m-d\TH:i:00+00:00", $time);
            $newTime = strtotime($date);

            return $newTime;

        }

    }