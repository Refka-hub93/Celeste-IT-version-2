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


// fetch('/api/cards', {
//   method: 'POST',
//   headers: { 'Content-Type': 'application/json' },
//   body: JSON.stringify({ cardTitle: "Ma carte", columns: 4})
// })
// .then(res => res.json())
// .then(data => {
//   console.log("Réponse de l'API :", data); // Log pour voir la structure de la réponse
//   const cardId = data.card.id;

//   // 🔁 Appel de l'API pour récupérer les détails
//   return fetch(`/api/cards/${cardId}`);
// })
// .then(res => res.json())
// .then(details => {
//   console.log("Détails complets de la carte :", details);
// });

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


 