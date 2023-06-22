document.getElementById('updateInfoForm').addEventListener('submit', async function(event) {
    // 阻止表單的自動提交
    event.preventDefault();
  
    let currentPassword = document.getElementById('currentPassword').value;
    let newPassword = document.getElementById('newPassword').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let contactNumber = document.getElementById('contactNumber').value;
    let warehouseAddress = document.getElementById('warehouseAddress').value;
  
    let errorMessage = document.getElementById('errorMessage');
  
    // 清除之前的错误信息
    errorMessage.textContent = '';
  
    // 检查两次输入的新密码是否一致
    if (newPassword !== confirmPassword) {
      errorMessage.textContent = 'The two new passwords you entered do not match.';
      return;
    }

    // 检查所有字段是否都已填写
    if (!currentPassword || !newPassword || !confirmPassword || !contactNumber || !warehouseAddress) {
    errorMessage.textContent = 'Please fill in all fields.';
    return;
    }
  
  // 检查新密码是否与确认密码相符
    if (newPassword !== confirmPassword) {
    errorMessage.textContent = 'The two new passwords you entered do not match.';
    return;
    }
  
    // 構建要發送的數據
    let formData = new FormData();
    formData.append('currentPassword', currentPassword);
    formData.append('newPassword', newPassword);
    formData.append('contactNumber', contactNumber);
    formData.append('warehouseAddress', warehouseAddress);
  
    // 發送請求到 update_manager_info.php
    const res = await fetch('../includes/update_manager_info.php', {
      method: 'POST',
      body: formData
    });
  
    const data = await res.json();
  
    // 判斷更新是否成功
    if(data.success) {
      alert('Update successful');
    } else {
      errorMessage.textContent = 'Update failed: ' + data.error;
    }
});
  
async function loadCurrentInfo() {
    const res = await fetch('../includes/get_manager_info.php', { method: 'GET' });
    const data = await res.json();

    document.getElementById('contactNumber').value = data.contactNumber;
    document.getElementById('warehouseAddress').value = data.warehouseAddress;
}

loadCurrentInfo();

document.querySelectorAll('.password-toggle').forEach(function(toggle) {
    toggle.addEventListener('click', function() {
      const passwordInput = this.previousElementSibling;
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
});