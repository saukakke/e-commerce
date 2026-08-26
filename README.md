# Shoply E-Commerce

Shoply is a production-oriented Laravel 12 e-commerce application for the Nigerian market. It includes the full customer shopping journey and an operational admin area.

## Implemented

- Responsive storefront with premium utility-first Blade UI
- Product catalog, search, category filtering, price filtering and sorting
- Featured products and related products
- Product detail pages with stock availability
- Session-based guest shopping cart with quantity limits
- Customer registration, login, logout and role-aware admin access
- Checkout with delivery details and order summary
- Cash-on-delivery checkout
- Optional Paystack online payment initialization and callback verification
- Orders, order items, status and payment status tracking
- Stock reservation/decrement at checkout inside a database transaction
- Saved customer delivery addresses
- Wishlist
- Product reviews
- Coupon codes with percentage/fixed discounts, minimum order and usage limits
- Admin dashboard with sales/customer/product/order statistics
- Admin product, category, order and coupon management
- JSON product API and health endpoint
- Demo seed data and demo accounts

## Stack

- Laravel 12
- PHP 8.2+
- MySQL 8+
- Blade
- Tailwind CSS CDN for the MVP UI
- Laravel HTTP client for Paystack

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`, then:

```bash
php artisan migrate --seed
php artisan serve
```

Open `http://localhost:8000`.

### Demo accounts

Admin: `admin@shoply.test` / `Admin@12345`

Customer: `customer@shoply.test` / `Customer@12345`

Change demo passwords before any real deployment.

### Paystack

Set `PAYSTACK_SECRET_KEY` in `.env` to enable online payment. Cash on delivery remains available without a payment gateway.

## Routes

- `/` storefront
- `/products` catalog
- `/cart` shopping cart
- `/checkout` checkout for authenticated customers
- `/account` customer dashboard
- `/orders` order history
- `/wishlist` wishlist
- `/admin` administration
- `/api/products` paginated product API
- `/api/health` health check

## Security and business rules

Authentication is session-based. Admin routes require the `admin` middleware and a user with the `admin` role. Checkout re-reads and locks products in a transaction so stale carts cannot oversell available stock. Coupon eligibility is recalculated server-side rather than trusting browser totals.

## Production checklist

1. Use strong, unique admin credentials.
2. Set `APP_ENV=production` and `APP_DEBUG=false`.
3. Configure MySQL and a persistent session/cache store for multi-instance deployments.
4. Set a real `PAYSTACK_SECRET_KEY` only through environment secrets.
5. Configure HTTPS and a production domain.
6. Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` during deployment.
7. Add transactional email/SMS and a real shipping provider before scaling order operations.
