<?php
/* Initialise the Application */
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
if(strpos($url, "?") == true) {
    $url = strstr($url, '?', true);
}

/* Initialise Scripts */
if ($url == "view") {
    require("./Modules/Functions.php");
    require("./Public/view.php");
    return;
}
if ($url == "processForm") {
    require("./Modules/Functions.php");
    require("./Public/processForm.php");
    return;
}

/* Default Page */
if ($url == "") {
    require("./Modules/Functions.php");
    setupDatabase(); // Initialise Database
    require("./Public/index.php");
    return;
} else {
    if ($url == "404") {
        /* Not Found Page */
        require("./Modules/Functions.php");
        require("./Public/Error/404.html");
        return;
    } else {
        if ($url == "403") {
            /* Not Found Page */
            require("./Modules/Functions.php");
            require("./Public/Error/403.html");
            return;
        } else{
            if ($url == "500") {
                /* Server Error Page */
                require("./Modules/Functions.php");
                require("./Public/Error/500.html");
                return;
            } else {
                /* Not Found Page */
                require("./Modules/Functions.php");
                require("./Public/Error/404.html");
                return;
            }
        }
    }
}
?>