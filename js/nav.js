// js/nav.js

function loadDashboardLayout(role, activePage, username = "User") {
    // Determine the links based on the user's role
    let menuHTML = '';

    if (role === 'Admin') {
        menuHTML = `
            <div class="sidebar-body-part">
                <p>Management</p>
                <a href="admin_dashboard.html" class="${activePage === 'dashboard' ? 'active-menu' : ''}">
                    <span><img src="icons/sidebar2.svg"> Overview</span>
                </a>
                <a href="admin_products.html" class="${activePage === 'products' ? 'active-menu' : ''}">
                    <span><img src="icons/sidebar6.svg"> Manage Products</span>
                </a>
            </div>

        `;
    } else if (role === 'Seller') {
        menuHTML = `
            <div class="sidebar-body-part">
                <p>Store</p>
                <a href="seller_dashboard.html" class="${activePage === 'dashboard' ? 'active-menu' : ''}">
                    <span><img src="icons/sidebar2.svg"> Dashboard</span>
                </a>
                <a href="seller_products.html" class="${activePage === 'products' ? 'active-menu' : ''}">
                    <span><img src="icons/sidebar6.svg"> My Products</span>
                </a>
                <a href="upload_product.html" class="${activePage === 'upload' ? 'active-menu' : ''}">
                    <span><img src="icons/sidebar3.svg"> Upload New</span>
                </a>
            </div>
        `;
    }

    // The complete HTML structure wrapping around whatever is in the page
    const layoutHTML = `
        <div class="dashboard">
            <section class="sidebar">
                <div class="sidebar-header">
                    <h2>E-Shopping Panel</h2>
                </div>
                
                <div class="sidebar-body">
                    ${menuHTML}
                </div>
                
                <div class="sidebar-footer">
                    <a href="index.html" target="_blank"><span><img src="icons/sidebar3.svg"> View Storefront</span></a>
                    
                    <div class="dashboard-account">
                        <div>
                            <h6>Logout</h6>
                            <p>${role} Account</p>
                        </div>
                        <div>
                            <a href="#" onclick="logoutUser()" style="color: red; font-size: 20px; padding: 0;"><img src="icons/sidebar11.svg"></a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="right-container">
                <div class="right-container-header">
                    <div class="dash_search">
                        <form onsubmit="event.preventDefault();">
                            <span style="position: absolute; left: 10px; top: 10px;">🔍</span>
                            <input type="search" placeholder="Search site contents...">
                        </form>
                    </div>
                    <div class="dash_great">
                        <h6>Hi, ${username}</h6>
                    </div>
                </div>

                <div class="contents-sec" id="main-content-area">
                    </div>
            </section>
        </div>
    `;

    // 1. Get the current content of the page (the tables, forms, etc.)
    const pageContent = document.getElementById('page-content').innerHTML;

    // 2. Replace the body with the layout
    document.body.innerHTML = layoutHTML;

    // 3. Put the page content back inside the specific content section
    document.getElementById('main-content-area').innerHTML = pageContent;
}

// Ensure the logout function is available
async function logoutUser() {
    await apiCall('auth.php?action=logout');
    window.location.href = 'login.html';
}