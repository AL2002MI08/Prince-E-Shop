<?php
// api/products.php
session_start();
include 'db.php';
$action = $_GET['action'] ?? '';

// Check Login
if (!isset($_SESSION['userid'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['userid'];
$role = $_SESSION['role'];

// ==============================
// HANDLE GET REQUESTS (Fetching)
// ==============================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Seller fetching their own products
    if ($action === 'my_products' && $role === 'Seller') {
        $sql = "SELECT p.*, u.username as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.seller_id = '$user_id' ORDER BY p.id DESC";
        $result = $conn->query($sql);
        $products = [];
        while($row = $result->fetch_assoc()) { $products[] = $row; }
        echo json_encode(["status" => "success", "data" => $products]);
    }
    
    // Admin fetching ALL products
    elseif ($action === 'admin_all_products' && $role === 'Admin') {
        $sql = "SELECT p.*, u.username as seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.id DESC";
        $result = $conn->query($sql);
        $products = [];
        while($row = $result->fetch_assoc()) { $products[] = $row; }
        echo json_encode(["status" => "success", "data" => $products]);
    }
} 

// ==============================
// HANDLE POST REQUESTS (Mutating)
// ==============================
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 1. HANDLE DELETE ---
    if ($action === 'delete') {
        $json_input = file_get_contents("php://input");
        $data = json_decode($json_input, true);
        
        if (!isset($data['id'])) {
            echo json_encode(["status" => "error", "message" => "Product ID is missing."]);
            exit;
        }

        $product_id = $conn->real_escape_string($data['id']);
        $condition = ($role === 'Admin') ? "" : " AND seller_id = '$user_id'";

        // Optional: Remove physical files from server
        $file_sql = "SELECT photo_path, document_path FROM products WHERE id = '$product_id' $condition";
        $file_result = $conn->query($file_sql);
        if ($file_result && $file_result->num_rows > 0) {
            $files = $file_result->fetch_assoc();
            if ($files['photo_path'] && file_exists("../" . $files['photo_path'])) { unlink("../" . $files['photo_path']); }
            if ($files['document_path'] && file_exists("../" . $files['document_path'])) { unlink("../" . $files['document_path']); }
        }

        $sql = "DELETE FROM products WHERE id = '$product_id' $condition";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete product."]);
        }
        exit;
    }

    // --- 2. HANDLE EDIT ---
    elseif ($action === 'edit') {
        if (!isset($_POST['id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid form submission."]);
            exit;
        }

        $product_id = $conn->real_escape_string($_POST['id']);
        $new_name = $conn->real_escape_string($_POST['product_name']);
        $new_location = $conn->real_escape_string($_POST['location']);
        
        // Catch NEW fields for Edit
        $price = isset($_POST['price']) ? $conn->real_escape_string($_POST['price']) : '0.00';
        $discount = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? $conn->real_escape_string($_POST['discount_percent']) : '0';
        $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? $conn->real_escape_string($_POST['category_id']) : 'NULL';
        
        $condition = ($role === 'Admin') ? "" : " AND seller_id = '$user_id'";
        $upload_dir = "../uploads/";

        // Update string WITH NEW FIELDS
        $update_query = "UPDATE products SET 
                            product_name='$new_name', 
                            location='$new_location',
                            price='$price',
                            discount_percent='$discount',
                            category_id=$category_id";

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo_filename = time() . '_edit_' . basename($_FILES["photo"]["name"]);
            $photo_target = $upload_dir . $photo_filename;
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_target)) {
                $db_photo_path = "uploads/" . $photo_filename;
                $update_query .= ", photo_path='$db_photo_path'";
            }
        }

        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $doc_filename = time() . '_edit_' . basename($_FILES["document"]["name"]);
            $doc_target = $upload_dir . $doc_filename;
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $doc_target)) {
                $db_doc_path = "uploads/" . $doc_filename;
                $update_query .= ", document_path='$db_doc_path'";
            }
        }

        $update_query .= " WHERE id='$product_id' $condition";

        if ($conn->query($update_query) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        }
        exit;
    }

    // --- 3. HANDLE UPLOAD ---
    elseif ($action === 'upload' && $role === 'Seller') {
        $product_name = $conn->real_escape_string($_POST['product_name']);
        $location = $conn->real_escape_string($_POST['location']);
        
        // Catch NEW fields for Upload
        $price = isset($_POST['price']) ? $conn->real_escape_string($_POST['price']) : '0.00';
        $discount = isset($_POST['discount_percent']) && $_POST['discount_percent'] !== '' ? $conn->real_escape_string($_POST['discount_percent']) : '0';
        $category_id = isset($_POST['category_id']) && !empty($_POST['category_id']) ? $conn->real_escape_string($_POST['category_id']) : 'NULL';

        $upload_dir = "../uploads/";
        
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["status" => "error", "message" => "A product photo is required."]);
            exit;
        }
        
        $photo_filename = time() . '_' . basename($_FILES["photo"]["name"]);
        $photo_target = $upload_dir . $photo_filename;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_target);
        $db_photo_path = "uploads/" . $photo_filename;

        $db_doc_path = "NULL";
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $doc_filename = time() . '_' . basename($_FILES["document"]["name"]);
            $doc_target = $upload_dir . $doc_filename;
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $doc_target)) {
                $db_doc_path = "'uploads/" . $doc_filename . "'";
            }
        }

        // Insert string WITH NEW FIELDS
        $sql = "INSERT INTO products (seller_id, category_id, product_name, price, discount_percent, photo_path, document_path, location) 
                VALUES ('$user_id', $category_id, '$product_name', '$price', '$discount', '$db_photo_path', $db_doc_path, '$location')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Product uploaded successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        }
        exit;
    }
}
?>