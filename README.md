# Prince E-Shopping Platform
 **⚠️ Project Context:** This repository contains academic coursework built for a university project. It was developed under specific rubric constraints (e.g., using Vanilla PHP and raw SQL rather than modern enterprise frameworks or ORMs). While it demonstrates solid architectural concepts and UI/UX implementation, it is a proof-of-concept and does not reflect production-grade, enterprise-level standards.
 ----------------------------------------------------------------------------------------------------------------------------------------------------------

Prince E-Shopping  is a modern, responsive, multi-role e-commerce web application. It connects eco-conscious sellers with customers, providing a seamless shopping experience with dynamic product filtering, real-time toast notifications, and secure user authentication.

## 🚀 Features

### General / UI
* **Modern Design:** Clean, high-end UI using custom CSS Flexbox and Grid layouts (no external CSS frameworks required).
* **Responsive:** Fully optimized for desktop, tablet, and mobile devices.
* **Toast Notifications:** Custom, animated, non-intrusive popup alerts for user actions (success/error).
* **Split-Screen Auth:** Premium login and registration pages with dynamic image backgrounds.

### Customer Features
* **Dynamic Storefront:** Browse the latest products and top-selling items.
* **Smart Filtering:** Filter products by category (Clothing, Accessories, Shoes, Home) or view items currently on sale.
* **Product Details:** View high-quality images, descriptions, prices (with discount calculations), and download attached product specification documents.
* **Interactive Reviews:** Read and write product comments/reviews.
* **Order Placement:** Seamlessly place orders directly from the product page.

### Seller Features
* **Seller Dashboard:** A dedicated portal to manage inventory.
* **Product Management:** Upload new products with images, optional PDF/DOC attachments, pricing, and discount percentages.
* **Live Editing & Deletion:** Edit existing product details or remove items from the catalog.

### Admin Features
* **Global Oversight:** View and manage all products uploaded by all sellers across the platform.
* **Moderation:** Ability to delete inappropriate or outdated products.

---

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3 (Custom variables, Flex/Grid), Vanilla JavaScript (ES6, Fetch API)
* **Backend:** Vanilla PHP (Session management, secure routing)
* **Database:** MySQL (Relational database management)
* **Architecture:** Client-Server model with asynchronous REST-like API endpoints.

---

## 📂 Project Structure


prince-e-shopping/
│
├── api/
│   ├── auth.php          # Handles Login, Register, Logout
│   ├── customer.php      # Public fetching, Comments, Placing Orders
│   ├── products.php      # Seller/Admin CRUD operations for products
│   └── db.php            # MySQL Database connection configuration
│
├── css/
│   ├── global.css        # Core styles, variables, typography, toast notifications
│   ├── auth.css          # Styles specific to login/register split-screens
│   ├── dashboard.css     # Admin/Seller dashboard layouts
│   └── customer.css      # Specific styles for product detail pages
│
├── js/
│   ├── app.js            # Reusable API fetch wrapper and global scripts
│   └── nav.js            # Sidebar/Navigation logic for dashboards
│
├── database/
│   └── prince_e_shopping.sql # SQL Dump file for easy database import
│
├── uploads/              # Directory for user-uploaded product images and documents
│
├── index.html            # Storefront homepage (Top sellers, Promo banners)
│   ├── products.html         # Full catalog with dynamic filtering
│   ├── product_details.html  # Single product view, reviews, order button
│   ├── login.html            # User authentication
│   ├── register.html         # User registration (Role selection)
│   ├── seller_dashboard.html # Seller view for managing inventory
│   ├── upload_product.html   # Seller form for adding new products
│   └── admin_products.html   # Admin view for global inventory management

-------------------------------------------------------------------------------------------------------------------------------------------
⚙️ Installation & Setup
To run this project locally, you will need a local server environment like XAMPP, WAMP, or MAMP.

1. Clone the Repository
Clone or download the project files into your local server's root directory (e.g., htdocs for XAMPP or www for WAMP).

2. Database Setup
You have two options for setting up the database:

Option A: Import the SQL File (Recommended)

Open phpMyAdmin (usually http://localhost/phpmyadmin).

Create a new database named prince_e_shopping.

Go to the "Import" tab and upload the prince_e_shopping.sql file located in the database/ folder of this project.

Option B: Manual SQL Setup
If you prefer to set it up manually, run the following queries in phpMyAdmin:


CREATE DATABASE IF NOT EXISTS `prince_e_shopping`;
USE `prince_e_shopping`;

-- 1. Users Table & Default Data
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Seller','Customer') NOT NULL
);

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'Admin'),
(2, 'seller1', 'seller123', 'Seller'),
(3, 'customer1', 'customer123', 'Customer'),
(4, 'PrinceHymn', 'Stack-pop@123', 'Seller'),
(5, 'seller2', 'seller123', 'Seller'),
(6, 'remmy', '12345', 'Customer');

-- 2. Categories Table & Default Data
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_name` varchar(100) NOT NULL
);

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Clothing'), (2, 'Accessories'), (3, 'Shoes'), (4, 'Home');

-- 3. Products Table
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `photo_path` varchar(255) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
);

-- 4. Orders Table
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

-- 5. Comments Table
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

3. Configure Database Connection
Open api/db.php and ensure the credentials match your local server setup:

<?php
$host = "localhost";
$user = "root";               // Your MySQL username
$pass = "";                   // Your MySQL password
$dbname = "prince_e_shopping"; // The Database name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

4. Create the Uploads Folder
Ensure there is a folder named uploads in the root directory of your project. Note: Ensure this folder has read/write permissions so PHP can save images and documents into it.

5. Run the Application
Open your web browser and navigate to the project directory:
http://localhost/prince-e-shopping/index.html

🔒 Default Test AccountsYou can log in immediately using the following test credentials:
** Admin User **
Role:Admin
Username: admin
password: admin123

** seller User **
Role: Seller
Username: seller1
password: seller123

** Customer User **

Role: Customer
Username: customer1
Password: customer123
