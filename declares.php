<?php
$defaultTimeZone='America/Chicago';
if(date_default_timezone_get()!=$defaultTimeZone) { date_default_timezone_set($defaultTimeZone); }

function __autoload($class_name)
{
    //class directories
    $directorys = array(
        'controllers/',
        'models/',
        'views/',
        'routers/',
        'templates/',
        'components/',
        'assets/',
        'classes/',
        'classes/lists/'
    );

    //for each directory
    foreach($directorys as $directory)
    {
        //see if the file exsists
        if(file_exists($directory.$class_name . '.php'))
        {
            require($directory.$class_name . '.php');
            //only require the class once, so quit after to save effort (if you got more, then name them something else
            return;
        }           
    }
}