<article class="wrapper">
  <h2>Informations de l'entreprise</h2>

  <section class="field-group">
    <div class="label">Nom</div>
    <div class="value">
      <?= htmlspecialchars($business["name"]) ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">SIREN</div>
    <div class="value">
      <?php if ($business["SIREN"] != null) {
        echo htmlspecialchars($business["SIREN"]);
       } else {
        echo "Cette entreprise n'a pas de SIREN enregistré.";
       } ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">E-mail</div>
    <div class="value">
      <?= htmlspecialchars($business["email"]) ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">Date de création</div>
    <div class="value">
      <time datetime="<?= htmlspecialchars($creation_date) ?>">
        <?= htmlspecialchars($creation_date) ?>
      </time>
    </div>
  </section>

  <form method="POST" action="/delete_business/<?= urlencode($business_name) ?>">
    <button class="btn btn-delete">Supprimer l'entreprise</button>
  </form>
</article>