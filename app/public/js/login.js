
const emailField = document.querySelector("#username");
const passwordField = document.querySelector("#password");
const submitButton = document.querySelector("button[type='submit']");

// Fonction pour afficher/masquer le mot de passe
function ajouterBoutonAffichageMotDePasse() {
    if (!passwordField) return;

    // Ajout du bouton 👁️
    const toggleButton = document.createElement("button");
    toggleButton.type = "button";
    toggleButton.className = "btn-show";

    toggleButton.setAttribute("aria-label", "Afficher/Masquer mot de passe");
    toggleButton.innerHTML = "👁️";

    // Style sur le conteneur du champ pour le positionnement
    const container = passwordField.parentElement;
    container.style.position = "relative";
    container.appendChild(toggleButton);

    toggleButton.addEventListener("click", () => {
        const isHidden = passwordField.type === "password";
        passwordField.type = isHidden ? "text" : "password";
        toggleButton.innerHTML = isHidden ? "🙈" : "👁️";
    });
}

// Fonction de validation simple : empêche l'envoi si vide
function verifierChamps() {
    const emailValide = emailField?.value.trim().length > 0;
    const motDePasseValide = passwordField?.value.trim().length > 0;

    if (submitButton) {
        submitButton.disabled = !(emailValide && motDePasseValide);
    }
}

// Initialisation
ajouterBoutonAffichageMotDePasse();
verifierChamps();

// Écouteurs d'événements
emailField?.addEventListener("input", verifierChamps);
passwordField?.addEventListener("input", verifierChamps);
