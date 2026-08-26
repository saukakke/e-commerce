# Shoply — Full Laravel E-Commerce Platform

Shoply is a Laravel 12 e-commerce platform covering the complete customer, merchant and administration lifecycle for a Nigerian online marketplace.

## Phase 1–5 implementation

### Phase 1 — Production foundation

- Product variants with variant SKU, attributes, price and inventory
- Product image gallery
- Shipping zones and courier rates
- Paystack initialization and webhook verification
- Payment transaction ledger
- Refund requests and Paystack refund processing
- Returns and return items
- Order shipment/tracking fields and lifecycle timestamps
- Database-backed notifications support
- Audit logging
- Role-aware customer, administrator and vendor access
- API authentication through Laravel Sanctum
- Production environment configuration
- Queue tables and scheduled jobs

### Phase 2 — Customer experience

- Searchable product API
- Product comparison
- Recently viewed product tracking
- Product questions and answers
- Reviews
- Wishlist
- Saved addresses
- Returns/refunds portal
- Customer support tickets
- Order/payment history
- Loyalty points and redemption
- Referral infrastructure
- FAQ system
- Gift-card purchasing
- Order dispute creation

### Phase 3 — Business and administration

- Sales analytics
- Product/category management
- Inventory management
- Coupon management
- Promotion campaigns
- Gift-card administration
- Shipping-zone administration
- Vendor approval and commission settings
- Vendor payout administration
- Return administration
- Support-ticket administration
- Product-question moderation
- Vendor-rating moderation
- Dispute management
- Audit log

### Phase 4 — Scale and integration foundation

- Sanctum mobile/API authentication
- Public product API
- Authenticated order API
- Paystack webhook endpoint
- Database queue infrastructure
- Scheduled abandoned-cart processing
- Scheduled vendor commission calculation
- Scheduled loyalty calculation
- Redis-ready configuration
- Production cache/session configuration
- API-ready architecture for mobile clients
- Environment-driven external provider configuration

### Phase 5 — Marketplace

- Vendor applications
- Vendor approval/rejection/suspension
- Vendor storefront identity
- Vendor products
- Vendor commissions
- Vendor balances
- Vendor payouts
- Vendor ratings
- Marketplace disputes
- Vendor dashboard
- Vendor-specific order-item ownership

## Requirements

- PHP 8.2+
- Composer 2+
- MySQL 8+ (SQLite is suitable for automated tests)
- Redis recommended for production queues/cache/sessions
- Paystack account for online payments/refunds

## Installation

```bash
git clone https://github.com/saukakke/e-commerce.git
cd e-commerce
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Environment

Configure database, mail, queue, Redis and Paystack credentials in `.env`.

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shoply
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=file

PAYSTACK_SECRET_KEY=
PAYSTACK_PUBLIC_KEY=
```

## Demo accounts

The seed data includes:

```text
Admin
admin@shoply.test
Admin@12345

Customer
customer@shoply.test
Customer@12345
```

Change these credentials before deployment.

## API

Public:

```text
GET /api/products
GET /api/products/{product}
GET /api/health
```

Authenticated with Sanctum:

```text
POST /api/auth/login
GET  /api/me
POST /api/auth/logout
GET  /api/orders
GET  /api/orders/{order}
```

## Paystack webhook

Configure the Paystack dashboard to send transaction events to:

```text
POST /api/payment/paystack/webhook
```

The endpoint validates the `x-paystack-signature` HMAC-SHA512 signature before processing successful payments.

## Scheduled operations

Run the scheduler in production with Laravel's scheduler mechanism:

```bash
php artisan schedule:work
```

The application schedules:

- abandoned-cart processing hourly
- vendor commission calculation daily
- loyalty point calculation daily

## Queue worker

For asynchronous application work:

```bash
php artisan queue:work --tries=3
```

## Production deployment

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

Use HTTPS, persistent database storage, Redis for distributed cache/queues, persistent object storage for media, database backups, monitoring and a process manager for queue workers.

## External integrations

The core application is fully implemented without hard-coded third-party credentials. Provider-specific credentials are intentionally supplied through environment variables. Paystack processing is implemented in the application; courier, SMS and email delivery can be enabled by configuring the corresponding production provider services.

## Security

- CSRF protection for browser forms
- Sanctum API tokens
- Admin/vendor middleware
- Server-side validation
- Transactional checkout
- Stock locking
- Signed Paystack webhook verification
- Password hashing through Laravel casts
- Sensitive credential storage through environment configuration
- Audit trail for administrative return/ticket operations
- Production debug mode disabled through environment configuration

## License

Private project. All rights reserved unless otherwise licensed by the project owner.
