# Mini E-Commerce

A simple e-commerce web application built with native PHP, CSS3, and PostgreSQL.

This project was created as a learning project to practice building an e-commerce application from scratch, including authentication, product management, shopping cart, checkout, and order management.

### User

- User registration
- User login and logout
- Browse products
- Add products to cart
- Update cart quantity
- Remove products from cart
- Checkout
- View order history

### Admin

- Admin dashboard
- Manage products
- View products
- Manage orders

## Tech Stack

- PHP
- CSS3
- PostgreSQL

## Project Structure

e-commerce/
├── admin/
│ ├── index.php
│ └── products.php
├── assets/
│ └── css/
│ └── style.css
├── config/
│ └── database.php
├── functions/
│ ├── helpers.php
│ ├── cart.php
│ └── checkout.php
├── database.sql
├── index.php
├── login.php
├── logout.php
├── orders.php
└── register.php

## Database

This project uses PostgreSQL as the database management system.

The database schema is provided in `database.sql`.

## Installation

1. Clone the repository:

git clone https://github.com/your-username/e-commerce.git

2. Create a PostgreSQL database.

3. Import `database.sql` into your PostgreSQL database.

4. Configure the database connection in:

config/database.php

5. Start the PHP development server:

php -S localhost:8000

6. Open the application in your browser:

http://localhost:8000

## Application Flow

Register → Login → Browse Products → Add to Cart → Checkout → View Orders

Admin Flow:

Admin Login → Dashboard → Manage Products → Manage Orders

## Purpose

This project is part of my web development learning journey.

The main purpose of this project is to practice native PHP, PostgreSQL database integration, CRUD operations, authentication, session management, shopping cart logic, checkout, and order processing.

## License

This project is for learning and portfolio purposes.
