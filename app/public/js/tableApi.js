


// app/public/js/table.js
// 💾 Création du tableau dans Symfony 
function createTable(title) {
    fetch('/api/tables', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
           
        },
        body: JSON.stringify({ title: title })
    })
    .then(response => response.json())
    .then(data => {
        if (data.tableau) {
            console.log('Tableau créé :', data.tableau);
            // Tu peux maintenant afficher ce tableau dans ton interface  
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



 
  document.addEventListener('DOMContentLoaded', () => {
    const form      = document.getElementById('add-member-form');
    if (!form) return;                       // Formulaire masqué si l’utilisateur n’est pas membre

    const feedback  = document.getElementById('add-member-feedback');
    const emailInput = document.getElementById('email-to-add');
    const tableId   = document.querySelector('main#board')?.dataset.tableId;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      feedback.innerHTML = '';               // Nettoie les messages précédents

      const email = emailInput.value.trim();
      if (!email) {
        feedback.innerHTML = `<div class="alert alert-warning">Veuillez saisir un email.</div>`;
        return;
      }

      try {
        const res  = await fetch(`/tables/${tableId}/add-user-by-email`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new URLSearchParams({ email })
        });

        const data = await res.json();       // Toutes les réponses renvoient du JSON

        // ✋ Cas : l’utilisateur connecté n’est pas membre → 403
        if (res.status === 403) {
          feedback.innerHTML = `<div class="alert alert-danger">${data.error ?? 'Accès refusé : vous n’êtes pas membre de ce tableau.'}</div>`;
          return;
        }

        // Cas : autre erreur (400, 404, 500…)
        if (!res.ok || data.error) {
          feedback.innerHTML = `<div class="alert alert-danger">${data.error ?? 'Une erreur est survenue.'}</div>`;
          return;
        }

        // ✅ Succès
        feedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        emailInput.value = '';

      } catch (err) {
        console.error(err);
        feedback.innerHTML = `<div class="alert alert-danger">Erreur réseau ; réessayez plus tard.</div>`;
      }
    });
  });
 

// Fonction pour ajouter un utilisateur à un tableau par email