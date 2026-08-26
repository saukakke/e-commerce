# Shoply — Laravel E-Commerce Platform

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)
![Database](https://img.shields.io/badge/MySQL-8%2B-4479A1?logo=mysql)
![License](https://img.shields.io/badge/license-Private-lightgrey)

Shoply is a full-featured Laravel e-commerce MVP designed for a modern Nigerian online shopping experience. It covers the customer journey from product discovery through cart, checkout, payment and order tracking, alongside an administrative back office for catalog, inventory, orders and promotions.

## Contents

- [Features](#features)
- [User roles](#user-roles)
- [Technology](#technology)
- [Application structure](#application-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Demo accounts](#demo-accounts)
- [Payment integration](#payment-integration)
- [Routes](#routes)
- [Business rules](#business-rules)
- [API](#api)
- [Testing](#testing)
- [Deployment](#deployment)
- [Security](#security)
- [Future production integrations](#future-production-integrations)

## Features

### Storefront

- Responsive, mobile-first storefront
- Hero and featured-product sections
- Product catalog with pagination
- Product search
- Category filtering
- Price filtering
- Product sorting
- Product detail pages
- Related products
- Stock availability indicators
- Sale pricing

### Shopping

- Guest session-based shopping cart
- Add, remove and update cart quantities
- Server-side stock validation
- Automatic subtotal calculation
- Coupon discounts
- Delivery charges
- Persistent customer checkout information
- Saved delivery addresses
- Wishlist
- Product reviews

### Checkout and payments

- Authenticated checkout
- Delivery details and order notes
- Cash on delivery
- Optional Paystack online payment
- Paystack transaction initialization
- Payment callback verification
- Payment status tracking
- Atomic checkout transaction
- Inventory decrement at successful order creation
- Inventory restoration when an order is cancelled

### Customer account

- Registration and authentication
- Account dashboard
- Recent orders
- Order details
- Order status
- Payment status
- Saved addresses
- Address deletion
- Wishlist access

### Administration

- Role-based admin access
- Admin dashboard
- Sales/revenue overview
- Customer count
- Product count
- Order count
- Product creation and editing
- Product deletion
- Category management
- Inventory management
- Order status management
- Payment status management
- Coupon creation and deletion
- Order/customer/product operational visibility

### Platform

- Laravel 12 application architecture
- MySQL support
- Blade views
- Responsive Tailwind-based MVP UI
- JSON product API
- Health-check endpoint
- Database seed data
- Laravel automated test workflow through GitHub Actions

## User roles

| Role | Capabilities |
|---|---|
| Customer | Browse products, manage cart, checkout, pay, view orders, manage addresses, wishlist and reviews |
| Admin | All operational management functions, including products, categories, inventory, orders and coupons |

Administrative routes are protected by authentication and the admin middleware. Customer-facing routes remain separate from the administration area.

## Technology

- **Backend:** Laravel 12
- **Language:** PHP 8.2+
- **Database:** MySQL 8+
- **Frontend:** Blade + Tailwind CSS
- **Authentication:** Laravel session authentication
- **Payments:** Paystack API
- **HTTP:** Laravel HTTP Client
- **Testing:** PHPUnit / Laravel test suite
- **CI:** GitHub Actions

## Application structure

```text
app/
├── Http/
│   └── Controllers/
│       ├── AccountController.php
│       ├── AdminController.php
│       ├── CartController.php
│       ├── CheckoutController.php
│       ├── HomeController.php
│       ├── OrderController.php
│       ├── ProductController.php
│       └── ...
├── Models/
│   ├── Address.php
│   ├── Category.php
│   ├── Coupon.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── Review.php
│   ├── Wishlist.php
│   └── User.php
└── ...

database/
├── migrations/
└── seeders/

resources/
└── views/
    ├── admin/
    ├── account/
    ├── auth/
    ├── shop/
    └── layouts/

routes/
├── web.php
└── api.php

.github/
└── workflows/
    └── tests.yml
```

## Installation

### Requirements

Install the following before running the application:

- PHP 8.2 or newer
- Composer 2.x
- MySQL 8.x or compatible database
- PHP extensions required by Laravel and PDO MySQL
- Node.js/npm if you later replace the CDN frontend with a compiled asset pipeline

### Clone the repository

```bash
git clone https://github.com/saukakke/e-commerce.git
cd e-commerce
```

### Install dependencies

```bash
composer install
```

### Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```env
APP_NAME=Shoply
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shoply
DB_USERNAME=root
DB_PASSWORD=
```

### Create the database

Create a MySQL database named `shoply`, or use another database name and update `DB_DATABASE`.

### Run migrations and seed data

```bash
php artisan migrate --seed
```

### Start the application

```bash
php artisan serve
```

Visit:

```text
http://localhost:8000
```

## Demo accounts

The database seeder creates demo accounts for local development.

**Admin**

```text
Email:    admin@shoply.test
Password: Admin@12345
```

**Customer**

```text
Email:    customer@shoply.test
Password: Customer@12345
```

These credentials are for development/testing only. Replace them before deploying the application.

## Configuration

### Paystack

Online payments are optional. Add your Paystack secret key to `.env`:

```env
PAYSTACK_SECRET_KEY=your_secret_key
```

The application uses Paystack to initialize and verify online transactions. Cash on delivery continues to work without Paystack configuration.

### Production environment

For production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Use environment secrets for credentials and never commit `.env` to Git.

## Payment integration

The checkout supports two payment methods:

1. **Cash on delivery** — order is created immediately with a pending payment status.
2. **Paystack** — the application initializes a Paystack transaction, redirects the customer to Paystack and verifies the returned transaction before marking the order as paid.

The payment reference is tied to the application's order number, allowing the callback to resolve the correct order.

## Routes

### Customer routes

| Method | Route | Purpose |
|---|---|---|
| GET | `/` | Storefront |
| GET | `/products` | Product catalog |
| GET | `/products/{product}` | Product details |
| GET | `/cart` | Shopping cart |
| POST | `/cart/add` | Add product to cart |
| PATCH | `/cart/{product}` | Update cart quantity |
| DELETE | `/cart/{product}` | Remove cart item |
| GET | `/checkout` | Checkout |
| POST | `/checkout` | Create order |
| POST | `/checkout/coupon` | Apply coupon |
| DELETE | `/checkout/coupon` | Remove coupon |
| GET | `/payment/callback` | Verify Paystack payment |
| GET | `/account` | Customer dashboard |
| GET | `/orders` | Order history |
| GET | `/orders/{order}` | Order details |
| GET | `/wishlist` | Wishlist |

### Admin routes

| Method | Route | Purpose |
|---|---|---|
| GET | `/admin` | Admin dashboard |
| GET | `/admin/products` | Product management |
| POST | `/admin/products` | Create product |
| PUT | `/admin/products/{product}` | Update product |
| DELETE | `/admin/products/{product}` | Delete product |
| GET | `/admin/categories` | Category management |
| POST | `/admin/categories` | Create category |
| DELETE | `/admin/categories/{category}` | Delete category |
| GET | `/admin/orders` | Order management |
| PUT | `/admin/orders/{order}` | Update order/payment status |
| GET | `/admin/coupons` | Coupon management |
| POST | `/admin/coupons` | Create coupon |
| DELETE | `/admin/coupons/{coupon}` | Delete coupon |

## Business rules

### Inventory

Checkout does not trust quantities stored in the browser. Products are reloaded from the database and locked inside a database transaction. The order is rejected if requested stock is no longer available.

### Coupons

Coupon eligibility is calculated on the server. The application checks the coupon's active state, validity dates, minimum order value and usage limit before applying the discount.

### Orders

An order stores a snapshot of the purchased product name, SKU, price and quantity. This prevents historical orders from changing when a product is later edited.

### Cancellation

When a non-cancelled order is changed to `cancelled`, the purchased quantities are returned to product inventory.

## API

### Health check

```http
GET /api/health
```

Returns application health/status information.

### Products

```http
GET /api/products
```

Supports paginated product retrieval. The `per_page` query parameter can be used to control page size within the API's configured limits.

Example:

```text
/api/products?per_page=20
```

## Testing

Run the Laravel test suite with:

```bash
php artisan test
```

The repository also contains a GitHub Actions workflow that installs PHP dependencies, configures a temporary SQLite database, runs migrations/seeding and executes the Laravel tests on pushes and pull requests targeting `main`.

## Deployment

Before production deployment:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Configure the server to point its document root to Laravel's `public/` directory.

For a multi-instance deployment, use persistent infrastructure for sessions, cache, queues and uploaded files rather than relying on local ephemeral storage.

## Security

- Keep `APP_DEBUG=false` in production.
- Never commit `.env` or payment credentials.
- Use HTTPS in production.
- Use a strong unique administrator password.
- Restrict administrative routes with authentication and role checks.
- Keep Laravel and Composer dependencies updated.
- Validate all monetary, inventory and coupon calculations on the server.
- Use database transactions for checkout operations.
- Configure appropriate database backups and monitoring.

## Future production integrations

The current implementation establishes the core e-commerce platform. A larger production rollout can add:

- Shipping/courier API integration with live delivery pricing
- Transactional email and SMS notifications
- Paystack webhook processing for asynchronous payment confirmation
- Refund workflows
- Product image uploads and media optimization
- Advanced product variants such as size, colour and SKU-level inventory
- Customer support/ticketing
- Sales analytics and reporting
- Abandoned-cart recovery
- Advanced search and recommendations
- Tax and invoice management
- Multi-vendor marketplace functionality
- Automated deployment and infrastructure monitoring

## Project status

**Status:** MVP implemented and maintained on the `main` branch.

## License

This project is private and all rights are reserved unless a separate license is provided by the project owner.
