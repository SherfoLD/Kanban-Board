<?php
session_start();
$_SESSION['user_id'] = null;
session_unset();
session_destroy();

header('Location: /kanban/authorization/login.php');
