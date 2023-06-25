async function fetchReportData() {
    const response = await fetch('../includes/fetchData.php');
    const data = await response.json();
    const { items, orders } = data;
  
    const reportData = items.map((item) => {
      const itemOrders = orders.filter((order) => order.itemID === item.itemID);
      const totalNumber = itemOrders.reduce((acc, order) => acc + parseInt(order.orderQty, 10), 0);
      const totalSalesAmount = itemOrders.reduce((acc, order) => acc + (parseInt(order.orderQty, 10) * parseFloat(order.itemPrice)), 0);
  
      return {
        id: item.itemID,
        name: item.itemName,
        imageUrl: '../images/' + item.ImageFile,
        totalNumber: totalNumber,
        totalSalesAmount: totalSalesAmount.toFixed(2),
      };
    });
  
    return reportData;
  }
  
  function displayReportData(reportData) {
    const tableBody = document.querySelector("#report-table tbody");
  
    reportData.forEach(item => {
      const row = document.createElement("tr");
  
      const idCell = document.createElement("td");
      idCell.textContent = item.id;
      row.appendChild(idCell);
  
      const nameCell = document.createElement("td");
      nameCell.textContent = item.name;
      row.appendChild(nameCell);
  
      const imageCell = document.createElement("td");
      const image = document.createElement("img");
      image.src = item.imageUrl;
      imageCell.appendChild(image);
      row.appendChild(imageCell);
  
      const totalNumberCell = document.createElement("td");
      totalNumberCell.textContent = item.totalNumber;
      row.appendChild(totalNumberCell);
  
      const totalSalesAmountCell = document.createElement("td");
      totalSalesAmountCell.textContent = item.totalSalesAmount;
      row.appendChild(totalSalesAmountCell);
  
      tableBody.appendChild(row);
    });
  }
  
  async function generateReport() {
    const reportData = await fetchReportData();
    displayReportData(reportData);
  }
  
  generateReport();