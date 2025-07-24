<?php

require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";
require_once "models/banks_sql_requests.php";

// Vérifier que la méthode est POST et que des données existent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

  // Récupération et sécurisation des données
  $token = htmlspecialchars(trim($_POST['token'] ?? ''));
  $order_amount = htmlspecialchars(trim($_POST['order_amount'] ?? ''));
  $order_id = htmlspecialchars(trim($_POST['order_id'] ?? ''));
  $redirect_url = filter_var($_POST['redirect_url'] ?? '', FILTER_SANITIZE_URL);
  $h1 = htmlspecialchars(trim($_POST['h1'] ?? ''));

  // Vérification que tous les champs sont remplis
  if (!$token || !$order_amount || !$order_id || !$redirect_url || !$h1) {
    header('Location: /transaction_error?error=Champs%20requis%20manquants');
    exit;
  }

  // Lecture des tokens depuis le fichier
  $tokens_file = 'controllers/api/business_bank_tokens.json';
  $tokens = file_exists($tokens_file)
    ? json_decode(file_get_contents($tokens_file), true)
    : [];

  // Nettoyage des tokens expirés (15 minutes)
  foreach ($tokens as $key => $data) {
    if (time() - ($data['timestamp'] ?? 0) > 900) {
      unset($tokens[$key]);
    }
  }

  // Vérification du token
  if (!isset($tokens[$token])) {
    header('Location: /transaction_error?error=Token%20inconnu');
    exit;
  }

  // Vérification et récupération des infos de compte
  $account = h1_check($h1);
  if (!$account || !isset($account['IBAN'], $account['name'])) {
    header('Location: /transaction_error?error=Compte%20introuvable');
    exit;
  }

  $business_iban = htmlspecialchars($account['IBAN']);
  $business_name = htmlspecialchars($account['name']);

  // Stockage dans la session (évite de stocker les infos sensibles inutiles)
  $_SESSION['order_amount'] = $order_amount;
  $_SESSION['order_id'] = $order_id;
  $_SESSION['redirect_url'] = $redirect_url;
  $_SESSION['business_iban'] = $business_iban;
  $_SESSION['business_name'] = $business_name;

  // Redirection vers le formulaire de paiement
  header('Location: /payement_form');
  exit;
}
