<?php

// These pages they don't need to have the sidebar and accessible when you try to log in
const NO_NAV_PAGES = [
    "homepage",
    "creation_of_customer_file",
    "insertion_of_customer_file",
    "sign_in",
    "sign_up",
    "verify_password",
    "bank_mail_code_check_form",
    "error_404",
    "error_403",
    "error_401",
    "payement",
    "payement_form"
];

// These pages are accessible without log in
const PUBLIC_PAGES = [
    "homepage",
    "sign_in",
    "sign_up",
    "verify_password",
    "error_404",
    "error_403",
    "error_401",
    "make_payment",
    "bank_begining_transaction",
    "bank_mail_code_check_form",
    "bank_mail_code_checking",
    "transaction_error",
    "confirm_transaction",
    "payement",
    "payement_form"
];