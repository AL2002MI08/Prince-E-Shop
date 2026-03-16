<?php
// api/admin.php
session_start();
include 'db.php';

// Ensure only Admins can access these endpoints
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Fetch Summary Statistics
    if ($action === 'stats') {
        $stats = [];
        
        $stats['users'] = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
        $stats['products'] = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
        $stats['orders'] = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
        $stats['comments'] = $conn->query("SELECT COUNT(*) AS count FROM comments")->fetch_assoc()['count'];
        
        echo json_encode(["status" => "success", "data" => $stats]);
    }
    
    // Fetch Recent Orders (with customer and product names)
    elseif ($action === 'recent_orders') {
        $sql = "SELECT o.id, o.order_date, p.product_name, c.username as customer_name 
                FROM orders o 
                JOIN products p ON o.product_id = p.id 
                JOIN users c ON o.customer_id = c.id 
                ORDER BY o.order_date DESC LIMIT 10";
        $result = $conn->query($sql);
        
        $orders = [];
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $orders]);
    }
    
    // Fetch All Users
    elseif ($action === 'all_users') {
        $sql = "SELECT id, username, role FROM users ORDER BY role, username";
        $result = $conn->query($sql);
        
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $users]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>