(function addIframeParamToLinks() {
    try {
        const inIframe = window.self !== window.top;
        if (!inIframe) return;

        const links = document.querySelectorAll('a[href]');

        links.forEach(link => {
            const url = new URL(link.href, window.location.origin);

            if (!url.searchParams.has('iframe')) {
                url.searchParams.set('iframe', 'true');
                link.href = url.toString();
            }
        });

        const forms = document.querySelectorAll('form[action]');

        forms.forEach(form => {
            const action = form.getAttribute('action') || '';
            const url = new URL(action, window.location.href);
            if (!url.searchParams.has('iframe')) {
                url.searchParams.set('iframe', 'true');
                form.setAttribute('action', url.toString());
            }
        });

    } catch (e) {
        console.error("Erreur dans addIframeParamToLinks :", e);
    }
})();

//Fonction pour suivre le curseur sur la page d'accueil avec une lumière bleu
document.addEventListener('DOMContentLoaded', () => {
  const light = document.getElementById('light');
  const hero = document.querySelector('.hero');

  if (!light || !hero) return;

  hero.addEventListener('mousemove', (e) => {
    const rect = hero.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    light.style.left = `${x}px`;
    light.style.top = `${y}px`;
  });

  hero.addEventListener('mouseenter', () => {
    light.style.display = 'block';
  });

  hero.addEventListener('mouseleave', () => {
    light.style.display = 'none';
  });
});

// Animation fade-in des sections
const faders = document.querySelectorAll('.fade-in');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    } else {
      // Supprime la classe visible si on quitte la section
      entry.target.classList.remove('visible');
    }
  });
}, { threshold: 0.2 });
faders.forEach(el => observer.observe(el));

// Fonction pour afficher/cacher le mot de passe/code
function passcodeVisibility (button) {
const wrapper = button.closest('.input-wrapper');
if (!wrapper) return;

const input = wrapper.querySelector('input');
if (!input) return;

if (input.id.includes('password') || input.id.includes('code')) {
    input.type = input.type === 'password' ? 'text' : 'password';
}
};

// Fonction pour déplacer le focus vers le champ suivant après la saisie
function moveFocus(id) {
  if (document.getElementById(`otp${id}`).value.length == 1) {
    document.getElementById(`otp${id + 1}`).focus(); // Passe au champ suivant
  }
}

// Fonction pour gérer la touche Backspace
function Backspace(event, id) {
  if (event.key === "Backspace" && document.getElementById(`otp${id}`).value === "") {
    document.getElementById(`otp${id - 1}`).focus();
  }
}

// Fonction pour valider l'entrée et n'autoriser que les chiffres
function validateInput(event) {
  // On empêche la saisie d'autres caractères que les chiffres
  if (!/[0-9]/.test(event.key)) {
    event.preventDefault();
  }
}

// Gestion du collage dans le champ OTP
const otpContainer = document.querySelector('.otp-inputs');
if (otpContainer) {
  otpContainer.addEventListener('paste', function (event) {
    event.preventDefault();
    const paste = (event.clipboardData || window.clipboardData).getData('text');
    const digits = paste.replace(/\D/g, '');
    const inputs = document.querySelectorAll('.otp-inputs input');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].value = digits[i] || '';
    }
    const filledIndex = Math.min(digits.length, inputs.length) - 1;
    if (filledIndex >= 0) {
      inputs[filledIndex].focus();
    }
  });
}

//Fonction pour le déplacement du glidder de la navbar
document.addEventListener('DOMContentLoaded', () => {
  const navItems = document.querySelectorAll('.sidebar-nav li');
  const indicator = document.querySelector('.active-indicator');
  const currentUrl = window.location.href;

  function moveIndicatorTo(target) {
    if (!target) return;
    const topOffset = target.offsetTop;
    indicator.style.top = topOffset + 'px';
  }

  // Détecte l'élément actif à partir de l'URL
  navItems.forEach(item => {
    const link = item.querySelector('a');
    if (link && currentUrl.includes(link.getAttribute('href'))) {
      document.querySelector('.sidebar-nav li.active')?.classList.remove('active');
      item.classList.add('active');
      moveIndicatorTo(item);
    }
  });

  // Repositionnement du glider si jamais aucun match n'est trouvé
  const fallback = document.querySelector('.sidebar-nav li.active');
  moveIndicatorTo(fallback);

  // Ajoute les écouteurs de clic
  navItems.forEach(item => {
    item.addEventListener('click', () => {
      document.querySelector('.sidebar-nav li.active')?.classList.remove('active');
      item.classList.add('active');
      moveIndicatorTo(item);
    });
  });
});

