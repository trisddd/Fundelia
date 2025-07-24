<?php

// Stocker les données dans la session au lieu de les afficher dans le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
    $_SESSION['token'] = $_POST['token'] ?? '';
  
}

display("bank_mail_code_check_form","Vérifiez votre code");
