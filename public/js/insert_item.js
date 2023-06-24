window.onload = async function() {
    let response = await fetch('../includes/get_supplier_id.php', {
      method: 'POST',
      credentials: 'include' // Send cookies
    });
  
    if (response.ok) {
      let result = await response.json();
      if (result.status === 'success') {
        document.querySelector("#supplierID").value = result.supplierID;
      }
    }
  };

  document.querySelector("#insert-item-form").addEventListener('submit', async function(e) {
    e.preventDefault();
  
    // Gather form data
    let formData = new FormData();
    formData.append('itemName', document.querySelector("#itemName").value);
    formData.append('ImageFile', document.querySelector("#itemImage").files[0]);
    formData.append('itemDescription', document.querySelector("#itemDescription").value);
    formData.append('stockItemQty', document.querySelector("#stockItemQty").value);
    formData.append('price', document.querySelector("#price").value);
  
    // Send a POST request to the server
    let response = await fetch('../includes/insert_item.php', {
      method: 'POST',
      body: formData
    });
  
    if (response.ok) {
        const responseBody = await response.text();
      
        try {
          const result = JSON.parse(responseBody);
      
          if (result.status === 'success') {
            alert('Item inserted successfully!');
          } else {
            alert('Failed to insert item: ' + result.error);
          }
        } catch (error) {
          console.error('Failed to parse JSON:', responseBody, error);
        }
      } else {
        let error = await response.json();
        alert('Failed to insert item: ' + error.error);
      }
  });
  