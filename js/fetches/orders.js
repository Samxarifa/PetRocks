window.addEventListener('load', function() {
    const url = "//comp-server.uhi.ac.uk/~22000454/HND/PetRocks/";
    const limit = 20;
    let offset = 0;
    let fetchComplete = false;
    let isFetching = false;

    /** 
     * Fetches Orders Data from API endpoint and calls the writeOrders function
    */
    function fetchOrders() {
        fetch(`${url}/api/orders?limit=${limit}&offset=${offset}`,{
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
            writeOrders(data);
        })
        .catch(error => {
            console.log(error)
        })
    }

    /**
     * Gets a list of Orders and adds each order to the page
     * @typedef {Object} product
     * @property {string} name
     * @property {number} quantity
     * 
     * @typedef {Object} order
     * @property {number} orderId
     * @property {string} username
     * @property {string} orderDate
     * @property {string} price
     * @property {product[]} products
     * 
     * @param {order[]} orders
     */
    function writeOrders(orders) {
        const list = document.querySelector('.admin_list');
        orders.forEach(order => {
            // Creates new list item and adds html content to it
            const listItem = document.createElement('li');
            listItem.innerHTML = `
                <div class="list_header">
                    <h3>Order Ref: ${order.orderId}</h3>
                    <img class="list_arrow" src="../img/arrow.png" height='25' />
                </div>
                <div class="list_content">
                    <div>
                        <h4>Details</h4>
                        <p>Placed By: ${order.username}</p>
                        <p>Placed On: ${order.orderDate}</p>
                        <p>Price: £${order.price}</p>
                    </div>
                    <div>
                        <h4>Products</h4>
                        ${order.products.map((product) => {
                            return `<p>${product.quantity} x ${product.name}</p>`
                        }).join('')}

                    </div>
                    <div class='buttons' data-order-id=${order.orderId}>
                        <button class='btn_delete'>Delete</button>
                    </div>
                </div>
            `;
            
            list.append(listItem); //Adds new item to list
        });
        isFetching = false;
    }

    // Fetches the first 20 Orders on page load
    fetchOrders();

    // Runs when user scolls
    window.addEventListener('scroll',() => {
        // If currently getting data or no more data to get then doesn't run next code
        if (isFetching || fetchComplete) {
            return;
        }
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = document.documentElement.scrollTop;
        const clientHeight = document.documentElement.clientHeight;
        // If scrolled to bottom of page, fetch the next 20 Orders
        if (scrollTop + clientHeight >= scrollHeight - 100) {
            isFetching = true;
            fetchOrders();
        }
    })

    // Handles Deleting Order Button
    document.querySelector('.admin_list').addEventListener('click', async function (event) {
        if (event.target.closest('.btn_delete') && confirm('Are you sure you want to DELETE this order?')) {
            // Get id from html data and format for post request
            const formData = new FormData();
            formData.append('orderId', event.target.closest('.buttons').dataset.orderId)
            // Send Post Request
            let response = await fetch(`${url}/api/deleteOrder`, {
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
        }
     });
});