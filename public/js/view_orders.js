let orders = [];

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