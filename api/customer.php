<?php
// api/customer.php
session_start();
include 'db.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // 1. Fetch ALL products (Supports optional Category & Discount filtering)
    if ($action === 'all_products') {
        
        $filter_query = "WHERE 1=1";
        
        // Filter by specific Category ID
        if (isset($_GET['category']) && is_numeric($_GET['category'])) {
            $cat_id = $conn->real_escape_string($_GET['category']);
            $filter_query .= " AND p.category_id = '$cat_id'";
        }

        // Filter to show ONLY items that have a discount
        if (isset($_GET['discount']) && $_GET['discount'] === 'true') {
            $filter_query .= " AND p.discount_percent > 0";
        }

        $sql = "SELECT p.*, u.username as seller_name, c.category_name 
                FROM products p 
                JOIN users u ON p.seller_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                $filter_query
                ORDER BY p.id DESC";
                
        $result = $conn->query($sql);
        $products = [];
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $products]);
    }
    
    // 2. Fetch TOP SELLING products (Limit 4 for the homepage grid)
    elseif ($action === 'top_selling') {
        // Counts how many times a product ID appears in the 'orders' table
        $sql = "SELECT p.*, u.username as seller_name, c.category_name, COUNT(o.id) as order_count 
                FROM products p 
                JOIN users u ON p.seller_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN orders o ON p.id = o.product_id 
                GROUP BY p.id 
                ORDER BY order_count DESC, p.id DESC
                LIMIT 4";
                
        $result = $conn->query($sql);
        $products = [];
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $products]);
    }

    // 3. Fetch details for a SINGLE product
    elseif ($action === 'product_details') {
        $id = $conn->real_escape_string($_GET['id']);
        
        $sql = "SELECT p.*, u.username as seller_name, c.category_name 
                FROM products p 
                JOIN users u ON p.seller_id = u.id 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = '$id'";
                
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
        } else {
            echo json_encode(["status" => "error", "message" => "Product not found"]);
        }
    }

    // 4. Fetch comments for a product
    elseif ($action === 'get_comments') {
        $product_id = $conn->real_escape_string($_GET['product_id']);
        
        $sql = "SELECT c.comment_text, c.created_at, u.username 
                FROM comments c 
                JOIN users u ON c.customer_id = u.id 
                WHERE c.product_id = '$product_id' 
                ORDER BY c.created_at DESC";
                
        $result = $conn->query($sql);
        $comments = [];
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
        }
        echo json_encode(["status" => "success", "data" => $comments]);
    }
} 

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Check if user is logged in as Customer for these actions
    if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Customer') {
        echo json_encode(["status" => "error", "message" => "Only logged-in customers can perform this action."]);
        exit;
    }
    $customer_id = $_SESSION['userid'];

    // Handle Adding a Comment
    if ($action === 'add_comment') {
        $product_id = $conn->real_escape_string($data['product_id']);
        $comment = $conn->real_escape_string($data['comment']);

        $sql = "INSERT INTO comments (customer_id, product_id, comment_text) VALUES ('$customer_id', '$product_id', '$comment')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Comment added!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add comment."]);
        }
    }

    // Handle Placing an Order
    elseif ($action === 'place_order') {
        $product_id = $conn->real_escape_string($data['product_id']);
        
        $sql = "INSERT INTO orders (customer_id, product_id) VALUES ('$customer_id', '$product_id')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to place order."]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>