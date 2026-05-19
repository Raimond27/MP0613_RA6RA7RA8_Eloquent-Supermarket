# Supermarket App

This Laravel-based application serves as a comprehensive system for managing a supermarket’s online presence. It includes features such as product listings, category management, fee configuration, calendar events, user authentication/profiles, and export functionality. The project is built with Laravel, Bootstrap, and various third-party libraries.
Landing page
<img width="1200" height="700" alt="image" src="https://github.com/user-attachments/assets/5144a5e2-df92-48d3-b444-85184c1dd601" />
Products
<img width="1200" height="700" alt="image" src="https://github.com/user-attachments/assets/8cb9b2c6-cf77-4460-8775-fea6eaed477b" />
Categories
<img width="1200" height="700" alt="image" src="https://github.com/user-attachments/assets/f9ee1426-6a17-4ae4-8135-e3974efa6f71" />
Fees
<img width="1200" height="700" alt="image" src="https://github.com/user-attachments/assets/69288ebe-16e8-4779-984d-d9aed3fca621" />
Calendar
<img width="1200" height="700" alt="image" src="https://github.com/user-attachments/assets/50a252fd-8371-4889-9078-84bb2cd68e55" />

---

## Features

-   **Landing Page:** A welcoming page with featured products and a call-to-action to shop.
-   **Product Management:** List, create, update, export (XLS/PDF), and delete products (with image carousel support).
-   **Category & Fee Management:** Create, edit, filter, and delete categories and fees (protected by role permissions).
-   **Calendar Events:** Manage calendar events (with fee application) and event scheduling.
-   **User Profiles:** User authentication, profile editing, and secure access control using custom permission middleware.
-   **Export Capabilities:** Export product data in XLS and PDF formats.

---

## Requirements

-   **PHP 8.0+**
-   **Composer**
-   **Node.js & NPM (for assets compilation if needed)**
-   **MySQL, PostgreSQL, or any supported database**
-   **Git**

---

## Installation and Setup Instructions

## Tech Stack
- Laravel (PHP)
- MySQL (via XAMPP)
- Composer
- Bootstrap (views)

## Prerequisites
- PHP 7.3+ (XAMPP recommended)
- MySQL running (XAMPP)
- Composer installed

## Setup
1. Clone the repository:

```bash
   git clone https://github.com/Stucom-Pelai/MP0613_RA6RA7RA8_Eloquent-Supermarket.git
```

2. Install Composer dependencies:

```bash
composer install
```

3. Copy the example enviroment file:

```bash
cp .env.example .env
```

4. Generate an application key

```bash
php artisan key:generate
```

5. Create a symbolic link from 'public/storage' to 'storage/app/public'

```bash
php artisan storage:link
```

6. Clear compiled view files

```bash
php artisan view:clear
```

7. Create mp0613_supermarket database


8. Run migrations and seed the database

```bash
php artisan migrate:fresh --seed
```

9. Start the Laravel development server 

```bash
php artisan serve
```

10. You are all set


##  Authentication Setup

    The application includes user authentication and access controls. You can register a new account or use seeded users (e.g., Customer, Employee, Admin) based on your testing requirements.

---

## Usage
- **Public Routes:** Visitors can browse the landing page and view product listings.
- **Restricted Routes:** Users with the appropriate roles (e.g., employees) can manage categories, fees, and products.
- **Calendar & Export Features:** Authenticated users can manage calendar events and export product information.

---



