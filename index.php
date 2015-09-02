<?php

use Tale\App;

include 'vendor/autoload.php';


//The first parameter to App can actually be omitted completely,
//if the app-directory is ./app from here!
$app = new App([
    'path' =>__DIR__.'/app'
]);

var_dump($app);
