<aside class="sidebar">
    <!-- TODO: Faire en sorte que le glidder reste sur l'élément de la navbar correspondant à la page  -->
    <!-- TODO: Changer tous les icons avec les fonctions bizarres en vrai svg (voir business)  -->
    <nav>
        <div class="sidebar-logo">
        <img src="/public/icons/logo.png" alt="" class="sidebar-logo-image">
        </div>
        <ul class="sidebar-nav">
            <div class="active-indicator"></div>
            <li class="active">
                <a href="/dashboard">
                    <div class="sidebar-icon">
                        <?php include_once 'public/svg/home_icon.php'; ?>
                        <?php home_icon(''); ?>
                    </div>
                    <span>Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="/notifications">
                    <div class="sidebar-icon">
                    <?php include_once 'public/svg/bell_icon.php'; ?>
                    <?php bell_icon(''); ?>
                    </div>
                    <span>Notifications</span>
                </a>
            </li>
            <li>
                <a href="/show_transaction_history">
                    <div class="sidebar-icon">
                    <?php include_once 'public/svg/clock_history_icon.php'; ?>
                    <?php clock_history_icon(''); ?>                
                    </div>
                    <span>Historique</span>
                </a>  
            </li>
            <li>
                <a href="/show_transfer_page">
                    <div class="sidebar-icon">
                        <?php include_once 'public/svg/arrow_left_right_icon.php'; ?>
                        <?php arrow_left_right_icon(''); ?>
                    </div>
                    <span>Virements</span>
                </a>
            </li>
            <li>
                <a href="/show_cards_according_to_account">
                    <div class="sidebar-icon">
                        <?php include_once 'public/svg/credit_card_icon.php'; ?>
                        <?php credit_card_icon(''); ?>
                    </div>
                    <span>Cartes</span>
                </a>
            </li>
            <li>
                <a href="/show_accounts_and_passbooks">
                    <div class="sidebar-icon">
                        <?php include_once 'public/svg/bank_icon.php'; ?>
                        <?php bank_icon(''); ?>
                    </div>
                    <span>Comptes & Livrets</span>
                </a>
            </li>
            <li>
                <a href="/business_homepage">
                    <div class="sidebar-icon">
                        <svg viewBox="0 0 422.211 422.211" xmlns="http://www.w3.org/2000/svg">
                            <use href="/public/svg/business_icon.svg#business-icon"/>
                        </svg>
                    </div>
                    <span>Entreprises</span>
                </a>
            </li>
            <li>
                <a href="/settings">
                    <div class="sidebar-icon">
                        <?php include_once 'public/svg/sliders_icon.php'; ?>
                        <?php sliders_icon(''); ?>
                    </div>
                    <span>Paramètres</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
