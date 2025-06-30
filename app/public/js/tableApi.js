


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

