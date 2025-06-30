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
 * 📦 Déplace une carte vers une autre colonne
 * @param {number} cardId - L’ID de la carte
 * @param {number} newColumnId - ID de la nouvelle colonne
 * @returns {Promise<object>}
 */
function moveCard(cardId, newColumnId) {
  return updateCard(cardId, { columns: newColumnId });
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


createCard("Nouvelle tâche", 4, "")
  .then(data => console.log("✅ Carte créée :", data))
  .catch(err => alert("Erreur : " + err.message));
