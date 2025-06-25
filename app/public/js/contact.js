document.addEventListener("DOMContentLoaded", () => {
  const firstname = document.querySelector("#contact_form_firstname");
  const lastname = document.querySelector("#contact_form_lastname");
  const email = document.querySelector("#contact_form_email");
  const subject = document.querySelector("#contact_form_subject");
  const message = document.querySelector("#contact_form_message");
  const submitBtn = document.querySelector("#contact_form_submit");

  // 🔒 Sécurité : s’assurer que tout existe
  if (!firstname || !lastname || !email || !subject || !message || !submitBtn) {
    console.warn("Certains champs du formulaire de contact sont introuvables.");
    return; // ← maintenant c’est légal car on est DANS une fonction
  }

  // ✅ Validation simple
  function verifierFormulaire() {
    const prenomOK = firstname.value.trim().length > 1;
    const nomOK = lastname.value.trim().length > 1;
    const emailOK = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    const sujetOK = subject.value.trim().length > 0;
    const messageOK = message.value.trim().length > 10;

    submitBtn.disabled = !(prenomOK && nomOK && emailOK && sujetOK && messageOK);
  }

  // 🧠 Ajout des écouteurs
  [firstname, lastname, email, subject, message].forEach((field) => {
    field.addEventListener("input", verifierFormulaire);
  });

  verifierFormulaire(); // appel initial
});
