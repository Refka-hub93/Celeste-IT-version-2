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
