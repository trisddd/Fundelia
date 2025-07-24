<h1>
    <?php
    if (isset($error_message)) {
        echo "Une erreur s'est produite : " . $error_message;
    } else {
        echo "Patientez, nous créons votre fiche client !";
    }
    ?>
</h1>
<p><?php echo isset($error_message) ? $error_message : ""; ?></p>
