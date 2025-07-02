
// Récupération des éléments principaux
const addListButton = document.querySelector('#add-list-button');
const listsContainer = document.querySelector('#lists-container');
const addButton = document.querySelectorAll('.add-card-button');

for (const button of addButton) {
  button.addEventListener('click', addButtonListener
  );
}


const removeListButton = document.querySelectorAll('.remove-list-button');

for (const button of removeListButton) {
  button.addEventListener('click', function () {
    const newList = this.closest('.list');

//  // ✅ Demande de confirmation
    if (!confirm('Supprimer cette liste et toutes ses cartes ?')) {
      return; // l’utilisateur a annulé
    }


    // Récupération de l'ID de la colonne à supprimer
    deleteColumn(newList.dataset.columnId);

    listsContainer.removeChild(newList);
  }
  );
}
// removeListButton.addEventListener('click', function () {
//   insertedCard.remove();
// });
const removeCardButton = document.querySelectorAll('.remove-card-button');

for (const button of removeCardButton) {
  button.addEventListener('click', function () {
    const newList = this.closest('.card');
    // Récupération de l'ID de la colonne à supprimer
    deleteCard(newList.dataset.cardId);

    newList.parentElement.removeChild(newList);
  }
  );
}

// let draggedCard = null;
// // Gestion du glisser-déposer des cartes

// Gestion de l'ajout de nouvelle liste
addListButton.addEventListener('click', function () {
  const listTemplate = document.querySelector('#list-template');
  const listClone = listTemplate.content.cloneNode(true);
  listsContainer.appendChild(listClone);

  const newList = listsContainer.lastElementChild;



// ➕ Événements drag & drop pour la colonne
// newList.addEventListener('dragover', handleDragOver);
// newList.addEventListener('dragenter', handleDragEnter);
// newList.addEventListener('dragleave', handleDragLeave);
// newList.addEventListener('drop', handleDrop);

  const addCardButton = newList.querySelector('.add-card-button');
  const removeListButton = newList.querySelector('.remove-list-button');



  // Ajout de carte dans la liste
  addCardButton.addEventListener('click', addButtonListener);

// drag and drop

// li.setAttribute('draggable', 'true');
// li.addEventListener('dragstart', handleDragStart);
// li.addEventListener('dragend', handleDragEnd);


  // Suppression de liste
  removeListButton.addEventListener('click', function () {
//  // ✅ Demande de confirmation
    if (!confirm('Supprimer cette liste et toutes ses cartes ?')) {
      return; // l’utilisateur a annulé
    }

    // ########
    deleteColumn(newList.dataset.columnId);
    listsContainer.removeChild(newList);

  });

});


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



  // // 👉 Attache uniquement le modal au titre
  // cardTitle.setAttribute('data-bs-toggle', 'modal');
  // cardTitle.setAttribute('data-bs-target', '#cardModal');
  // cardTitle.setAttribute('data-bs-title', cardTitle.textContent);
  // cardTitle.setAttribute('data-bs-description', '');
  // cardTitle.setAttribute('data-bs-comments', '');

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
 * ⚠️ 2025‑07‑01 — Fix :
 *   • Le backend Symfony attend le champ « columns » (et non « column »).
 *   • Journalise le corps de réponse HTTP en cas d’erreur pour faciliter le debug.
 */

document.addEventListener('DOMContentLoaded', () => {
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

      // 🆕 Si création, on utilise currentColumnId capturé au clic « Ajouter une carte »
      const cardId = currentCardElement?.dataset.cardId || null;
      const method = cardId ? 'PUT' : 'POST';
      const url = cardId ? `/api/cards/${cardId}` : '/api/cards';

      const payload = {
        cardTitle: title,
        description,
        comments,
        columns: currentColumnId, // <‑‑ champ attendu par l’API Symfony
      };

      // try {
      //   const response = await fetch(url, {
      //     method,
      //     headers: {
      //       'Content-Type': 'application/json',
      //     },
      //     body: JSON.stringify(payload),
      //   });

      //   if (!response.ok) {
      //     const errorText = await response.text();
      //     throw new Error(`HTTP ${response.status} — ${errorText}`);
      //   }

      //   const result = await response.json();

      //   /* ---------- Mise à jour DOM ---------- */
      //   if (cardId) {
      //     // Modification d’une carte existante
      //     currentCardElement.querySelector('.card-title').textContent = title;
      //   } else {
      //     // Création : injecte la nouvelle carte dans la bonne colonne
      //     const cardTemplate = document.getElementById('card-template');
      //     const clone = cardTemplate.content.cloneNode(true);
      //     const newCard = clone.querySelector('.card');
      //     newCard.dataset.cardId = result.id;
      //     const titleEl = newCard.querySelector('.card-title');
      //     titleEl.textContent = title;
      //     titleEl.setAttribute('data-bs-toggle', 'modal');
      //     titleEl.setAttribute('data-bs-target', '#cardModal');
      //     titleEl.setAttribute('data-bs-title', title);
      //     titleEl.setAttribute('data-bs-description', description);
      //     titleEl.setAttribute('data-bs-comments', comments);

      //     const column = document.querySelector(`.list[data-column-id="${currentColumnId}"] ul.cards`);
      //     column?.appendChild(newCard);
      //   }

      //   /* ---------- Nettoyage ---------- */
      //   bootstrap.Modal.getInstance(cardModal).hide();
      //   form.reset();
      //   currentCardElement = null;
      //   currentColumnId = null;
      // } catch (error) {
      //   console.error('❌ Enregistrement échoué :', error);
      //   alert('Une erreur est survenue — consulte la console pour plus de détails');
      // }
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
});


//   // drag and drop
// function handleDragStart(e) {
//   draggedCard = this;
//   e.dataTransfer.setData('text/plain', this.dataset.cardId);
//   setTimeout(() => {
//     this.style.display = 'none';
//   }, 0);
// }

// function handleDragEnd() {
//   setTimeout(() => {
//     this.style.display = 'block';
//   }, 0);
//   draggedCard = null;
// }

// function handleDragOver(e) {
//   e.preventDefault();
// }

// function handleDragEnter(e) {
//   e.preventDefault();
//   this.classList.add('drag-over');
// }

// function handleDragLeave() {
//   this.classList.remove('drag-over');
// }

// function handleDrop(e) {
//   e.preventDefault();
//   this.classList.remove('drag-over');

//   const newColumnId = this.dataset.columnId;
//   if (draggedCard && newColumnId) {
//     this.querySelector('.cards').appendChild(draggedCard);

//     const cardId = draggedCard.dataset.cardId;
//     if (cardId) {
//       moveCard(cardId, newColumnId)
//         .then(data => console.log('✅ Carte déplacée :', data))
//         .catch(err => console.error('❌ Déplacement échoué :', err.message));
//     }
//   }
// }
