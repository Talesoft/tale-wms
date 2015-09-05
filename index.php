<?php

use Tale\App;

include 'vendor/autoload.php';


//The first parameter to App can actually be omitted completely,
//if the app-directory is ./app from here!
//Even though, all sub paths (meaning, ALL sub-paths) will
//be relative then.
$app = new App([
    'path' =>__DIR__.'/app',
]);

$app->run();