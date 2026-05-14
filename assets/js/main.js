(function () {
  var menuToggle = document.querySelector('.menu-toggle');
  var mainNav = document.querySelector('#main-nav');

  if (!menuToggle || !mainNav) return;

  menuToggle.addEventListener('click', function () {
    var isOpen = mainNav.classList.toggle('is-open');
    menuToggle.setAttribute('aria-expanded', String(isOpen));
  });
})();
