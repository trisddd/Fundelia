<h1>Vos entreprises</h1>
<a href="/add_business">
    <section class="add-button">Faire une demande de création d'entreprise</section>
</a>
<article>
    <?php foreach ($businesses as $business):
        if ($business["is_verified"]): ?>
            <a href="/business/<?= urlencode($business["name"]) ?>">
                <section>
                    <h2>
                        <?= htmlspecialchars($business["name"]) ?>
                        <?php if ($business["SIREN"]):  ?>
                            <svg id="certif-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <use href="/public/svg/certif_icon.svg#certif-icon" />
                            </svg>
                        <?php endif; ?>
                    </h2>
                </section>
            </a>
        <?php else: ?>
            <section class="unverified">
                <h2>
                    <?= htmlspecialchars($business["name"]) ?>
                    <?php if ($business["SIREN"]):  ?>
                        <svg id="certif-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <use href="/public/svg/certif_icon.svg#certif-icon" />
                        </svg>
                    <?php endif; ?>
                </h2>
            </section>
    <?php endif;
    endforeach; ?>
</article>