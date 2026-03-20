// js/app.js

/**
 * Reusable function to call PHP API endpoints
 * @param {string} endpoint - The PHP file name (e.g., 'auth.php?action=login')
 * @param {string} method - 'GET', 'POST', etc.
 * @param {object|FormData} data - Data to send (JSON object or FormData)
 */
async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method: method,
    };

    if (data) {
        // Handle file uploads (FormData) differently than standard JSON
        if (data instanceof FormData) {
            options.body = data;
            // Fetch automatically sets the correct Content-Type for FormData
        } else {
            options.headers = { 'Content-Type': 'application/json' };
            options.body = JSON.stringify(data);
        }
    }

    try {
        const response = await fetch(`api/${endpoint}`, options);
        return await response.json();
    } catch (error) {
        console.error("API Error:", error);
        return { status: "error", message: "A network error occurred." };
    }
}

/**
 * UI Helper to show custom alerts
 * @param {string} elementId - The ID of the alert div
 * @param {string} message - The message to display
 * @param {string} type - 'success' or 'danger'
 */
function showAlert(elementId, message, type = 'danger') {
    const alertBox = document.getElementById(elementId);
    if (alertBox) {
        alertBox.className = `alert alert-${type}`;
        alertBox.innerText = message;
        alertBox.style.display = 'block';

        // Auto-hide after 4 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
}

/**
 * Update the navigation bar based on user login status
 */
async function updateNavbar() {
    const navActions = document.querySelector('.nav-actions');

    if (navActions) {
        const checkResponse = await apiCall('auth.php?action=check_session');

        if (checkResponse.status === 'success') {
            const user = checkResponse.user;

            let dashboardLink = '';
            if (user.role === 'Admin') dashboardLink = '<a href="admin_products.html" class="btn-rounded">Admin Panel</a>';
            if (user.role === 'Seller') dashboardLink = '<a href="seller_dashboard.html" class="btn-rounded">Dashboard</a>';

            // Logged in: show Logout button and user role-specific dashboard link if needed
            navActions.innerHTML = `
                ${dashboardLink}
                <a href="#" id="logoutBtn" class="btn-rounded" style="background: var(--primary-dark); color: white;">Log out</a>
            `;

            document.getElementById('logoutBtn').addEventListener('click', async (e) => {
                e.preventDefault();
                const response = await apiCall('auth.php?action=logout');
                if (response.status === 'success') {
                    window.location.href = 'index.html';
                }
            });
        } else {
            // Not logged in: show Login and Sign Up buttons
            navActions.innerHTML = `
                <a href="login.html" class="btn-rounded">Log in</a>
                <a href="register.html" class="btn-rounded" style="background: var(--primary-dark); color: white;">Sign Up</a>
            `;
        }
    }
}

// Automatically update navbar on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    updateNavbar();
});
