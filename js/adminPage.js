window.onload = function() {

    const nav = document.querySelector('aside');
    const menuButton = document.querySelector('#btn_menu');

    const body = document.querySelector('body');

    const dlgFeedback = document.querySelector('#dlg_feedback');
    const dlgFeedbackClose = document.querySelector('#dlg_feedback_close');

    // Handle Swipe Gestures for mobile nav
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

    // Handle Hiding NAV on mobile when button inside nav is pressed
    document.querySelectorAll('aside > ul > li > a').forEach((val) => {
        val.addEventListener('click', () => nav.classList.remove('nav_focused'));
    });

    // toggles mobile nav when menu button pressed
    menuButton.addEventListener('click', () => nav.classList.toggle('nav_focused'));

    // Handles toggle expanding list item when item "header" is clicked
    // Coded in such away to instatly work on new list items that are dynamically added to the page with JS
    document.querySelector('.admin_list').addEventListener('click', function(event) {
        // If click was inside the list items header then toggle expand
        if (event.target.closest('.list_header')) {
            let clickedHeader = event.target.closest('.list_header');
            let content = clickedHeader.nextElementSibling;
            let arrow = clickedHeader.querySelector('.list_arrow');
            
            // Gets all list item headers on page
            let headers = document.querySelectorAll('.list_header');
    
            // For each header, close if not the new one
            headers.forEach(function(header) {
                if (header !== clickedHeader) {
                    let content = header.nextElementSibling;
                    let arrow = header.querySelector('.list_arrow');
                    
                    content.classList.remove('list_content_shown');
                    arrow.classList.remove('list_arrow_inverted');
                }
            });

            content.classList.toggle('list_content_shown');
            arrow.classList.toggle('list_arrow_inverted');
        }
    });
}