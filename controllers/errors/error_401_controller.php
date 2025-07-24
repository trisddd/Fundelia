<?php 
if($session == array() || array_keys($session)===["last_activity"]) {
    display("error_401", "Non Connecté");
} else {
    display("error_401", "Déconnecté");
}
