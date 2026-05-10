(function () {
  'use strict';

  // Mobile burger menu
  const burger = document.querySelector('.ds-burger');
  const navWrapper = document.querySelector('.ds-nav-wrapper');
  if (burger && navWrapper) {
    burger.addEventListener('click', () => {
      navWrapper.classList.toggle('open');
      burger.setAttribute('aria-expanded', navWrapper.classList.contains('open'));
    });
  }

  // Fermer la bannière info-rentree
  const closeBtn = document.querySelector('.ds-info-rentree .close-btn');
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      const banner = closeBtn.closest('.ds-info-rentree');
      if (banner) banner.style.display = 'none';
      try { sessionStorage.setItem('ds_info_rentree_closed', '1'); } catch (e) {}
    });
    try {
      if (sessionStorage.getItem('ds_info_rentree_closed') === '1') {
        const banner = closeBtn.closest('.ds-info-rentree');
        if (banner) banner.style.display = 'none';
      }
    } catch (e) {}
  }

  // Mise à jour automatique de l'année dans le footer
  document.querySelectorAll('[data-current-year]').forEach(el => {
    el.textContent = String(new Date().getFullYear());
  });

  // Smooth scroll pour les liens internes
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', e => {
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Active nav link
  const path = window.location.pathname;
  document.querySelectorAll('.ds-nav .nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href && href !== '/' && path.startsWith(href)) link.classList.add('active');
    else if (href === '/' && path === '/') link.classList.add('active');
  });
})();
