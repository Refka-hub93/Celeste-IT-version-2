
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
  const addCardButton = newList.querySelector('.add-card-button');
  const removeListButton = newList.querySelector('.remove-list-button');



  // Ajout de carte dans la liste
  addCardButton.addEventListener('click', addButtonListener);

  // Suppression de liste
  removeListButton.addEventListener('click', function () {

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

    // 👉 Attache uniquement le modal au titre
    cardTitle.setAttribute('data-bs-toggle', 'modal');
    cardTitle.setAttribute('data-bs-target', '#cardModal');
    cardTitle.setAttribute('data-bs-title', cardTitle.textContent);
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





//   // drag and drop

//   // Ajouter des écouteurs d'événements pour le glisser-déposer
//   newList.addEventListener('dragover', handleDragOver);
//   newList.addEventListener('dragenter', handleDragEnter);
//   newList.addEventListener('dragleave', handleDragLeave);
//   newList.addEventListener('drop', handleDrop);
// });

// function handleDragStart(e) {
//   draggedCard = this;
//   e.dataTransfer.setData('text/plain', this.getAttribute('data-card-id'));
//   setTimeout(() => {
//     this.style.display = 'none';
//   }, 0);
// }

// function handleDragEnd() {
//   setTimeout(() => {
//     this.style.display = 'block';
//   }, 0);
// }

// function handleDragOver(e) {
//   e.preventDefault();
//   return false;
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

//   if (draggedCard) {
//     const cardId = draggedCard.getAttribute('data-card-id');
//     const newColumnId = this.getAttribute('data-column-id');

//     this.querySelector('.cards').appendChild(draggedCard);
//     draggedCard = null;

//     if (cardId && newColumnId) {
//       moveCard(cardId, newColumnId)
//         .then(data => console.log('✅ Carte déplacée :', data))
//         .catch(err => console.error('❌ Déplacement échoué :', err.message));
//     }
//   }
// 