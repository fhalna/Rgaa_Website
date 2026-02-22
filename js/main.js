document.addEventListener('DOMContentLoaded', function () {
	var menuOpen = false;
	var headsite = document.querySelector('.headsite');
	var heightFullMenu = headsite ? headsite.offsetHeight : 0;
	var btnnav = document.getElementById('btnnav');
	var nav = document.getElementById('nav');

	/**
	 * Menu open/close management
	 */

	function closeMenu() {
		document.body.classList.remove('opened-menu');
		if (nav) nav.classList.remove('nav-opened');
		if (btnnav) btnnav.setAttribute('aria-expanded', 'false');
		menuOpen = false;
	}

	function openMenu() {
		document.body.classList.add('opened-menu');
		if (nav) nav.classList.add('nav-opened');
		if (btnnav) btnnav.setAttribute('aria-expanded', 'true');
		menuOpen = true;
	}

	if (btnnav) {
		openMenu();
		btnnav.addEventListener('click', function () {
			menuOpen ? closeMenu() : openMenu();
		});
	}

	/**
	 * Criteria filter management
	 */

	document.querySelectorAll('.filter input').forEach(function (input) {
		input.addEventListener('click', function () {
			var id = this.id;
			var sections = document.querySelectorAll('section[data-level="' + id + '"]');
			var anchors = document.querySelectorAll('a[data-level="' + id + '"]');

			if (this.checked) {
				this.setAttribute('aria-checked', 'true');
				sections.forEach(function (s) { s.style.display = 'block'; });
				anchors.forEach(function (a) { a.classList.remove('disabled'); });
			} else {
				this.setAttribute('aria-checked', 'false');
				sections.forEach(function (s) { s.style.display = 'none'; });
				anchors.forEach(function (a) { a.classList.add('disabled'); });
			}
		});
	});

	/**
	 * Active anchor indicator on "Criteria" and "Glossary" pages
	 */

	if (document.querySelector('.toc-follow')) {
		window.addEventListener('scroll', function () {
			var h2 = document.querySelector('h2');
			var paddingTopThematique = h2 ? parseInt(getComputedStyle(h2).paddingTop, 10) : 0;
			var position = window.scrollY + heightFullMenu - paddingTopThematique;

			var elements = document.querySelector('.criteres')
				? document.querySelectorAll('.thematique')
				: document.querySelectorAll('h2');

			var tocItems = document.querySelectorAll('#navtoc > nav > ul > li');

			elements.forEach(function (el) {
				var target = el.getBoundingClientRect().top + window.scrollY;
				var id = el.getAttribute('id');

				if (position >= target) {
					tocItems.forEach(function (li) { li.classList.remove('on'); });
					var activeLink = document.querySelector('#navtoc > nav > ul > li > a[href="#' + id + '"]');
					if (activeLink) {
						activeLink.parentElement.classList.add('on');
					}
				}
			});
		});
	}

	/**
	 * Open main menu when activating the "Go to menu" skip link
	 */

	var goToMenu = document.getElementById('go-to-menu');
	if (goToMenu) {
		goToMenu.addEventListener('click', openMenu);
		goToMenu.addEventListener('keydown', function (e) {
			if (e.key === 'Enter') {
				openMenu();
				var firstLink = nav ? nav.querySelector('a') : null;
				if (firstLink) firstLink.focus();
			}
		});
	}
});
