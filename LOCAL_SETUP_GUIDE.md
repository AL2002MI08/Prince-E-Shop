# 🔧 Local Setup Guide (XAMPP)

Follow these simple steps to run this project on your computer using **XAMPP**.

---

## 🏗️ 1. Prepare the Folders
1.  **Copy** the project folder (`coursework-prince-eshop`).
2.  **Paste** it into your XAMPP's web directory:
    *   **Windows:** `C:\xampp\htdocs\`
    *   **Mac:** `/Applications/XAMPP/htdocs/`

## 🚀 2. Start XAMPP
1.  Open the **XAMPP Control Panel**.
2.  Click **Start** for **Apache**.
3.  Click **Start** for **MySQL**.
    *   *Green lights mean you're ready!*

## 💾 3. Setup the Database
1.  Open your browser and go to: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2.  Click **"New"** in the top left.
3.  Database name: `prince_e_shopping`
4.  Click **"Create"**.
5.  Click the **"Import"** tab at the top.
6.  Click **"Choose File"** and select `database/prince_e_shopping.sql` from your project folder.
7.  Scroll down and click **"Go"** or **"Import"**.

## 🌐 4. Run the Project
Go to this link in your browser:  
👉 [http://localhost/coursework-prince-eshop/index.html](http://localhost/coursework-prince-eshop/index.html)

---

## 👤 Test Accounts
| Role | Username | Password |
| :--- | :--- | :--- |
| **Customer** | `customer1` | `customer123` |
| **Seller** | `seller1` | `seller123` |
| **Admin** | `admin` | `admin123` |

---
**💡 Tip:** If the database fails to connect, make sure `api/db.php` has `$password = "";` (empty) for XAMPP.
