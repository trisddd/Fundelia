<?php

const CONTROLLERS_PATHS = array(
    // ERRORS
    "error_401" => "controllers/errors/error_401_controller.php",
    "error_403" => "controllers/errors/error_403_controller.php",
    "error_404" => "controllers/errors/error_404_controller.php",


    // COMMON
    "dashboard" => "controllers/common/dashboard_controller.php",
    "notifications" => "controllers/common/show_notifications_controller.php",
    "change_notification_state" => "controllers/common/change_notification_state_controller.php",
    "external_iban_verification" => "controllers/beneficiaries/iban_verification_and_adding.php",


    // AUTH
    "sign_up" => "controllers/auth/sign_up_controller.php",
    "sign_in" => "controllers/auth/sign_in_controller.php",
    "verify_password" => "controllers/auth/verify_password_controller.php",
    "verify_code" => "controllers/auth/verify_code_controller.php",
    "creation_of_customer_file" => "controllers/auth/creation_of_customer_file_controller.php",
    "insertion_of_customer_file" => "controllers/auth/insertion_of_customer_file_controller.php",


    // ACCOUNTS
    "show_accounts" => "controllers/accounts/show_accounts_and_passbooks_controller.php",
    "show_account_details" => "controllers/accounts/show_account_and_passbook_details_controller.php",
    "show_accounts_and_passbooks" => "controllers/accounts/show_accounts_and_passbooks_controller.php",
    "show_transaction_history" => "controllers/accounts/show_transaction_history_controller.php",
    "add_labels" => "controllers/accounts/add_labels_controller.php",
    "add_labels_to_transactions" => "controllers/accounts/add_labels_to_transactions_controller.php",
    "remove_labels_to_transactions" => "controllers/accounts/remove_labels_to_transactions_controller.php",
    "add_accounts_and_passbooks" => "controllers/accounts/add_accounts_and_passbooks_controller.php",
    "delete_accounts_and_passbooks" => "controllers/accounts/delete_accounts_and_passbooks_controller.php",
    "add_beneficiary" => "controllers/accounts/add_beneficiary_controller.php",
    "show_beneficiaries" => "controllers/accounts/show_beneficiaries_controller.php",


    // TRANSACTIONS
    'make_transfer' => "controllers/transactions/make_transfer_controller.php",
    'show_transfer_form' => "controllers/transactions/show_transfer_form_controller.php",
    "show_transfer_page" => "controllers/transactions/show_transfer_controller.php",
    "payement" => "controllers/api/payement_controller.php",
    "payement_form" => "controllers/api/payement_form_controller.php",


    // CARDS
    "show_cards_according_to_account" => "controllers/cards/show_cards_according_to_account_controller.php",
    "add_cards_to_account" => "controllers/cards/add_cards_to_account_controller.php",
    "delete_card" => "controllers/cards/delete_card_controller.php",
    "freeze_unfreeze_card" => "controllers/cards/freeze_unfreeze_card_controller.php",

    
    // BUSINESSES
    "business_homepage" => "controllers/businesses/show_business_homepage_controller.php",
    "add_business" => "controllers/businesses/add_business_controller.php",
    "create_business" => "controllers/businesses/create_business_controller.php",
    "check_business_infos" => "controllers/businesses/check_business_infos_controller.php",
    "update_API_key" => "controllers/businesses/update_API_key_controller.php",
    "business_accounts" => "controllers/businesses/show_business_accounts_controller.php",
    "create_business_account" => "controllers/businesses/create_business_account_controller.php",
    "business" => "controllers/businesses/show_business_controller.php",
    "update_business_account_owner" => "controllers/businesses/update_business_account_owner_controller.php",
    "business_account_details" => "controllers/businesses/show_business_account_details_controller.php",
    "delete_business_account" => "controllers/businesses/delete_business_account_controller.php",
    "employees" => "controllers/businesses/show_employees_controller.php",
    "add_employee" => "controllers/businesses/add_employee_controller.php",
    "create_employee" => "controllers/businesses/create_employee_controller.php",
    "business_informations" => "controllers/businesses/show_business_informations_controller.php",
    "delete_business" => "controllers/businesses/delete_business_controller.php",


    // API
    "make_payment" => "controllers/api/make_payment_controller.php",
    "bank_begining_transaction" => "controllers/api/bank_begining_transaction.php",
    "bank_mail_code_check_form" => "controllers/api/bank_mail_code_check_form_controller.php",
    "bank_mail_code_checking" => "controllers/api/bank_mail_code_checking.php",
    "transaction_error" => "controllers/api/transaction_error.php",
    "confirm_transaction" => "controllers/api/confirm_transaction.php",

    // SETTINGS
    "settings" => "controllers/settings/show_settings_controller.php",
    "disconnect" => "controllers/settings/disconnect_controller.php",
);

const DEFAULT_CONTROLLER_ROUTE = CONTROLLERS_PATHS["error_404"];
const DISCONNECTION_CONTROLLER_ROUTE = CONTROLLERS_PATHS["error_401"];