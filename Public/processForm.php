<?php
/* Process the Data */
header("Location: ./?submitted=" . processData($_POST["data"]));
?>