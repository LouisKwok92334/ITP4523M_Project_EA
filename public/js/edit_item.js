// Fetch items from the database
async function fetchItems() {
    try {
      const response = await fetch('../includes/get_items.php');
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const items = await response.json();
  
      if (Array.isArray(items)) {
        populateTable(items);
      } else {
        console.error('Unexpected data format received:', items);
      }
    } catch (error) {
      console.error('Error fetching items:', error);
    }
  }
  
  const itemTable = document.getElementById('item-table');
  const editItemModal = document.getElementById('edit-item-modal');
  const editForm = document.getElementById('edit-item-form');
  const closeModal = document.querySelector('.close');
  const cancelBtn = document.getElementById('cancel-btn');
  const updateBtn = document.getElementById('update-btn');
  const deleteBtn = document.getElementById('delete-btn');
  
  let selectedItem;
  
  function populateTable(items) {
    const tbody = itemTable.querySelector('tbody');
    items.forEach(item => {
      const row = document.createElement('tr');
      row.innerHTML= `
        <td>${item.itemID}</td>
        <td>${item.supplierID}</td>
        <td>${item.itemName}</td>
        <td>${item.itemDescription}</td>
        <td><img src="../images/${item.ImageFile}" alt="${item.itemName}" class="item-image"></td>
        <td>${item.stockItemQty}</td>
        <td>${item.price}</td>
        <td><button class="edit-btn">Edit</button></td>
      `;
      row.querySelector('.edit-btn').addEventListener('click', () => openEditModal(item));
      tbody.appendChild(row);
    });
  }
  
  function openEditModal(item) {
    selectedItem = item;
    editForm.elements.supplierID.value = item.supplierID;
    editForm.elements.itemName.value = item.itemName;
    editForm.elements.itemDescription.value = item.itemDescription;
    editForm.elements.stockItemQty.value = item.stockItemQty;
    editForm.elements.price.value = item.price;
  
    editItemModal.style.display = 'block';
  }
  
  function closeEditModal() {
    editItemModal.style.display = 'none';
    selectedItem = null;
  }
  
async function updateItem(e) {
  e.preventDefault();

  const formData = new FormData();
  formData.append('itemID', selectedItem.itemID);
  formData.append('supplierID', editForm.elements.supplierID.value);
  formData.append('itemName', editForm.elements.itemName.value);
  formData.append('itemDescription', editForm.elements.itemDescription.value);
  formData.append('stockItemQty', editForm.elements.stockItemQty.value);
  formData.append('price', editForm.elements.price.value);

  // 检查是否有新的图片上传
  if (editForm.elements.ImageFile.files.length > 0) {
    formData.append('image', editForm.elements.ImageFile.files[0]);
  }

  try {
    const response = await fetch('../includes/update_item.php', {
      method: 'POST',
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    selectedItem.supplierID = editForm.elements.supplierID.value;
    selectedItem.itemName = editForm.elements.itemName.value;
    selectedItem.itemDescription = editForm.elements.itemDescription.value;
    selectedItem.stockItemQty = editForm.elements.stockItemQty.value;
    selectedItem.price = editForm.elements.price.value;

    alert('Item updated successfully.');
    closeEditModal();

    // 清空表格并重新填充数据
    itemTable.querySelector('tbody').innerHTML = '';
    fetchItems();
  } catch (error) {
    console.error('Error updating item:', error);
  }
}
  
  async function deleteItem() {
    try {
      const response = await fetch(`../includes/delete_item.php?itemID=${selectedItem.itemID}`, {
        method: 'GET',
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      closeEditModal();
      fetchItems();
    } catch (error) {
      console.error('Error deleting item:', error);
    }
  }
  
  editForm.addEventListener('submit', updateItem);
  cancelBtn.addEventListener('click', closeEditModal);
  deleteBtn.addEventListener('click', deleteItem);
  closeModal.addEventListener('click', closeEditModal);

  fetchItems();