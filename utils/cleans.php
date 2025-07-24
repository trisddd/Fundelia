<?php

/**
 * Clean string that user send to protect from injections
 * @param String $string the string you want to clean
 * @return String the string cleaned
 */
function clean_string($string){
    return htmlspecialchars(trim($string));
}