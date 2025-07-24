<?php

const VIEWS_PATHS = array(
    // ACCOUNTS
    "add_accounts"=>"views/accounts/add_accounts_view.php",
    "show_account_details"=>"views/accounts/show_account_details_view.php",
    "show_accounts"=>"views/accounts/show_accounts_view.php",
    "show_transaction_history"=>"views/accounts/show_transaction_history_view.php",
    "add_labels"=>"views/accounts/add_labels_view.php",
    "add_beneficiary"=>"views/accounts/add_beneficiary_view.php",
    "show_beneficiaries"=>"views/accounts/show_beneficiaries_view.php",

    // AUTHENTIFICATION
    "creation_of_customer_file"=>"views/auth/creation_of_customer_file_view.php",
    "insertion_of_customer_file"=>"views/auth/insertion_of_customer_file_view.php",
    "sign_in"=>"views/auth/sign_in_view.php",
    "sign_up"=>"views/auth/sign_up_view.php",
    "verify_password"=>"views/auth/verify_password_view.php",

    // CARDS
    "add_card_to_account"=>"views/cards/add_card_to_account_view.php",
    "show_cards"=>"views/cards/show_cards_view.php",

    // COMMON
    "dashboard"=>"views/common/dashboard_view.php",
    "show_notifications"=>"views/common/show_notifications_view.php",
    "homepage"=>"views/common/homepage_view.php",

    // ERRORS
    "error_401"=>"views/errors/error_401.php",
    "error_403"=>"views/errors/error_403.php",
    "error_404"=>"views/errors/error_404.php",

    // TRANSACTIONS
    "show_transfer_form"=>"views/transactions/show_transfer_form_view.php",
    "bank_mail_code_check_form" => "views/transactions/bank_mail_code_check_form_view.php",
    "show_transfer_page" => "views/transactions/show_transfer_view.php",
    "payement_form" => "views/transactions/payement_form_view.php",

    // BUSINESSES
    "create_business_account"=>"views/businesses/create_business_account_view.php",
    "add_business"=>"views/businesses/add_business_view.php",
    "create_business"=>"views/businesses/create_business_view.php",
    "check_business_infos"=>"views/businesses/check_business_infos_view.php",
    "show_business_accounts"=>"views/businesses/show_business_accounts_view.php",
    "show_business_homepage"=>"views/businesses/show_business_homepage_view.php",
    "update_API_key"=>"views/businesses/update_API_key_view.php",
    "show_business"=>"views/businesses/show_business_view.php",
    "update_business_account_owner" => "views/businesses/update_business_account_owner_view.php",
    "show_business_account_details" => "views/businesses/show_business_account_details_view.php",
    "show_employees" => "views/businesses/show_employees_view.php",
    "show_business_informations" => "views/businesses/show_business_informations_view.php",
    "add_employee" => "views/businesses/add_employee_view.php",

    // SETTINGS
    "show_settings" => "views/settings/show_settings_view.php",
);

const DEFAULT_VIEW_ROUTE = VIEWS_PATHS["error_404"];