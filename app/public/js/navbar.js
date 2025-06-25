 
 
  const navbar     = document.getElementById('navbarNavDropdown');
  const navLinks   = navbar.querySelectorAll('.nav-link');
  const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbar); // Bootstrap 5

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (navbar.classList.contains('show')) {
        bsCollapse.hide(); // referme la navbar mobile
      }
    });
  });

  /* ---------------------------------------------------------
   * 2. Marquer le lien actif selon l’URL courante
   * --------------------------------------------------------- */
  const currentPath = window.location.pathname.replace(/\/$/, ''); // sans slash final
  navLinks.forEach(link => {
    const linkPath = link.getAttribute('href').replace(/\/$/, '');
    if (linkPath === currentPath) {
      link.classList.add('active');
    }
  });

  /* ---------------------------------------------------------
   * 3. Effet “shrink” : réduire la hauteur du header au scroll
   *    (facultatif : supprime ce bloc si tu n’en veux pas)
   * --------------------------------------------------------- */
  const header = document.querySelector('header');
  const shrinkPoint = 80; // px avant de déclencher la réduction

  function toggleShrink() {
    if (window.scrollY > shrinkPoint) {
      header.classList.add('header--shrink');
    } else {
      header.classList.remove('header--shrink');
    }
  }
  window.addEventListener('scroll', toggleShrink);
  toggleShrink(); // appel initial
 
