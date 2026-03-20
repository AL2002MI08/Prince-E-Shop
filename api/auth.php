<?php
// api/auth.php
header("Content-Type: application/json");
include 'db.php';

session_start();

$action = $_GET['action'] ?? '';

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    
    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']); // Using simple password for CAT, normally use password_hash()
    $role = $conn->real_escape_string($data['role']);

    // Check if username exists
    $check_sql = "SELECT id FROM users WHERE username='$username'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists."]);
        exit;
    }

    // Insert new user
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Registration successful! Please login."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $conn->error]);
    }
}

// Handle Login
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']);

    $sql = "SELECT id, role FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['userid'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Determine redirect path based on role
        $redirect = 'index.html'; // Default for Customer
        if ($user['role'] === 'Admin') $redirect = 'admin_dashboard.html';
        if ($user['role'] === 'Seller') $redirect = 'seller_dashboard.html';

        echo json_encode(["status" => "success", "message" => "Login successful", "redirect" => $redirect]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
    }
} 
// Handle Session Check
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'check_session') {
    if (isset($_SESSION['userid'])) {
        echo json_encode(["status" => "success", "user" => ["id" => $_SESSION['userid'], "role" => $_SESSION['role']]]);
    } else {
        echo json_encode(["status" => "error", "message" => "No active session."]);
    }
}
// Handle Logout
elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Logout successful"]);
}
else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

$conn->close();
?>