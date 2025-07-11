/**
 * 🔁 Crée une carte dans une colonne donnée
 * @param {string} cardTitle - Le titre de la carte
 * @param {number} columnId - L’ID de la colonne associée
 * @param {string} [description] - Description optionnelle
 * @returns {Promise<object>}
 */
function createCard(cardTitle, columnId, description = '') {
  return fetch('/api/cards', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      cardTitle: cardTitle,
      columns: columnId,
      description: description
    })
  }).then(checkResponse);
}





/**
 * ✏️ Met à jour une carte (titre, description, date, etc.)
 * @param {number} cardId - L’ID de la carte à modifier
 * @param {object} payload - Champs à modifier (ex: { cardTitle, description, deadline })
 * @returns {Promise<object>}
 */
function updateCard(cardId, payload) {
  return fetch(`/api/cards/${cardId}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  }).then(checkResponse);
}



/**
 * 📦 Déplace une carte vers une autre colonne
 * @param {number} cardId - L’ID de la carte
 * @param {number} newColumnId - ID de la nouvelle colonne
 * @returns {Promise<object>}
 */
function moveCard(cardId, newColumnId) {
  return updateCard(cardId, { columns: newColumnId });
}

// Charger les commentaires
function loadComments(cardId) {
  fetch(`/api/comments/${cardId}`)
    .then(res => res.json())
    .then(data => {
      const thread = document.getElementById('comments-thread');
      thread.innerHTML = '';
      data.forEach(c => {
        const div = document.createElement('div');
        div.className = 'border rounded p-2 mb-2';
        div.innerHTML = `<strong>${c.author}</strong> — <small>${c.createdAt}</small><br>${c.content}`;
        thread.appendChild(div);
      });
    });
}

// Ajout de commentaire
document.getElementById('comment-form')?.addEventListener('submit', function (e) {
  e.preventDefault();
  const content = document.getElementById('new-comment').value.trim();
  if (!content) return;

  fetch(`/api/comments/${currentCardElement.dataset.cardId}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ content })
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById('new-comment').value = '';
    loadComments(currentCardElement.dataset.cardId);
  });
});



//  Charge les commentaires d'une carte
function loadComments(cardId) {
  if (!cardId) return; // évite les appels inutiles

  fetch(`/api/comments/${cardId}`)
    .then(res => res.json())
    .then(data => {
      const thread = document.getElementById('comments-thread');
      thread.innerHTML = ''; // 🔁 on vide à chaque fois
      [...data].reverse().forEach(c => {
        const div = document.createElement('div');
        div.className = 'border rounded p-2 mb-2 bg-light';
        const initials = c.author[0]?.toUpperCase() || '?';
        div.innerHTML = `
          <div class="d-flex justify-content-between align-items-center mb-1">
            <div>
              <span class="badge bg-primary me-2">${initials}</span>
              <strong>${c.author}</strong>
            </div>
            <small class="text-muted">${c.createdAt}</small>
          </div>
          <div>${c.content}</div>
        `;
        thread.appendChild(div);
      });
    });
}

