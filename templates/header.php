<?php
session_start();
if (!$_SESSION['user_id'])
    header('Location: ../authorization/login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/css/styles.css">
    <title>Your Website</title>
</head>
<body>
<header>
    <h1>Your Website</h1>
</header>
<main>