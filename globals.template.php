<?php
    date_default_timezone_set("America/Sao_Paulo");//APP DEFAULT TIME ZONE
    define('APP_ROOT',__DIR__);
    define('APP_NAME', "APP NAME");
    define('APP_URL' , 'YOUR APP URL');
    define('APP_SECRET','YOUR APP KEY');
    define('APP_CONFIG',array(
    "Database" => [
        'servername' => "localhost",
        'dbname' => 'gradehorarios',
        'tablePrefix'=>'',
        'username'=>'',
        'password' =>''
    ]
    ));
?>