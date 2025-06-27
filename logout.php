<?php
session_start();
session_unset();
session_destroy();

// include "cursor.php";
header("Location: login.php");
exit;
