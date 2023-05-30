window.onload = function () {
    document.querySelector("body").addEventListener('click', function () { //Hides account menu when user clicks body
        document.querySelector('#div_dropdown').style.display = 'none';
    })
}

function accountDropDown() { //Shows account menu
    setTimeout(function () {
        dropdown = document.querySelector('#div_dropdown');
        dropdown.style.display = 'block';
    }, 5);

}

