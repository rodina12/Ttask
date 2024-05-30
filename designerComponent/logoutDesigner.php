<?php

include '../sharedComponents/connect.php';

session_start();
session_unset();
session_destroy();

header('location:loginDesigner.php');

?>