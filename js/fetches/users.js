window.addEventListener('load', function() {
    const url = "//comp-server.uhi.ac.uk/~22000454/HND/PetRocks/";
    const limit = 20;
    let offset = 0;
    let fetchComplete = false;
    let isFetching = false;

    // Fetches Users Data from API endpoint and calls writeUsers function
    function fetchUsers() {
        fetch(`${url}/api/users?limit=${limit}&offset=${offset}`,{
            method: 'GET',
            headers: {'Content-Type': 'application-json'}
        })
        .then(response => {
            return response.json()
        })
        .then(data => {
            offset += limit
            if (data.length == 0) {
                fetchComplete = true;
            }
            writeUsers(data);
        })
        .catch(error => {
            console.log(error)
        })
    }
 
    /**
     * Gets a list of users and adds each user to the page
     * @typedef {Object} user
     * @property {number} customerId
     * @property {string} forename
     * @property {string} surname
     * @property {string} street
     * @property {string} town
     * @property {string} postcode
     * @property {string} email
     * @property {string} username
     * @property {number} memberType
     * @property {number} isSuspended
     * 
     * @param {user[]} users
     */
    function writeUsers(users) {
        const list = document.querySelector('.admin_list');
        users.forEach(user => {
            // Creates new list item and adds html content to it
            const listItem = document.createElement('li');
            let tier;
            if (user.memberType === 0) {
                tier = "Bronze";
            } else if (user.memberType === 1) {
                tier = "Silver";
            } else {
                tier = "Gold";
            }

            let suspendButtonText;
            if (user.isSuspended === 1) {
                suspendButtonText = 'Un-Suspend';
            } else {
                suspendButtonText = 'Suspend';
            }

            listItem.innerHTML = `
                <div class="list_header">
                    <h3 class='${user.isSuspended? "h3-suspended":""}'>${user.username}</h3>
                    <img class="list_arrow" src="../img/arrow.png" height='25' />
                </div>
                <div class="list_content">
                    <div>
                        <h4>Details</h4>
                        <p>ID: ${user.customerId}</p>
                        <p>Name: ${user.forename} ${user.surname}</p>
                        <p>Email: ${user.email}</p>
                        <p>Tier: ${tier}</p>
                    </div>
                    <div>
                        <h4>Address</h4>
                        <p>${user.street}</p>
                        <p>${user.town}</p>
                        <p>${user.postcode}</p>
                    </div>
                    <div class='buttons' data-account-id=${user.customerId}>
                        <button class='btn_suspend' data-suspended=${user.isSuspended}>${suspendButtonText}</button>
                        <button class='btn_delete'>Delete</button>
                    </div>
                </div>
            `;

            list.append(listItem); //Addes new list item to list
        });
        isFetching = false;
    }

    // Fetches the first 20 users on page load
    fetchUsers();

    // Runs when user scolls
    window.addEventListener('scroll',() => {
        // If currently getting data or no more data to get then doesn't run next code
        if (isFetching || fetchComplete) {
            return;
        }
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = document.documentElement.scrollTop;
        const clientHeight = document.documentElement.clientHeight;
        // If scrolled to bottom of page, fetch the next 20 users
        if (scrollTop + clientHeight >= scrollHeight - 100) {
            isFetching = true;
            fetchUsers();
        }
    })

    //Runs when list is clicked
    document.querySelector('.admin_list').addEventListener('click', async function (event) { 
        // If click was on the delete account button and the user is sure they want to delete
        if (event.target.closest('.btn_delete') && confirm('Are you sure you want to DELETE this account?')) {
            // Get id from html data and format for post request
            const formData = new FormData();
            formData.append('custId',event.target.closest('.buttons').dataset.accountId)
            // Send Post Request
            let response = await fetch(`${url}/api/deleteAccount`, {
                method: "POST",
                body: formData
            });
            // Shows success or fail dialog
            let jsonResponse = await response.json();
            const modal = document.querySelector('#dlg_feedback');
            const modalText = modal.querySelector('span');
            modalText.textContent = `${jsonResponse[0]}: ${jsonResponse[1]}`;
            modal.showModal();
            // If successful, delete item from page
            if (response.ok) {
                const li = event.target.closest('li');
                li.remove();
                
            }
        } else if (event.target.closest('.btn_suspend')) {
            const formData = new FormData();
            formData.append('custId', event.target.closest('.buttons').dataset.accountId);
            formData.append('suspend', Math.abs(parseInt(event.target.closest('.btn_suspend').dataset.suspended)-1));

            let response = await fetch(`${url}/api/suspendAccount`, {
                method: "POST",
                body: formData
            });
            // Shows success or fail dialog
            let jsonResponse = await response.json();
            const modal = document.querySelector('#dlg_feedback');
            const modalText = modal.querySelector('span');
            modalText.textContent = `${jsonResponse[0]}: ${jsonResponse[1]}`;
            modal.showModal();
            // If success, change the list item to reflect db changes
            if (response.ok) {
                const li = event.target.closest('li');
                const h3 = li.querySelector('h3');
                event.target.closest('.btn_suspend').dataset.suspended = Math.abs(parseInt(event.target.closest('.btn_suspend').dataset.suspended) - 1);
                let suspendData = event.target.closest('.btn_suspend').dataset.suspended;
                suspendData == 1 ? h3.classList.add('h3-suspended') : h3.classList.remove('h3-suspended');
                event.target.closest('.btn_suspend').textContent = suspendData == 1 ? "Un-Suspend" : "Suspend";
            }
        }
    });

});