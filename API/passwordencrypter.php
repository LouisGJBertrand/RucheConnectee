<?php

    $pass = "Controbojionastikov";
    $pass = sha1($pass);
    echo $pass."\r\n";
    // 1c52d5c2f1bb27eaabf306701212b1a28cf79dcc
    echo password_hash($pass, PASSWORD_BCRYPT)."\r\n";
    echo dechex(9029);

    echo "\r\n";
    echo "\r\n";

    //       Exptn PT1 PRT2
    //       NAM##_UID_UID2
    $pass = "TST00_000_A001";
    $pass = sha1($pass);
    echo $pass."\r\n";
    // 699d0dd2eb6b939f21f41b3bbf8c5d093fddcc99
    echo password_hash($pass, PASSWORD_BCRYPT)."\r\n";
    echo hexdec(1457);

    echo "\r\n";
    echo "\r\n";

    $pass = "SuperAdminDeluxe";
    $pass = md5($pass);
    echo $pass."\r\n";
    $pass = "SuperAdminDeluxe";
    $pass = sha1($pass);
    echo $pass."\r\n";
    // b2f8b98c04e5d02ace9ff62726e857e720398c93
    echo password_hash($pass, PASSWORD_BCRYPT)."\r\n";
    echo hexdec(1457);