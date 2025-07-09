
// Récupération des éléments principaux
const addListButton = document.querySelector('#add-list-button');
const listsContainer = document.querySelector('#lists-container');
const addButton = document.querySelectorAll('.add-card-button');

// Ajout d'écouteurs sur les boutons "Ajouter une carte" déjà présents dans le DOM
for (const button of addButton) {
  button.addEventListener('click', addButtonListener
  );
}

const removeListButton = document.querySelectorAll('.remove-list-button');

// Écouteurs sur les boutons "Supprimer cette liste" déjà présents
for (const button of removeListButton) {
  button.addEventListener('click', function () {
    const newList = this.closest('.list');

    // Demande de confirmation
    if (!confirm('Supprimer cette liste et toutes ses cartes ?')) {
      return; // l’utilisateur a annulé
    }

    // Suppression côté back-end (appel API ou fonction) suppression de la colonne dans la base
    deleteColumn(newList.dataset.columnId);

    // Suppression dans le DOM (interface)
    listsContainer.removeChild(newList);
  }
  );
}

const removeCardButton = document.querySelectorAll('.remove-card-button');

// Écouteurs sur les boutons "Supprimer la carte" déjà présents
for (const button of removeCardButton) {
  button.addEventListener('click', function () {
    const newList = this.closest('.card');

    // Suppression côté back-end
    // BACKEND : suppression de la carte dans la base
    deleteCard(newList.dataset.cardId);

    // Suppression dans l'interface
    newList.parentElement.removeChild(newList);
  }
  );
}

// Ajout de nouvelle liste (template cloné dynamiquement)

addListButton.addEventListener('click', function () {
  const listTemplate = document.querySelector('#list-template');
  const listClone = listTemplate.content.cloneNode(true);
  listsContainer.appendChild(listClone);

  const newList = listsContainer.lastElementChild;
  const addCardButton = newList.querySelector('.add-card-button');
  const removeListButton = newList.querySelector('.remove-list-button');

  // Ajout de carte dans la liste
  addCardButton.addEventListener('click', addButtonListener);

  // Suppression de liste
  removeListButton.addEventListener('click', function () {
    //  Demande de confirmation
    if (!confirm('Supprimer cette liste et toutes ses cartes ?')) {
      return; // l’utilisateur a annulé
    }

    // Suppression côté back-end  et suppression de la colonne dans la base
    deleteColumn(newList.dataset.columnId);

    // Suppression dans l'interface
    listsContainer.removeChild(newList);
  });

});






// Partie API

/**
 * 🗑️ Supprime une colonne.
 * @param {number} columnId
 */
function deleteColumn(columnId) {
  return fetch(`/api/columns/${columnId}`, { method: 'DELETE' })
    .then(checkResponse)
    .then(() => {
      console.log('🗑️ Colonne supprimée :', columnId);
    })
    .catch(err => {
      console.error('❌ Suppression échouée :', err.message);
      throw err; // Rejeter la promesse pour que l'erreur soit gérée par l'appelant
    });
}

function addButtonListener() {
  const newList = this.closest('.list');

  const cardsContainer = newList.querySelector('.cards');
  const cardTemplate = document.querySelector('#card-template');
  const cardClone = cardTemplate.content.cloneNode(true);
  cardsContainer.appendChild(cardClone);

  const insertedCard = cardsContainer.lastElementChild;
  const cardTitle = insertedCard.querySelector('.card-title');

  // 🛠️ Initialisation correcte de la carte vide
  // cardTitle.textContent = 'Nouvelle carte';  // Ce qui s'affiche visuellement
  cardTitle.setAttribute('data-bs-toggle', 'modal');
  cardTitle.setAttribute('data-bs-target', '#cardModal');
  cardTitle.setAttribute('data-bs-title', '');         // ❌ Vide pour éviter remplissage
  cardTitle.setAttribute('data-bs-description', '');
  cardTitle.setAttribute('data-bs-comments', '');





  // 🔥 Gestion suppression carte
  const removeCardButton = insertedCard.querySelector('.remove-card-button');
  if (removeCardButton) {
    removeCardButton.addEventListener('click', function () {
      insertedCard.remove();
    });
  } else {
    console.error("❌ Le bouton .remove-card-button est introuvable !");
  }
}


/**
 * 🗑️ Supprime une carte
 * @param {number} cardId - L’ID de la carte à supprimer
 * @returns {Promise<void>}
 */
function deleteCard(cardId) {
  return fetch(`/api/cards/${cardId}`, {
    method: 'DELETE'
  }).then(checkResponse);
}

/**
 * ✅ Vérifie et retourne la réponse d'une requête fetch
 * @param {Response} response
 * @returns {Promise<any>}
 */
async function checkResponse(response) {
  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    throw new Error(data.message || 'Erreur API');
  }
  return data;
}


/**
 * cardSubmit.js
 * --------------------------------------------------
 * Gère la création et la mise à jour des cartes via
 * le formulaire du modal (#card-details-form).
 *
 *    Le backend Symfony attend le champ « columns » (et non « column »).
 */


const form = document.getElementById('card-details-form');
const cardModal = document.getElementById('cardModal');
let currentCardElement = null;
let currentColumnId = null; // mémorise la colonne cible quand on clique « Ajouter une carte »

/* --------------------------------------------------
 * Détection de la colonne avant ouverture du modal
 * -------------------------------------------------- */
document.body.addEventListener('click', (e) => {
  const addBtn = e.target.closest('.add-card-button');
  if (addBtn) {
    const list = addBtn.closest('.list');
    currentColumnId = list?.dataset.columnId || null;
  }
});

/* --------------------------------------------------
 * Pré‑remplissage lors de l’ouverture du modal
 * -------------------------------------------------- */
if (cardModal) {
  cardModal.addEventListener('show.bs.modal', (event) => {
    const trigger = event.relatedTarget;

    currentCardElement = trigger.closest('li.card'); // null si création
    if (!currentCardElement) {
      form.reset(); // mode création
    }
    const title = trigger.getAttribute('data-bs-title') || '';
    const description = trigger.getAttribute('data-bs-description') || '';
    const comments = trigger.getAttribute('data-bs-comments') || '';

    ///
    
    const attachment = trigger.getAttribute('data-bs-attachment') || '';
       const deadline = trigger.getAttribute('data-bs-deadline') || '';

    document.getElementById('card-attachment').value = attachment;
     document.getElementById('card-deadline').value = deadline;
    cardModal.querySelector('#card-title').value = title;
    cardModal.querySelector('#card-description').value = description;
    cardModal.querySelector('#card-comments').value = comments;
  });
}

/* --------------------------------------------------
 * Soumission du formulaire
 * -------------------------------------------------- */
if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const title = document.getElementById('card-title').value.trim();
    const description = document.getElementById('card-description').value.trim();
    const comments = document.getElementById('card-comments').value.trim();

    // 
    const attachment = document.getElementById('card-attachment').value.trim();
    const deadline = document.getElementById('card-deadline').value;
    // 🆕 Si création, on utilise currentColumnId capturé au clic « Ajouter une carte »
    const cardId = currentCardElement?.dataset.cardId || null;
    const method = cardId ? 'PUT' : 'POST';
    const url = cardId ? `/api/cards/${cardId}` : '/api/cards';

    const payload = {
      // Champs attendus par l’API Symfony
      cardTitle: title,
      description,
      comment: comments,  // 🟢 attention : ici c'est `comment` (pas `comments`)
      attachment,
      notification,
      deadline,

      columns: currentColumnId, // <‑‑ champ attendu par l’API Symfony
    };

    try {
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`HTTP ${response.status} — ${errorText}`);
      }

      const result = await response.json();

      /* ---------- Mise à jour DOM ---------- */
      if (cardId) {
        // Modification d’une carte existante
        currentCardElement.querySelector('.card-title').textContent = title;
      } else {
        // Création réelle après clic sur "Ajouter une carte"
        if (currentCardElement) {
          currentCardElement.dataset.cardId = result.card.id;

          const titleEl = currentCardElement.querySelector('.card-title');
          titleEl.textContent = title;
          titleEl.setAttribute('data-bs-title', title);
          titleEl.setAttribute('data-bs-description', description);
          titleEl.setAttribute('data-bs-comments', comments);

          // 🔽 AJOUTE ICI les autres :
          titleEl.setAttribute('data-bs-attachment', attachment);
          titleEl.setAttribute('data-bs-deadline', deadline);
        } else {
          const cardTemplate = document.getElementById('card-template');
          const clone = cardTemplate.content.cloneNode(true);
          const newCard = clone.querySelector('.card');
          newCard.dataset.cardId = result.card.id;

          const titleEl = newCard.querySelector('.card-title');
          titleEl.textContent = title;
          titleEl.setAttribute('data-bs-toggle', 'modal');
          titleEl.setAttribute('data-bs-target', '#cardModal');
          titleEl.setAttribute('data-bs-title', title);
          titleEl.setAttribute('data-bs-description', description);
          titleEl.setAttribute('data-bs-comments', comments);

          // 🔽 AJOUTE ICI AUSSI :
          titleEl.setAttribute('data-bs-attachment', attachment);
          titleEl.setAttribute('data-bs-deadline', deadline);
          const column = document.querySelector(`.list[data-column-id="${currentColumnId}"] ul.cards`);
          column?.appendChild(newCard);
        }
      }

      // ✅ Nettoyage (à faire quoi qu’il arrive après réussite)
      bootstrap.Modal.getInstance(cardModal).hide();
      form.reset();
      currentCardElement = null;
      currentColumnId = null;

    } catch (error) {
      console.error('❌ Enregistrement échoué :', error);
      alert('Une erreur est survenue — consulte la console pour plus de détails');
    }


  });
}

// GGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG

document.getElementById('add-member-form').addEventListener('submit', function (e) {
  e.preventDefault();

  const email = document.getElementById('email-to-add').value;
  const tableId = document.querySelector('main#board').dataset.tableId;

  fetch(`/tables/${tableId}/add-user-by-email`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({ email })
  })
    .then(res => res.json())
    .then(data => {
      const feedback = document.getElementById('add-member-feedback');
      if (data.error) {
        feedback.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
      } else {
        feedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        document.getElementById('email-to-add').value = '';
      }
    })
    .catch(error => {
      console.error(error);
      document.getElementById('add-member-feedback').innerHTML =
        `<div class="alert alert-danger">Une erreur est survenue</div>`;
    });
});