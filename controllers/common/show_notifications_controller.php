<?php
require_once "models/users_sql_requests.php";
$user_id = $_SESSION['user_id'];
$notifications = read_all_notifications_by_user($user_id);
display("show_notifications", "notifications");
