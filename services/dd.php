<?php
/**
 * Some data you want to fast dump
 * @param Any $data some datas
 */
function dd($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}