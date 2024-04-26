<?php
session_id($_SESSION['uid']);
session_start();
session_destroy();
header("Location: login.php");
?>