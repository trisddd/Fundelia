<h1>
    <?= $business["name"] ?>
    <?php if ($business["SIREN"]):  ?>
        <svg id="certif-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <use href="/public/svg/certif_icon.svg#certif-icon"/>
        </svg>
    <?php endif; ?>
</h1>

<article>
    <header>
        <nav class="header-nav">
            <a onclick="switchFrame('/employees/<?= urlencode($business['name']) ?>')">Membres</a>
            <a onclick="switchFrame('/business_accounts/<?= urlencode($business['name']) ?>')">Comptes entreprise</a>
            <a onclick="switchFrame('/business_informations/<?= urlencode($business['name']) ?>')">Informations</a>
        </nav>
    </header>
    <iframe class="window" id="iframe-window" sandbox="allow-forms allow-scripts allow-same-origin allow-top-navigation" ></iframe>
</article>