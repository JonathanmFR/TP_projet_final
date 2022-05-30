<?php
session_start();
if($_SESSION["email"]) header("Location: blog.php");
header("Location: loginpage.php")
?>