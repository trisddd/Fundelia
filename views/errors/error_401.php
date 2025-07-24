<h1>Oups !</h1>
<h2>
    <?php if($title == "Déconnecté"): ?>
        Il semblerait que vous ayez été déconnecté suite à une trop longue période d'inactivité. </br>
        Nous vous invitons à vous reconnecter depuis l'accueil.
    <?php else: ?>
        Vous ne pouvez pas accéder à cette page car vous n'êtes pas connecté. </br>
        Nous vous invitons à vous connecter depuis l'accueil.
    <?php endif; ?>
</h2>
<a href="/"><button>Retourner à la page d'accueil</button></a>