<?php
/* Initialise the Application */
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
$url = strstr($url, '?', true);

/* Initialise Displays */
if ($url == "") {
    /* Primary Display Page */
    require("./Modules/Functions.php");
    setupDatabase(); // Initialise Database
    require("./Public/index.php");
    return;
} else {
    if ($url == "view") {
        /* View Message Page */
        require("./Modules/Functions.php");
        require("./Public/view.php");
        return;
    } else {
        if ($url == "processForm") {
            /* Form Submission Handler */
            require("./Modules/Functions.php");
            require("./Public/processForm.php");
            return;
        } else {
            if ($url == "404") {
                /* Not Found Page */
                require("./Modules/Functions.php");
                require("./Public/Error/404.html");
                return;
            } else {
                if ($url == "403") {
                    /* No Permission Page */
                    require("./Modules/Functions.php");
                    require("./Public/Error/403.html");
                    return;
                } else {
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
    }
}
?>