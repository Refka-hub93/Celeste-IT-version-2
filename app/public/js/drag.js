/*
 * kanban-dnd.js
 * ----------------------------------------------------------------------
 * Gestion complète du drag & drop des cartes Kanban avec mise à jour
 * côté backend Symfony (via /api/cards/{id} PUT).
 */

document.addEventListener('DOMContentLoaded', () => {
  let draggedCard = null;

  document.body.addEventListener('dragstart', (e) => {
    const card = e.target.closest('.card');
    console.log('🧭 dragstart sur', card);
    if (card) {
      console.log('📦 dragstart sur carte ID =', card.dataset.cardId);
      draggedCard = card;
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', ''); // requis sur certains navigateurs
      setTimeout(() => card.classList.add('dragging'), 0);
    }
  });

  document.body.addEventListener('dragend', () => {
    if (draggedCard) {
      draggedCard.classList.remove('dragging');
      draggedCard = null;
    }
  });

  // ✅ Initialiser les dropzones existantes rendues par Twig
  document.querySelectorAll('.cards').forEach(setupDropzone);

  // ✅ MutationObserver pour les colonnes créées dynamiquement
  const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      mutation.addedNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE) {
          const dropZone = node.querySelector('ul.cards');
          if (dropZone) setupDropzone(dropZone);
        }
      });
    });
  });
  observer.observe(document.getElementById('lists-container'), { childList: true, subtree: true });
});

function setupDropzone(container) {
  console.log('🧪 Dropzone initialisée sur', container);

  container.addEventListener('dragover', e => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    container.classList.add('drag-over');
  });

  container.addEventListener('dragleave', () => {
    container.classList.remove('drag-over');
  });

  container.addEventListener('drop', e => {
    e.preventDefault();
    e.stopPropagation();
    container.classList.remove('drag-over');
    console.log('🔥 DROP déclenché sur colonne ID =', container.closest('.list')?.dataset.columnId);

    const card = document.querySelector('.card.dragging');
    if (!card) return;

    const columnElement = container.closest('.list');
    const newColumnId = parseInt(columnElement.dataset.columnId);
    const cardId = parseInt(card.dataset.cardId);

    if (isNaN(newColumnId) || isNaN(cardId)) {
      console.error('❌ ID colonne ou carte invalide !');
      return;
    }

    console.log('➡️ Déplacer carte', cardId, 'vers colonne', newColumnId);

    // Déplacement visuel
    container.appendChild(card);

    // Appel backend
    fetch(`/api/cards/${cardId}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ columns: newColumnId })
    })
      .then(response => {
        if (!response.ok) throw new Error("Erreur lors du déplacement");
        return response.json();
      })
      .then(() => {
        console.log(`✅ Carte ${cardId} déplacée dans colonne ${newColumnId}`);
        // showSuccessToast?.();
        alert(`Carte ${cardId} déplacée dans la colonne ${newColumnId}`);
      })
      .catch(err => {
        console.error('❌ Erreur backend :', err);
        alert('Erreur lors du déplacement de la carte');
      });
  });
}
