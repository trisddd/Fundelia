(function setupCopyButtons(){
    const copyButtons = document.querySelectorAll('.copy-btn');

    copyButtons.forEach(button => {
        button.addEventListener('click', () => {
            const apiKey = button.dataset.apiKey;

            if (!apiKey) {
                alert("Aucune clé à copier.");
                return;
            }

            // Création d'un textarea temporaire
            const textarea = document.createElement("textarea");
            textarea.value = apiKey;
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand("copy");
                button.textContent = "Copiée !";
                button.style.backgroundColor = "#28a745";

                setTimeout(() => {
                    button.textContent = "Copier";
                    button.style.backgroundColor = "#186ed1";
                }, 2000);
            } catch (err) {
                alert("Échec de la copie.");
                console.error("Erreur : ", err);
            }

            document.body.removeChild(textarea);
        });
    });
})()