

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
function hookTitleInput(input, tableId) {
  // ➜ Perte de focus → enregistrement
  input.addEventListener('blur', () => {
    const title = input.value.trim();
    if (title) createColumn(title, 0, tableId);
  });

  // ➜ Appui sur Entrée → déclenche blur
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      e.preventDefault();
      input.blur();
    }
  });
}

/* ------------------  CRUD COLONNES  ------------------ */

/**
 * 🔁 Crée une colonne dans la BDD.
 * @param {string} columnTitle
 * @param {number} ranking
 * @param {number} tableId
 */
function createColumn(columnTitle, ranking = 0, tableId) {
  fetch('/api/columns', {
    method : 'POST',
    headers: { 'Content-Type': 'application/json' },
    body   : JSON.stringify({
      columnTitle,
      ranking,
      tables: tableId
    })
  })
    .then(checkResponse)
    .then(data => console.log('✅ Colonne créée :', data))
    .catch(err => console.error('❌ Création échouée :', err.message));
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
 * 🗑️ Supprime une colonne.
 * @param {number} columnId
 */
function deleteColumn(columnId) {
  fetch(`/api/columns/${columnId}`, { method: 'DELETE' })
    .then(checkResponse)
    .then(() => console.log('🗑️ Colonne supprimée :', columnId))
    .catch(err => console.error('❌ Suppression échouée :', err.message));
}

/**
 * Vérifie la réponse fetch ; lève une erreur si !response.ok.
 */
async function checkResponse(response) {
  const data = await response.json().catch(() => ({}));
  if (!response.ok) {
    const msg = data.message || 'Erreur serveur';
    throw new Error(msg);
  }
  return data;
}



