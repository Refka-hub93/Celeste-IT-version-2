
function initialiserValidationFormulaireContact() {
  const email = document.querySelector("#contact_form_email");
  const subject = document.querySelector("#contact_form_subject");
  const message = document.querySelector("#contact_form_message");
  const submitBtn = document.querySelector("#contact_form_submit");
  const form = document.querySelector("form");

  // Sécurité : vérifier que tous les champs sont présents
  if (!email || !subject || !message || !submitBtn || !form) {
    console.error("Un ou plusieurs éléments du formulaire sont introuvables !");
    return;
  }

  // Fonction de validation du formulaire (activée à chaque saisie)
  function verifierFormulaire() {
    const emailOK = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    const sujetOK = subject.value.trim().length > 0;
    const messageOK = message.value.trim().length > 10;

    submitBtn.disabled = !(emailOK && sujetOK && messageOK);
    return emailOK && sujetOK && messageOK; // utile pour la validation finale
  }

  // Ajout des écouteurs sur chaque champ
  [email, subject, message].forEach((field) => {
    field.addEventListener("input", verifierFormulaire);
  });

  // Empêcher l’envoi du formulaire si les champs sont invalides
  form.addEventListener("submit", function (e) {
    if (!verifierFormulaire()) {
      e.preventDefault();
      alert("Veuillez remplir correctement tous les champs du formulaire.");
    }
  });

  // Vérification initiale au chargement
  verifierFormulaire();
}

// Appel immédiat de la fonction
initialiserValidationFormulaireContact();
