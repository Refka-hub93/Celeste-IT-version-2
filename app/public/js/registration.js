document.addEventListener("DOMContentLoaded", () => {
    console.log("Fichier registration.js bien chargé ✅");

    const champMotDePasse = document.querySelector("#registration_form_plainPassword");
    const champEmail = document.querySelector("#registration_form_email");
    const caseConditions = document.querySelector("#registration_form_agreeTerms");
    const boutonSoumettre = document.querySelector("button[type='submit']");

    if (!champMotDePasse || !champEmail || !caseConditions || !boutonSoumettre) {
        console.warn("Un ou plusieurs éléments du formulaire n'ont pas été trouvés.");
        return;
    }

    const infoMotDePasse = document.createElement("div");
    infoMotDePasse.className = "form-text mt-1";
    champMotDePasse.parentElement.appendChild(infoMotDePasse);

    const boutonAfficher = document.createElement("button");
    boutonAfficher.type = "button";
    boutonAfficher.className = "btn-show";
    boutonAfficher.textContent = "👁️"; // automatiquement échappé
    champMotDePasse.parentElement.style.position = "relative";
    champMotDePasse.parentElement.appendChild(boutonAfficher);

    boutonAfficher.addEventListener("click", () => {
        champMotDePasse.type = champMotDePasse.type === "password" ? "text" : "password";
    });

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

    function emailValide() {
        const email = champEmail.value;
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regexEmail.test(email);
    }

    function verifierFormulaire() {
        const passwordOk = motDePasseValide();
        const emailOk = emailValide();
        const termsOk = caseConditions.checked;
        boutonSoumettre.disabled = !(passwordOk && emailOk && termsOk);
    }

    champMotDePasse.addEventListener("input", verifierFormulaire);
    champEmail.addEventListener("input", verifierFormulaire);
    caseConditions.addEventListener("change", verifierFormulaire);

    verifierFormulaire(); // Appel initial
});
