window.onload = function () {
    const accountButton = document.querySelector('.account_button');
    const divDropdown = document.querySelector('#div_dropdown');
    const arrow = document.querySelector('#account_arrow');
    const ignores = ['.account_button', '#div_dropdown'];
    const body = document.querySelector('body');
    const hammer = new Hammer(body);

    /** Shows account menu */
    function openMenu() { 
        divDropdown.classList.add('div_dropdown_focused');
        arrow.classList.add('account_arrow_open');
        window.scrollTo(0, 0); //snaps to top of page when menu is opened
        // Disables scrolling on mobile for full screen menu
        if (window.innerWidth < 650) {
            body.style.overflowY = 'hidden';
        }
    }

    /** Hides account menu */
    function closeMenu() {
        divDropdown.classList.remove('div_dropdown_focused');
        arrow.classList.remove('account_arrow_open');
        // Enables scrolling on mobile for full screen menu close
        if (window.innerWidth < 650) {
            body.style.overflowY = 'unset';
        }
    }

    // Handles Mobile Swipe guestures to open and close the account menu
    hammer.on('swiperight', () => { window.innerWidth < 650 && openMenu() });
    hammer.on('swipeleft', () => { window.innerWidth < 650 && closeMenu() });

    // Toggles the menu when account button is clicked
    accountButton.addEventListener('click', function (event) {
        if (divDropdown.classList.contains('div_dropdown_focused')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Closes the menu if clicked away from
    window.addEventListener('click',function (event) {
        if (divDropdown.classList.contains('div_dropdown_focused')) {
            let ignore = false;
            ignores.forEach(item => {
                if (event.target.closest(item)) {
                    ignore = true;
                };
            });

            if (!ignore) {
                closeMenu();
            };
        };
    });

    // When user scrolls away from menu, closes automatically
    window.addEventListener('scroll', function () {
        if (divDropdown.classList.contains('div_dropdown_focused') && window.scrollY != 0) {
            closeMenu();
        };
    });
}