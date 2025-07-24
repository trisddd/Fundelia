<?php

require_once "env.php";

$db = new PDO("mysql:host=localhost;dbname=fundelia;charset=utf8", $db_user, $db_pass);