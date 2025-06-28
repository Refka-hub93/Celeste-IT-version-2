// Récupération des éléments principaux
const addListButton = document.querySelector('#add-list-button');
const listsContainer = document.querySelector('#lists-container');

// Gestion de l'ajout de nouvelle liste
addListButton.addEventListener('click', function () {
    const listTemplate = document.querySelector('#list-template');
    const listClone = listTemplate.content.cloneNode(true);
    listsContainer.appendChild(listClone);

    const newList = listsContainer.lastElementChild;
    const addCardButton = newList.querySelector('.add-card-button');
    const removeListButton = newList.querySelector('.remove-list-button');
    const cardsContainer = newList.querySelector('.cards');

    // Ajout de carte dans la liste
    addCardButton.addEventListener('click', function () {
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
    });

    // Suppression de liste
    removeListButton.addEventListener('click', function () {
        listsContainer.removeChild(newList);
    });
});


// ✅ Gestion du contenu du modal Bootstrap
const cardModal = document.getElementById('cardModal');

if (cardModal) {
    cardModal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;

        const title = trigger.getAttribute('data-bs-title') || '';
        const description = trigger.getAttribute('data-bs-description') || '';
        const comments = trigger.getAttribute('data-bs-comments') || '';

        cardModal.querySelector('#card-title').value = title;
        cardModal.querySelector('#card-description').value = description;
        cardModal.querySelector('#card-comments').value = comments;
    });
}


function createTable(title) {
    fetch('/api/tables', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // Si tu utilises un token, ajoute aussi :
            // 'Authorization': 'Bearer VOTRE_TOKEN'
        },
        body: JSON.stringify({ title: title })
    })
    .then(response => response.json())
    .then(data => {
        if (data.tableau) {
            console.log('Tableau créé :', data.tableau);
            // Tu peux maintenant afficher ce tableau dans ton interface Trello
        } else if (data.error) {
            alert('Erreur : ' + data.error);
        }
    })
    .catch(error => {
        console.error('Erreur réseau :', error);
    });
}


function fetchTables() {
    fetch('/api/tables', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            // Ajoute l'Authorization si nécessaire
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Tableaux existants :', data);
        // Tu peux les afficher dynamiquement ici :
        data.forEach(table => {
            // Par exemple, ajouter dans le DOM :
            const container = document.getElementById('tables-container');
            const div = document.createElement('div');
            div.textContent = table.title;
            container.appendChild(div);
        });
    })
    .catch(error => {
        console.error('Erreur lors du chargement des tableaux :', error);
    });
}


// colonne 

/* -------------------------------------------------------------
 * columns.js  –  Gestion exclusive des colonnes (listes)
 * ----------------------------------------------------------- */
// document.addEventListener('DOMContentLoaded', () => {
//   /* ---------- Sélecteurs & constantes ---------- */
//   const tableId        = window.TABLE_ID;                      // injecté depuis Twig
//   const API_COLUMNS    = `/api/tables/${tableId}/columns`;     // racine REST
//   const listsContainer = document.getElementById('lists-container');
//   const listTemplate   = document.getElementById('list-template');
//   const addListBtn     = document.getElementById('add-list-button');

//   /* ---------- Helpers DOM ---------- */
//   /**
//    * Construit un <section class="list"> à partir d’un objet colonne.
//    * @param {{id:number,title:string}} col
//    * @returns {HTMLElement}
//    */
//   function buildList(col) {
//     const fragment = listTemplate.content.cloneNode(true);
//     const section  = fragment.querySelector('section.list');
//     section.dataset.id = col.id;

//     /* -- titre editable -- */
//     const titleInput = section.querySelector('.list-title');
//     titleInput.value = col.title;
//     titleInput.addEventListener('change', () => {
//       const newTitle = titleInput.value.trim();
//       if (!newTitle) return;
//       fetch(`/api/columns/${col.id}`, {
//         method : 'PUT',
//         headers: { 'Content-Type': 'application/json' },
//         body   : JSON.stringify({ title: newTitle })
//       });
//     });

//     /* -- bouton supprimer -- */
//     const removeBtn = section.querySelector('.remove-list-button');
//     removeBtn.addEventListener('click', async () => {
//       await fetch(`/api/columns/${col.id}`, { method: 'DELETE' });
//       section.remove();
//     });

//     /* -- on ignore complètement add-card-button & <ul class="cards"> -- */
//     return section;
//   }

//   /* ---------- Initialisation ---------- */
//   (async () => {
//     /* 1. Récupère et affiche toutes les colonnes existantes */
//     const res     = await fetch(API_COLUMNS);
//     const columns = await res.json();          // [{id,title}, …]
//     columns.forEach(col => listsContainer.appendChild(buildList(col)));
//   })();

//   /* 2. Crée une nouvelle colonne via le bouton principal */
//   addListBtn.addEventListener('click', async () => {
//     const title = prompt('Titre de la nouvelle liste :');
//     if (!title) return;

//     const res   = await fetch(API_COLUMNS, {
//       method : 'POST',
//       headers: { 'Content-Type': 'application/json' },
//       body   : JSON.stringify({ title })
//     });
//     const col   = await res.json();            // {id,title}
//     listsContainer.appendChild(buildList(col));
//   });
// });
