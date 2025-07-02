

// document.addEventListener('DOMContentLoaded', () => {
//   const bell = document.getElementById('notif-bell');
//   const notifList = document.getElementById('notif-list');
//   const notifCount = document.getElementById('notif-count');
//   const notifItems = document.getElementById('notif-items');

//   if (!bell || !notifList || !notifCount || !notifItems) {
//     console.warn("🔔 Composants de notifications non trouvés dans le DOM");
//     return;
//   }

//   // ▶️ Toggle ouverture/fermeture de la boîte
//   bell.addEventListener('click', () => {
//     notifList.classList.toggle('d-none');
//   });

//   // ➕ Fonction pour ajouter une notification
//   window.pushNotification = function (message) {
//     const li = document.createElement('li');
//     li.className = "p-2 border-bottom small text-dark";
//     li.textContent = message;

//     notifItems.prepend(li); // Ajoute en haut

//     // Mise à jour du compteur
//     const current = parseInt(notifCount.textContent || '0', 10);
//     notifCount.textContent = current + 1;
//     notifCount.classList.remove('d-none');
//   };

//   // 🟣 (optionnel) Charger les notifications depuis une API
//   const board = document.querySelector('#board');
//   const tableId = board?.dataset.tableId;

//   if (tableId) {
//    fetch(`/api/notification?tableId=${tableId}`)
//   .then(res => {
//     if (!res.ok) throw new Error("API error : " + res.status);
//     return res.json();
//   })
//   .then(data => {
//     data.forEach(n => pushNotification(n.message));
//   })
//   .catch(err => console.error("❌ Erreur chargement notification :", err));

//   }
// });



document.addEventListener('DOMContentLoaded', () => {
  /* ------------- DROP-DOWN (page tableau) ------------- */
  const bell       = document.getElementById('notif-bell');
  const listBox    = document.getElementById('notif-list');
  const listUl     = document.getElementById('notif-items');
  const countBadge = document.getElementById('notif-count');
  const clearAllDD = document.getElementById('notif-clear-all');

  /* ------------- LISTE COMPLÈTE (page /notifications) ------------- */
  const fullListUl = document.getElementById('notif-full-list');
  const clearAllPg = document.getElementById('clear-all-btn');

  /* ----------- Fonction utilitaire d’affichage ----------- */
  function renderNotif(liParent, {id, message, createdAt}) {
    const li = document.createElement('li');
    li.dataset.id = id;
    li.className  = 'p-2 border-bottom small d-flex justify-content-between align-items-center';

    const span = document.createElement('span');
    span.textContent = message;
    li.appendChild(span);

    const btn = document.createElement('button');
    btn.className = 'btn btn-sm btn-outline-danger ms-2';
    btn.innerHTML = '🗑';
    btn.addEventListener('click', () => deleteNotif(id, li));
    li.appendChild(btn);

    liParent.prepend(li);
  }

  /* ----------- Requête DELETE pour une notif ----------- */
  async function deleteNotif(id, liElement) {
    if (!confirm('Supprimer cette notification ?')) return;
    try {
      const res = await fetch(`/api/notification/${id}`, { method: 'DELETE' });
      if (!res.ok) throw new Error('API DELETE error');
      liElement.remove();
    } catch (e) {
      alert('Erreur suppression');
      console.error(e);
    }
  }

  /* ----------- Requête DELETE pour TOUT supprimer ----------- */
//   async function clearAll(tableId) {
//     if (!confirm('Supprimer TOUTES les notifications ?')) return;
//     try {
//       const res = await fetch(`/api/notification?tableId=${tableId}`, { method: 'DELETE' });
//       if (!res.ok) throw new Error('API CLEAR ALL error');
//       if (listUl)  listUl.innerHTML  = '';
//       if (fullListUl) fullListUl.innerHTML = '';
//     } catch (e) {
//       alert('Erreur suppression globale');
//       console.error(e);
//     }
//   }

  /* ----------- Drop-down toggle ----------- */
  if (bell && listBox) bell.addEventListener('click', () => listBox.classList.toggle('d-none'));

  /* ----------- Récupération initiale ----------- */
  const board = document.querySelector('#board'); // présent uniquement sur show.html.twig
  const tableId = board?.dataset.tableId;

  if (tableId && listUl) { // dropdown
    fetch(`/api/notification?tableId=${tableId}`)
      .then(res => res.json())
      .then(data => {
        data.forEach(n => renderNotif(listUl, n));
        if (data.length) {
          countBadge.textContent = data.length;
          countBadge.classList.remove('d-none');
        }
      });
  }

  /* ----------- Clear all boutons ----------- */
  if (clearAllDD) clearAllDD.addEventListener('click', () => clearAll(tableId));
  if (clearAllPg && tableId) clearAllPg.addEventListener('click', () => clearAll(tableId));


  // Pour la page /notifications : suppression individuelle
if (fullListUl) {
  fullListUl.querySelectorAll('.delete-notif-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const li = btn.closest('li');
      const id = li.dataset.id;
      deleteNotif(id, li);
    });
  });
}

});


window.pushNotification = function (message) {
  const notifItems = document.getElementById('notif-items');
  const notifCount = document.getElementById('notif-count');

  if (!notifItems || !notifCount) return;

  // Crée un élément li pour l'affichage
  const li = document.createElement('li');
  li.className = "p-2 border-bottom small text-dark";
  li.textContent = message;
  notifItems.prepend(li); // ajout en haut

  // Met à jour le compteur
  const current = parseInt(notifCount.textContent || '0', 10);
  notifCount.textContent = current + 1;
  notifCount.classList.remove('d-none');
};
