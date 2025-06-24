document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.querySelector("#registration_form_plainPassword");
    const agreeCheckbox = document.querySelector("#registration_form_agreeTerms");
    const submitButton = document.querySelector("button[type='submit']");

    // Crée un message sous le champ mot de passe
    const message = document.createElement("div");
    message.className = "form-text mt-1";
    passwordInput.parentElement.appendChild(message);

    // Crée la case "Afficher le mot de passe"
    const showPasswordDiv = document.createElement("div");
    showPasswordDiv.className = "form-check mt-2";

    const showPasswordCheckbox = document.createElement("input");
    showPasswordCheckbox.type = "checkbox";
    showPasswordCheckbox.className = "form-check-input";
    showPasswordCheckbox.id = "showPassword";

    const showPasswordLabel = document.createElement("label");
    showPasswordLabel.className = "form-check-label";
    showPasswordLabel.textContent = "Afficher le mot de passe";
    showPasswordLabel.htmlFor = "showPassword";

    showPasswordDiv.appendChild(showPasswordCheckbox);
    showPasswordDiv.appendChild(showPasswordLabel);
    passwordInput.parentElement.appendChild(showPasswordDiv);

    // Fonction pour valider le mot de passe
    function validatePassword() {
        const password = passwordInput.value;

        // regex : au moins 8 caractères, 1 maj, 1 min, 1 chiffre, 1 caractère spécial
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (regex.test(password)) {
            message.textContent = "Mot de passe valide.";
            message.style.color = "green";
            return true;
        } else {
            message.textContent = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un symbole.";
            message.style.color = "red";
            return false;
        }
    }

    // Fonction pour activer/désactiver le bouton
    function updateButton() {
        const passwordOk = validatePassword();
        const termsOk = agreeCheckbox.checked;

        if (passwordOk && termsOk) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }

    // Voir ou cacher le mot de passe
    showPasswordCheckbox.addEventListener("change", function () {
        if (showPasswordCheckbox.checked) {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    });

    // Quand on tape dans le champ ou clique sur la case
    passwordInput.addEventListener("input", updateButton);
    agreeCheckbox.addEventListener("change", updateButton);

    // Vérifie au début
    updateButton();
});





const champMotDePasse = document.querySelector("#registration_form_plainPassword");
const champEmail = document.querySelector("#registration_form_email");
const caseConditions = document.querySelector("#registration_form_agreeTerms");
const boutonSoumettre = document.querySelector("button[type='submit']");

// Ajout du message de validation sous le champ mot de passe
const infoMotDePasse = document.createElement("div");
infoMotDePasse.className = "form-text mt-1";
champMotDePasse.parentElement.appendChild(infoMotDePasse);

// Ajout du bouton œil dans le champ mot de passe
const boutonAfficher = document.createElement("button");
boutonAfficher.type = "button";
boutonAfficher.className = "btn-show";
boutonAfficher.innerHTML = "👁️";
champMotDePasse.parentElement.style.position = "relative";
champMotDePasse.parentElement.appendChild(boutonAfficher);

boutonAfficher.addEventListener("click", () => {
    champMotDePasse.type = champMotDePasse.type === "password" ? "text" : "password";
});

// Validation du mot de passe
function motDePasseValide() {
    const motDePasse = champMotDePasse.value;
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (regex.test(motDePasse)) {
        infoMotDePasse.textContent = "Mot de passe valide.";
        infoMotDePasse.style.color = "green";
        return true;
    } else {
        infoMotDePasse.textContent = "8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 symbole.";
        infoMotDePasse.style.color = "red";
        return false;
    }
}

// Validation email
function emailValide() {
    const email = champEmail.value;
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regexEmail.test(email);
}

// Active/désactive le bouton de soumission
function verifierFormulaire() {
    const passwordOk = motDePasseValide();
    const emailOk = emailValide();
    const termsOk = caseConditions.checked;

    boutonSoumettre.disabled = !(passwordOk && emailOk && termsOk);
}

// Écoute des événements
champMotDePasse.addEventListener("input", verifierFormulaire);
champEmail.addEventListener("input", verifierFormulaire);
caseConditions.addEventListener("change", verifierFormulaire);

verifierFormulaire();
});
