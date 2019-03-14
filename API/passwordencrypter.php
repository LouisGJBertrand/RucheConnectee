<pre><?php

    $pass = "Controbojionastikov";
    $pass = sha1($pass);
    // 1c52d5c2f1bb27eaabf306701212b1a28cf79dcc
    echo password_hash($pass, PASSWORD_BCRYPT);