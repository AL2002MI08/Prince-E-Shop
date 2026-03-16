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
    if(alertBox) {
        alertBox.className = `alert alert-${type}`;
        alertBox.innerText = message;
        alertBox.style.display = 'block';
        
        // Auto-hide after 4 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }
}
