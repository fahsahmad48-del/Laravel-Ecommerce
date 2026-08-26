# Laravel E-Commerce

A full-stack e-commerce application built with Laravel 12, PHP, MySQL, Blade, JavaScript, and CSS.

The application allows users to browse products, search and filter products, manage their cart, and place orders. It also includes an admin section for managing products, categories, and orders.

## Features

### Authentication

* User registration
* User login and logout
* Password validation
* Authentication using Laravel
* Support for guest and authenticated users

### Products

* Display products and product details
* Product categories
* Product images
* Product prices and quantities
* Product descriptions
* Search products by name
* Filter products by category
* Dynamic filtering using AJAX and JSON

### Shopping Cart

The cart works differently depending on whether the customer is logged in.

For guest users:

* Cart data is stored in the Laravel session
* Add products to the cart
* Remove products
* Change product quantities

For authenticated users:

* Cart data is stored in the database
* Cart items are linked to the logged-in user
* Add and remove products
* Change quantities
* Check available stock

### Checkout and Orders

* Checkout form
* Customer name, email, phone, and address
* Payment method selection
* Cash on delivery
* Order creation
* Order items stored in the database
* Cart cleared after an order is placed
* Guest checkout support

### Admin

The admin section allows administrators to:

* Add products
* Upload product images
* Add categories
* View orders
* View order details
* Update order status
* Calculate order totals

Admin routes are protected using authentication and custom admin middleware.

## Technologies

* Laravel 12
* PHP 8.2+
* MySQL / MariaDB
* Blade
* HTML5
* CSS3
* JavaScript
* AJAX
* JSON
* Eloquent ORM
* Laravel Sessions
* Laravel Middleware
* Laravel Storage

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   └── Maincontroller.php
│   └── Middleware/
│       └── admin.php
│
├── Models/
│   ├── Cart.php
│   ├── Category.php
│   ├── Category_Product.php
│   ├── OrderItem.php
│   ├── Orders.php
│   ├── Product.php
│   └── User.php
│
database/
├── migrations/
└── seeders/
│
resources/
└── views/
    ├── admin/
    ├── components/
    ├── cart.blade.php
    ├── checkout.blade.php
    ├── home.blade.php
    ├── login.blade.php
    ├── products.blade.php
    └── register.blade.php
│
routes/
└── web.php
```

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
```

Go to the project folder:

```bash
cd Laravel-Ecommerce
```

### 2. Install dependencies

Install the PHP dependencies with Composer:

```bash
composer install
```

### 3. Create the environment file

Copy `.env.example` and rename the copy to:

```text
.env
```

On Windows, you can also use:

```bash
copy .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure the database

Open `.env` and configure the database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Create the `ecommerce` database using MySQL or phpMyAdmin.

### 6. Run the migrations

```bash
php artisan migrate
```

### 7. Create the storage link

Product images use Laravel's public storage disk.

Run:

```bash
php artisan storage:link
```

### 8. Start the application

```bash
php artisan serve
```

Open the address shown by Laravel, normally:

```text
http://127.0.0.1:8000
```

## Admin

The project contains an admin section protected by custom middleware.

Some of the admin routes include:

```text
/addProduct
/addCategory
/admin/orders
/admin/orderDetails/{id}
```

The user must have the appropriate admin value in the database to access these routes.

## Application Flow

```text
Customer
   │
   ├── Browse Products
   │       ├── Search
   │       └── Filter by Category
   │
   ├── Add Product to Cart
   │       ├── Guest → Session
   │       └── User  → Database
   │
   ├── Checkout
   │
   └── Place Order
           │
           └── Cart Cleared


Admin
   │
   ├── Manage Categories
   ├── Manage Products
   ├── View Orders
   ├── View Order Details
   └── Update Order Status
```

## Product Search and Filtering

The products page includes search and category filtering.

The frontend sends the filter request to the Laravel backend. The backend returns the matching products as JSON, which allows the product list to be updated without reloading the whole page.

## Guest Cart

Visitors can add products to the cart without creating an account.

For guests, cart information is stored in the Laravel session. When an order is completed, the cart is cleared.

Authenticated users have their cart items stored in the database and associated with their account.

## Database

The application uses MySQL/MariaDB.

The database contains tables for:

* Users
* Products
* Categories
* Cart items
* Orders
* Order items

The project uses Eloquent relationships to connect the different models.

## Security

The project uses Laravel's built-in security features together with custom middleware.

These include:

* Authentication middleware
* Admin middleware
* CSRF protection
* Request validation
* Password hashing
* Eloquent ORM
* Protected admin routes
* Session management

## Screenshots

Screenshots can be added to this section.

For example:

```markdown
![Home Page](screenshots/home.png)

![Products Page](screenshots/products.png)

![Shopping Cart](screenshots/cart.png)

![Admin Orders](screenshots/admin-orders.png)
```

## What I Practiced

While building this project, I worked with:

* Laravel MVC
* Routes and controllers
* Blade templates
* Eloquent ORM
* Database migrations
* Model relationships
* Authentication
* Middleware
* Form validation
* File uploads
* Laravel sessions
* AJAX and JSON
* Shopping cart logic
* Order management
* Admin functionality
* MySQL database design
* Laravel storage

## Future Improvements

Some features I may add later:

* Online payment integration
* Product reviews and ratings
* Wishlist
* Email notifications
* Order tracking
* More admin statistics
* Pagination
* REST API
* Automated tests
* Further responsive design improvements

## License

This project is open-sourced under the MIT License.

---

Built with Laravel 12 and PHP.
