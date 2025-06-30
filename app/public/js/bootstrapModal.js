

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