<header>
    <div class="Title">
        <nav class="navigation" role="navigation">
            <div id="menuToggle">
            <input type="checkbox" />
            <span></span>
            <span></span>
            <span></span>
            <ul id="menu">
                <a href="/ITP4523M_Project_EA/public/index.php">
                    <li>                
                        <img class="Title-Icon" src="/ITP4523M_Project_EA/public/images/Logo3.png">
                    </li>
                </a>
                <?php
                    if ($_SESSION['role'] === 'purchase_manager') {
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/make_order.php">Make Order</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/view_order.php">View Order</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/update_info.php">Update Information</a></li>';
                    } elseif ($_SESSION['role'] === 'supplier') {
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/insert_item.php">Insert Item</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/edit_item.php">Edit Item</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/generate_report.php">Generate Report</a></li>';
                    }
                ?>
            </ul>
            </div>
        </nav>
        <div class="Title-Introduce">
            <a href="/ITP4523M_Project_EA/public/index.php">
                <img class="Title-Icon" src="/ITP4523M_Project_EA/public/images/Logo3.png">
            </a>
            <ul class="Title-Menu">
                <?php
                    if ($_SESSION['role'] === 'purchase_manager') {
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/make_order.php">Make Order</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/view_order.php">View Order</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/manager_info/update_info.php">Update Information</a></li>';
                    } elseif ($_SESSION['role'] === 'supplier') {
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/insert_item.php">Insert Item</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/edit_item.php">Edit Item</a></li>';
                      echo '<li><a href="/ITP4523M_Project_EA/public/supplier/generate_report.php">Generate Report</a></li>';
                    }
                ?>
            </ul>
        </div>
        <div class="Title-Login-Icon dropdown">
            <img src="https://gamelet.online/clients/assets/v1/img/none_login_photo.png">
            <div class="dropdown-content">
                <a href="/ITP4523M_Project_EA/public/logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>