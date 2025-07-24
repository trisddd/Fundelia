<section class="card">
    <h1>Remplissez les informations de l'entreprise</h1>
    <form action="/check_business_infos" method="post" id="real-business">
        <h2>Entreprise avec SIREN</h2>
        <label for="siren">SIREN (9 chiffres attendus)</label>
        <input type="text" name="SIREN" id="siren" class="value" pattern="\d{3} \d{3} \d{3}" maxlength="9" required>
        <label for="email-real">E-mail de l'entreprise</label>
        <input type="email" name="email" id="email-real" class="value" required>
        <input type="hidden" name="business_type" value="real_business">
        <input type="submit" class="submit">
        <button id="switch-to-new">Inscrire une entreprise sans SIREN</button>
    </form>
    <form action="/check_business_infos" method="post" id="new-business" class="disabled">
        <h2>Entreprise sans SIREN</h2>
        <label for="name">Nom de l'entreprise</label>
        <input type="text" name="name" id="name" class="value" maxlength="255" required>
        <label for="email-new">E-mail de l'entreprise</label>
        <input type="email" name="email" id="email-new" class="value" required>
        <input type="hidden" name="business_type" value="new_business">
        <input type="submit" class="submit">
        <button id="switch-to-real">Inscrire une entreprise avec SIREN</button>
    </form>
</section>