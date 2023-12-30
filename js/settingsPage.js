function validateUpdate() {
    // Handles checking for new data added to form before submitting
    const form = document.querySelector('#update_form');
    let data = false;
    Array.from(form.elements).forEach((input) => {
        // If value in any input or tier select has changed, form submits
        if (input.value && input.id != 'select_tier') {
            data=true;
        } else if (input.id === 'select_tier' && !document.querySelectorAll('option')[input.selectedIndex].defaultSelected) {
            data=true;
        }
    });
    // If no data in form show dialog to user telling them form was not submitted
    if (!data) {
        const modal = document.querySelector('#dlg_feedback');
        const modalText = modal.querySelector('span');
        modalText.textContent = 'Error: Nothing to Update';
        modal.showModal();
    }
    return data;
}

window.onload = function() {
    
    const nav = document.querySelector('aside');
    const menuButton = document.querySelector('#btn_menu');

    const body = document.querySelector('body');

    const updateButton = document.querySelector('#update_button');

    const navDetailsButton = document.querySelector('#nav_details');
    const navAddressButton = document.querySelector('#nav_address');
    const navTierButton = document.querySelector('#nav_tier');
    const navDeleteButton = document.querySelector('#nav_delete');
    
    const detailsPage = document.querySelector('#details');
    const addressPage = document.querySelector('#address');
    const tierPage = document.querySelector('#tier');
    const deletePage = document.querySelector('#delete');

    const dlgFeedback = document.querySelector('#dlg_feedback');
    const dlgFeedbackClose = document.querySelector('#dlg_feedback_close');

    // Handles Swipe gestures on mobile for nav
    const hammer = new Hammer(body);

    hammer.on('swiperight', () => nav.classList.add('nav_focused'));
    hammer.on('swipeleft', () => nav.classList.remove('nav_focused'));

    // Handle Closing Feedback Dialog when button pressed
    dlgFeedbackClose.addEventListener('click',function() {
        dlgFeedback.close();
    });

    // Handle Closing Feeback Dialog when escape or enter keys pressed
    dlgFeedback.addEventListener('keydown', function(event) {
        if (event.keyCode === 27 || event.keyCode === 13) {
            dlgFeedback.close();
        }
    });

    // Function to hide all "pages" before showing one of them
    function hideAll() {
        detailsPage.style.display = 'none';
        addressPage.style.display = 'none';
        tierPage.style.display = 'none';
        deletePage.style.display = 'none';
    }

    hideAll();
    detailsPage.style.display = 'flex';     //Shows details page when first loaded

    // Handles switching to details page
    navDetailsButton.addEventListener('click',() => {
        updateButton.style.display = 'block';

        hideAll();
        detailsPage.style.display = 'flex';
    });
    // Handles switching to address page
    navAddressButton.addEventListener('click',() => {
        updateButton.style.display = 'block';

        hideAll();
        addressPage.style.display = 'flex';
    });
    // Handles switching to tier page
    navTierButton.addEventListener('click',() => {
        updateButton.style.display = 'block';

        hideAll();
        tierPage.style.display = 'flex';
    });
    // Handles switching to delete page (Also hides form submit button for this page)
    navDeleteButton.addEventListener('click',() => {
        updateButton.style.display = 'none';

        hideAll();
        deletePage.style.display = 'flex';
    });

    // Handle Hiding NAV on mobile when button inside nav is pressed
    document.querySelectorAll('aside > ul > li > a').forEach((val) => {
        val.addEventListener('click', () => nav.classList.remove('nav_focused'));
    });

    // toggles mobile nav when menu button pressed
    menuButton.addEventListener('click', () => nav.classList.toggle('nav_focused'));
};