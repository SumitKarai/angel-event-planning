(function () {
  var siteHeader = document.querySelector('.site-header');
  var menuToggle = document.querySelector('.menu-toggle');
  var mainNav = document.querySelector('#main-nav');
  var sectionLinks = mainNav ? Array.prototype.slice.call(mainNav.querySelectorAll('a[href^="#"]')) : [];
  var revealTargets = Array.prototype.slice.call(
    document.querySelectorAll(
      '.services h2, .services-intro, .service-card, .about-us .container > *, .advertising .container > *, .client-card'
    )
  );
  var trackedSections = sectionLinks
    .map(function (link) {
      var targetId = link.getAttribute('href');
      if (!targetId || targetId === '#') return null;

      var section = document.querySelector(targetId);
      if (!section) return null;

      return { link: link, section: section };
    })
    .filter(Boolean);

  var syncActiveNavLink = function () {
    if (!trackedSections.length) return;

    var scrollPosition = window.scrollY + 120;
    var current = trackedSections[0];

    trackedSections.forEach(function (item) {
      if (item.section.offsetTop <= scrollPosition) {
        current = item;
      }
    });

    trackedSections.forEach(function (item) {
      item.link.classList.toggle('is-active', item === current);
    });
  };

  var setupRevealAnimations = function () {
    if (!revealTargets.length) return;

    revealTargets.forEach(function (element, index) {
      element.classList.add('reveal');
      element.classList.add('stagger-' + ((index % 4) + 1));
    });

    if (!('IntersectionObserver' in window)) {
      revealTargets.forEach(function (element) {
        element.classList.add('is-visible');
      });
      return;
    }

    var revealObserver = new IntersectionObserver(
      function (entries, observer) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        });
      },
      {
        threshold: 0.16,
        rootMargin: '0px 0px -40px 0px'
      }
    );

    revealTargets.forEach(function (element) {
      revealObserver.observe(element);
    });
  };

  if (siteHeader) {
    var syncHeaderScrollState = function () {
      siteHeader.classList.toggle('is-scrolled', window.scrollY > 10);
    };

    syncHeaderScrollState();
    window.addEventListener('scroll', syncHeaderScrollState);
  }

  syncActiveNavLink();
  setupRevealAnimations();
  window.addEventListener('scroll', syncActiveNavLink);

  if (!menuToggle || !mainNav) return;

  menuToggle.addEventListener('click', function () {
    var isOpen = mainNav.classList.toggle('is-open');
    menuToggle.setAttribute('aria-expanded', String(isOpen));
  });
})();
