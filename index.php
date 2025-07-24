<?php

require_once "routes/controllers_routes.php";
require_once "routes/views_routes.php";
require_once "routes/special_pages.php";
require_once "utils/cleans.php";
require_once "services/startup_services.php"; // all services started at the beginning of a request

// dd($_GET);

if (empty($_GET)) {
    display("homepage", "Page d'accueil");
} else {
    $go = $_GET["go"] ?? null;
    $_GET["index"] = $_GET["index"] ?? null;
    
    if(!isset(CONTROLLERS_PATHS[$go])) {
        require_once DEFAULT_CONTROLLER_ROUTE;
    } else {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_secure' => false, // FIXME : Change to true when we are on https only
                'cookie_httponly' => true,
                'cookie_lifetime' => 0,
                'use_strict_mode' => true
            ]);
        }

        $session_timeout = 1440;

        if ((isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout && !in_array($go,PUBLIC_PAGES)) // session expired
            || (!isset($_SESSION["registered"])) && !in_array($go,PUBLIC_PAGES)  // Try to go to pages he don't have access as a random user
            || (isset($_SESSION["registered"]) && $_SESSION["registered"] == false && !in_array($go,NO_NAV_PAGES))) { // Try to go to pages he don't have access as a user which is trying to register
            $session = $_SESSION;
            session_unset();
            session_destroy();
            die(require_once DISCONNECTION_CONTROLLER_ROUTE);
        }

        $_SESSION['last_activity'] = time();

        require_once CONTROLLERS_PATHS[$go];
    }
}