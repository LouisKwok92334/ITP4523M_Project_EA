let orders = [];
let modal = document.querySelector('#delete-modal');
let closeButton = document.querySelector('.close');
let confirmDeleteButton = document.querySelector('#confirm-delete');
let cancelDeleteButton = document.querySelector('#cancel-delete');

closeButton.onclick = function() {
    modal.style.display = "none";
}

confirmDeleteButton.onclick = async function() {
    // Call the delete_order.php script
    let response = await fetch('../includes/delete_order.php', {
        method: 'POST',
        body: JSON.stringify({orderID: modal.dataset.orderId, itemID: modal.dataset.itemId})  // Updated
    });

    if (response.ok) {
        let res = await response.json();
        if (res.status === 'success') {
          // Add an alert message
          alert('Order deleted successfully!');
          // Reload orders
          await loadOrder();
        } else {
          alert('Failed to delete order: ' + res.error);
        }
    } else {
        let error = await response.json();
        alert('Failed to delete order: ' + error.error);
    }
  
    modal.style.display = "none";
}
  
// When the user clicks on Cancel, close the modal
cancelDeleteButton.onclick = function() {
    modal.style.display = "none";
}

async function loadOrder() {
  const res = await fetch('../includes/get_orders.php');
  orders = await res.json();
  
  displayOrders(orders);
  
  // 默认排序
  document.querySelector("#sort-by").value = 'orderID';
  document.querySelector("#direction").value = 'asc';
  sortOrders();
}

function displayOrders(orders) {
    let tableBody = document.querySelector("#orderTable tbody");
    
    // 清空现有的行
    tableBody.innerHTML = "";
  
    orders.forEach(order => {
      let row = document.createElement('tr');
  
      Object.entries(order).forEach(([key, value]) => {
        let cell = document.createElement('td');
  
        if (key === 'ImageFile') {
          let img = document.createElement('img');
          img.src = "../images/" + value;
          img.alt = 'Item Image';
          img.style.width = '50px'; // Set a suitable width
          cell.appendChild(img);
        } else {
          cell.textContent = value;
        }
        
        row.appendChild(cell);
      });
  
      // Add a Delete button
      let deleteCell = document.createElement('td');
      let deleteButton = document.createElement('button');
      deleteButton.textContent = 'Delete';
      deleteButton.className = 'delete-button';
  
      // Check if order is within two days of delivery
      let deliveryDate = new Date(order.deliveryDate);
      let now = new Date();
      let twoDays = 2 * 24 * 60 * 60 * 1000;  // two days in milliseconds
      let nowDateOnly = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      if ((deliveryDate - nowDateOnly) <= twoDays) {
        deleteButton.disabled = true;
      }
  
      deleteButton.addEventListener('click', function() {
        document.querySelector('#delete-modal').style.display = 'block';
        // Store the order ID and item ID in the modal
        modal.dataset.orderId = order.orderID;
        modal.dataset.itemId = order.itemID;  // Updated
    });
  
      deleteCell.appendChild(deleteButton);
      row.appendChild(deleteCell);
  
      tableBody.appendChild(row);
    });
}

function sortOrders() {
  // 获取用户的选择
  let sortBy = document.querySelector("#sort-by").value;
  let direction = document.querySelector("#direction").value;

  // 根据用户的选择排序订单
  orders.sort((a, b) => {
    let aValue = a[sortBy];
    let bValue = b[sortBy];
    
    // 如果是Total Order Amount列，解析为数字进行比较
    if (sortBy === 'TotalOrderAmount') {
      aValue = parseFloat(aValue);
      bValue = parseFloat(bValue);
    }
    
    if (aValue < bValue) {
      return direction === 'asc' ? -1 : 1;
    } else if (aValue > bValue) {
      return direction === 'asc' ? 1 : -1;
    } else {
      return 0;
    }
  });

  // 显示排序后的订单
  displayOrders(orders);
}

document.querySelector("#sort-by").addEventListener('change', sortOrders);
document.querySelector("#direction").addEventListener('change', sortOrders);

window.addEventListener('DOMContentLoaded', (event) => {
    loadOrder();
});