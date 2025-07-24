<?php
$user_id = $_SESSION["user_id"];

require_once "models/business_user_sql_requests.php";
require_once "models/businesses_sql_requests.php";

$businesses = read_all_businesses_informations_of_user($user_id);

display("show_business_homepage","Entreprises");