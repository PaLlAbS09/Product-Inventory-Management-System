StoreFront | Advanced Inventory & E-Commerce Management System
A robust, full-stack web application built with PHP (PDO), Tailwind CSS, and MySQL, designed to handle multi-tier operations including a secure administrative control panel and a fully functional customer shopping portal.
🚀 Key Features
🛠️ Admin & Seller Portal
Dynamic Dashboard: Real-time metrics overview including total products, active orders, active user count, and pending support tickets. 
Product Inventory Management: Complete CRUD functionality (Add, Edit, Delete) with image uploading, stock level badges, category filtering, and advanced sorting. 
Order Tracking: Monitor customer purchases, quantity metrics, unit prices, and automatic stock calculation reductions. 
User Management: Register new accounts, review directory records, and securely manage system users.  
Support Ticket Hub: Resolve, approve, or reject customer inquiries dynamically via asynchronous AJAX handlers. 
Analytics & Reports: Visualized financial data featuring monthly revenue trends and category distribution charts powered by Chart.js, complete with a print-ready report layout. 
🛍️ Customer Portal
Interactive Storefront: Browse live product catalogs with responsive category switching, search bars, and dynamic sorting. 
Order Placement & History: Seamless checkout workflow ("Buy Now") tracking item quantities, order dates, and purchase history.  
Customer Support Center: Submit help tickets, track issue statuses, and read system maintenance notices.  
Secure Authentication: User registration, password resets, secure login sessions, and "Remember Me" cookie functionality.  
💻 Tech StackBackend: PHP (Object-Oriented PDO database interactions, secure password hashing using bcrypt)  
Frontend: HTML5, JavaScript (Fetch API for asynchronous operations), Tailwind CSS (via CDN)  
Database: MySQL  
Libraries & Plugins:
        Swiper.js (for interactive product sliders)  
        Chart.js (for revenue and sales analytics)  
📂 Project Directory Structure
Plaintext
├── ajax/                   # Asynchronous request endpoints (Auth, Products, Users, Support)
├── assets/                 # Images, product uploads, and frontend media
├── Authentication/         # Registration and login process scripts
├── config/                 # Database configuration (dbcon.php) and auth checks
├── dashboard/              # User-specific dashboard views
├── includes/               # Reusable layout templates (header, footer, navigation sidebars)
├── admin_dashboard.php     # Main administrative control center
├── admin_registration.php  # Admin account onboarding
├── admin_support.php       # Support ticket management interface
├── index.php               # Public landing page & gateway
├── login.php               # Admin authentication portal
├── orders.php              # System-wide order tracking[cite: 1]
├── products.php            # Inventory management catalog[cite: 1]
├── reports.php             # Financial and stock analytics[cite: 1]
├── search.php              # Advanced directory search engine[cite: 1]
├── user_dashboard.php      # Customer account overview[cite: 1]
├── user_shop.php           # E-commerce product browser[cite: 1]
└── users.php               # User administration grid[cite: 1]
⚙️ Setup and Installation
Prerequisites
A local web server environment running PHP (version 8.0 or higher recommended) and MySQL (such as XAMPP, WAMP, or MAMP).
Installation Steps
Clone or Download the Repository
Place the project folder inside your web server's root directory (e.g., htdocs for XAMPP).
Configure the Database
  Open your database management tool (e.g., phpMyAdmin).
  Create a new MySQL database named product_inventory_management.
  Import the necessary schema tables (admins, users, product_inventory, orders, support_tickets) matching the requirements in config/dbcon.php.
  Verify Database CredentialsOpen config/dbcon.php and confirm your connection parameters:
  PHPprivate $host = "localhost";
private $db_name = "product_inventory_management";
private $username = "root";
private $password = "";
Run the ApplicationStart your Apache and MySQL services.Open your web browser and navigate to:
http://localhost/your-project-folder/index.php
🔒 Security FeaturesPDO Prepared Statements: Prevents SQL injection vulnerabilities across all database queries.
Password Hashing: Implements PHP's native password_hash() and password_verify() using secure cryptographic algorithms.
Session & Cookie Validation: Restricts unauthorized endpoint access via strict session verification and secure cookie tokens.
