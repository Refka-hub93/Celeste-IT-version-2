 
  document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.querySelector('.navbar-toggler');
    const navMenu = document.getElementById('navbarNavDropdown');

    // S'assurer que le menu est fermé au chargement (au cas où il est ouvert par défaut)
    navMenu.classList.remove('show');

    toggleButton.addEventListener('click', function () {
      const isOpen = navMenu.classList.contains('show');

      // Si déjà ouvert, forcer la fermeture via Bootstrap Collapse API
      if (isOpen) {
        const collapse = bootstrap.Collapse.getInstance(navMenu) || new bootstrap.Collapse(navMenu, { toggle: false });
        collapse.hide();
      }
     
    });
  });
 
