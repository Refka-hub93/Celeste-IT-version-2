

/**
 * table.js – version corrigée
 * Hypothèses :
 *   – L’API utilise les routes suivantes :
 *       POST   /api/columns            (création)
 *       PUT    /api/columns/{id}       (mise à jour)
 *       DELETE /api/columns/{id}       (suppression)
 *   – Le JSON attendu côté Symfony est :
 *       {
 *         "columnTitle": "Mon titre",
 *         "tables":      1,
 *         "ranking":     0
 *       }
 */

/* ----------------------  INIT  ---------------------- */

  const board        = document.getElementById('board');
  const tableId      = Number(board.dataset.tableId);       // ID du tableau courant
  const listsWrapper = document.getElementById('lists-container');

  /* MutationObserver : déclenché à chaque ajout de LISTE */
  new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      mutation.addedNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE) {
          const titleInput = node.querySelector('.list-title');
          if (titleInput) {
            hookTitleInput(titleInput, tableId);
          }
        }
      });
    });
  }).observe(listsWrapper, { childList: true });

/* -------------------  UTILITAIRES  ------------------- */

/**
 * Ajoute les écouteurs « blur » et « Enter » à un input de titre.
 */
 

/* ------------------  CRUD COLONNES  ------------------ */

/**
 * 🔁 Crée une colonne dans la BDD.
 * @param {string} columnTitle
 * @param {number} ranking
 * @param {number} tableId
 */
// function createColumn(columnTitle, ranking = 0, tableId) {
//   fetch('/api/columns', {
//     method : 'POST',
//     headers: { 'Content-Type': 'application/json' },
//     body   : JSON.stringify({
//       columnTitle,
//       ranking,
//       tables: tableId
//     })
//   })
//     .then(checkResponse)
//     .then(data => console.log('✅ Colonne créée :', data))
//     .catch(err => console.error('❌ Création échouée :', err.message));
// }
function createColumn(columnTitle, ranking = 0, tableId) {
  return fetch('/api/columns', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      columnTitle: columnTitle || 'Nouvelle Colonne',
      ranking,
      tables: tableId
    })
  })
    .then(checkResponse);
}

function hookTitleInput(input, tableId, columnId = null) {
  const listElement = input.closest('.list');

  // Si la colonne a déjà un ID, c'est une colonne existante, donc pas besoin de la créer à nouveau
  if (!columnId) {
    const title = input.value.trim() || 'Nouvelle Colonne';
    createColumn(title, 0, tableId).then(response => {
      const data = response;
      if (data.column && data.column.id) {
        listElement.setAttribute('data-column-id', data.column.id);
      }
    });
  }

  // ➜ Perte de focus → mise à jour
  input.addEventListener('blur', () => {
    const title = input.value.trim();
    if (title) {
      const columnId = listElement.getAttribute('data-column-id');
      if (columnId) {
        updateColumn(columnId, title, 0);
      }
    }
  });

  // ➜ Appui sur Entrée → déclenche blur
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      input.blur();
    }
  });
}



/**
 * ✏️ Met à jour une colonne.
 * @param {number} columnId
 * @param {string} columnTitle
 * @param {number} ranking
 */
function updateColumn(columnId, columnTitle, ranking) {
  fetch(`/api/columns/${columnId}`, {
    method : 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body   : JSON.stringify({ columnTitle, ranking })
  })
    .then(checkResponse)
    .then(data => console.log('✅ Colonne mise à jour :', data))
    .catch(err => console.error('❌ Mise à jour échouée :', err.message));
}


/**
 * Vérifie la réponse fetch ; lève une erreur si !response.ok.
 */
// async function checkResponse(response) {
//   const data = await response.json().catch(() => ({}));
//   if (!response.ok) {
//     const msg = data.message || 'Erreur serveur';
//     throw new Error(msg);
//   }
//   return data;
// }

// function fetchColumns(tableId) {
//   fetch(`/api/columns?tableId=${tableId}`, {
//     method: 'GET',
//     headers: { 'Content-Type': 'application/json' },
//   })
//     .then(checkResponse)
//     .then(data => {
//       data.forEach(column => {
//         // Ajoute les colonnes existantes au DOM
//         const listTemplate = document.querySelector('#list-template');
//         const listClone = listTemplate.content.cloneNode(true);
//         listsContainer.appendChild(listClone);

//         const newList = listsContainer.lastElementChild;
//         const titleInput = newList.querySelector('.list-title');
//         titleInput.value = column.title;

//         // Ajoute les écouteurs d'événements
//         hookTitleInput(titleInput, tableId);
//       });
//     })
//     .catch(err => console.error('❌ Chargement des colonnes échoué :', err.message));
// }


document.addEventListener('DOMContentLoaded', function () {
  const tableId = Number(board.dataset.tableId);
  // fetchColumns(tableId);
});

// // /**
// //  * 🔁 Charge les colonnes + cartes depuis l’API et les affiche dans l’interface
// //  */
// function loadColumnsAndCards(tableId) {
//   fetch(`/api/columns?tableId=${tableId}`, {
//     method: 'GET',
//     headers: { 'Content-Type': 'application/json' },
//   })
//     .then(checkResponse)
//     .then(async columns => {
//       for (const column of columns) {
//         // 👉 Clone de la colonne
//         const listTemplate = document.querySelector('#list-template');
//         const listClone = listTemplate.content.cloneNode(true);
//         listsContainer.appendChild(listClone);

//         const newList = listsContainer.lastElementChild;
//         const titleInput = newList.querySelector('.list-title');
//         const cardsUl = newList.querySelector('.cards');

//         titleInput.value = column.title;
//         newList.dataset.columnId = column.id;

//         hookTitleInput(titleInput, tableId, column.id); // met à jour sur blur/enter

//         // 🔁 Récupérer les cartes de cette colonne
//         const cards = await fetch(`/api/cards?columnId=${column.id}`)
//           .then(checkResponse)
//           .catch(() => []);

//         for (const card of cards) {
//           const cardTemplate = document.querySelector('#card-template');
//           const cardClone = cardTemplate.content.cloneNode(true);
//           const li = cardClone.querySelector('li');

//           li.dataset.cardId = card.id;
//           li.querySelector('.card-title').textContent = card.title;

//           // Activation du modal sur le titre
//           const cardTitle = li.querySelector('.card-title');
//           cardTitle.setAttribute('data-bs-toggle', 'modal');
//           cardTitle.setAttribute('data-bs-target', '#cardModal');
//           cardTitle.setAttribute('data-bs-title', card.title);
//           cardTitle.setAttribute('data-bs-description', card.description || '');
//           cardTitle.setAttribute('data-bs-comments', '');

//           // Bouton suppression carte
//           const removeCardButton = li.querySelector('.remove-card-button');
//           removeCardButton?.addEventListener('click', () => li.remove());

//           cardsUl.appendChild(li);
//         }

//         // Gestion drag & drop
//         newList.addEventListener('dragover', handleDragOver);
//         newList.addEventListener('dragenter', handleDragEnter);
//         newList.addEventListener('dragleave', handleDragLeave);
//         newList.addEventListener('drop', handleDrop);

//         // Suppression colonne
//         const removeListButton = newList.querySelector('.remove-list-button');
//         removeListButton.addEventListener('click', () => {
//           deleteColumn(newList.dataset.columnId);
//           listsContainer.removeChild(newList);
//         });
//       }
//     })
//     .catch(err => console.error('❌ Erreur de chargement des colonnes/cartes :', err.message));
// }

// // Appeler la fonction au chargement de la page
// document.addEventListener('DOMContentLoaded', function () {
//   const tableId = Number(board.dataset.tableId);
//   if (tableId) {
//     loadColumnsAndCards(tableId);
//   }
// });

