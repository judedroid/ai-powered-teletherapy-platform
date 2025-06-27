<?php
session_start();

// Load selected language or default to English
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'en');
$_SESSION['lang'] = $lang;

// Load the corresponding language file
$translations = include __DIR__ . "/lang/$lang.php";
?>
