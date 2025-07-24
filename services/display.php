<?php


/**
 * Display a view with the good informations
 * @param String $view_name the name of the view (and not the path) => should be registered in VIEWS_PATHS
 * @param String $title the title of the view
 */
function display($view_name, $title) {
    extract($GLOBALS);
    $is_iframe = $_GET["iframe"] ?? null;
    if ($is_iframe == true) {
        require_once "views/layout_iframe.php";
    } else {
        require_once "views/layout.php";
    }
}