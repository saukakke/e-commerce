# Shoply — Full Laravel E-Commerce & Marketplace Platform

Shoply is a Laravel 12 e-commerce and marketplace platform built for a modern Nigerian online-shopping workflow. The project covers the customer journey, catalog and inventory operations, payments, shipping, promotions, support, analytics, APIs, scaling infrastructure and multi-vendor marketplace operations.

## Project status

**Phase 1–5:** Implemented in the `main` branch.

The application is designed to run as a conventional Laravel application with MySQL, database queues, scheduled jobs and optional Redis/third-party integrations.

## Table of contents

- [Phase 1 — Production foundation](#phase-1--production-foundation)
- [Phase 2 — Customer experience](#phase-2--customer-experience)
- [Phase 3 — Business and administration](#phase-3--business-and-administration)
- [Phase 4 — Scale and integrations](#phase-4--scale-and-integrations)
- [Phase 5 — Marketplace](#phase-5--marketplace)
- [Roles](#roles)
- [Technology](#technology)
- [Installation](#installation)
- [Environment configuration](#environment-configuration)
- [Demo accounts](#demo-accounts)
- [Payments](#payments)
- [Shipping](#shipping)
- [Queues and scheduled jobs](#queues-and-scheduled-jobs)
- [API](#api)
- [Security](#security)
- [Testing](#testing)
- [Production deployment](#production-deployment)
- [External services](#external-services)

## Phase 1 — Production foundation

### Catalog and inventory

- Product catalog
- Categories
- Brands and product metadata
- Product variants
- Variant-level SKU, price and inventory
- Product image galleries
- Product publish/active status
- Featured products
- Sale pricing
- Low-stock inventory visibility
- Inventory restoration when applicable
- Stock locking during checkout
- Transaction-safe order creation

### Orders and payments

- Cart management
- Checkout
- Cash on delivery
- Paystack payment initialization
- Paystack transaction verification
- Paystack signed webhook processing
- Payment transaction records
- Payment status lifecycle
- Refund requests
- Refund processing infrastructure
- Return requests and return items
- Order shipment/tracking information
- Order lifecycle management

### Shipping

- Shipping zones
- Shipping rates
- Delivery address management
- Order shipping information
- Zone/rate administration

### Notifications and auditing

- Database notification records
- Customer notification infrastructure
- Administrative audit logs
- Administrative activity history

### Access control

- Customer authentication
- Administrator authentication
- Vendor authentication
- Role-aware middleware
- Sanctum API authentication

## Phase 2 — Customer experience

### Discovery

- Product search
- Category filtering
- Price filtering
- Product sorting
- Product comparison
- Recently viewed products
- Related products
- Product questions and answers
- FAQs

### Customer account

- Customer dashboard
- Profile information
- Saved delivery addresses
- Wishlist
- Product reviews and ratings
- Order history
- Payment history
- Order details
- Return/refund portal
- Customer support tickets
- Order disputes

### Loyalty and retention

- Loyalty accounts
- Loyalty point transactions
- Point redemption
- Referral infrastructure
- Gift cards
- Promotional discounts
- Coupon application

## Phase 3 — Business and administration

### Admin dashboard

- Sales statistics
- Revenue statistics
- Customer statistics
- Product statistics
- Order statistics
- Operational dashboards
- Date-aware reporting foundation

### Catalog operations

- Product creation
- Product editing
- Product deletion
- Category creation
- Category deletion with product protection
- Inventory management
- Product question moderation
- Review/rating moderation

### Promotions

- Coupons
- Percentage discounts
- Fixed discounts
- Minimum order rules
- Usage limits
- Campaigns/promotions
- Gift-card administration
- Promotion status controls

### Operations

- Order management
- Payment-status management
- Return administration
- Refund administration
- Support-ticket administration
- Shipping-zone administration
- Dispute management
- Audit log

### Marketplace administration

- Vendor application review
- Vendor approval
- Vendor rejection
- Vendor suspension
- Vendor commission settings
- Vendor balance management
- Vendor payout administration
- Vendor-rating moderation

## Phase 4 — Scale and integrations

### API

- Public product API
- Product detail API
- API health endpoint
- Sanctum login/logout
- Authenticated customer endpoint
- Authenticated order listing
- Authenticated order details
- API-ready customer/mobile architecture

### Background processing

- Database queue infrastructure
- Queue worker configuration
- Scheduled abandoned-cart processing
- Scheduled vendor commission calculation
- Scheduled loyalty calculation
- Queue-safe application jobs

### Infrastructure readiness

- Redis-ready cache configuration
- Redis-ready session configuration
- Database queue configuration
- Environment-driven credentials
- Production cache configuration
- Production session configuration
- Config/route/view caching support

### Reliability

- Transaction-safe checkout
- Database stock locking
- Server-side coupon validation
- Signed Paystack webhook validation
- Idempotency-oriented payment transaction records
- Auditability of operational actions

## Phase 5 — Marketplace

### Vendors

- Vendor applications
- Vendor approval workflow
- Vendor rejection
- Vendor suspension
- Vendor storefront identity
- Vendor dashboard
- Vendor product ownership
- Vendor inventory ownership
- Vendor ratings

### Marketplace finance

- Vendor commissions
- Vendor commission records
- Vendor balances
- Vendor payout records
- Payout administration

### Marketplace operations

- Vendor-specific order-item ownership
- Marketplace disputes
- Vendor/customer dispute workflow
- Vendor rating moderation
- Vendor operational administration

## Roles

| Role | Main capabilities |
|---|---|
| **Customer** | Browse products, manage cart, checkout, payments, orders, reviews, wishlist, addresses, returns, support, loyalty and referrals |
| **Admin** | Manage catalog, inventory, orders, payments, shipping, promotions, returns, support, vendors, disputes, analytics and audits |
| **Vendor** | Manage approved marketplace products, inventory, vendor orders, storefront information, commissions, balance and payout information |

Administrative and vendor operations are protected by role-aware middleware. API access uses Laravel Sanctum tokens.

## Technology

- **Framework:** Laravel 12
- **Language:** PHP 8.2+
- **Database:** MySQL 8+
- **Testing database:** SQLite supported
- **Frontend:** Blade + Tailwind CSS
- **Authentication:** Laravel session authentication
- **API authentication:** Laravel Sanctum 4
- **Payments:** Paystack
- **Queues:** Laravel database queues
- **Cache/session:** File by default, Redis-ready
- **HTTP integrations:** Laravel HTTP Client
- **CI:** GitHub Actions

## Installation

### Requirements

- PHP 8.2 or newer
- Composer 2.x
- MySQL 8.x or compatible database
- Required Laravel PHP extensions
- Node.js/npm where the frontend asset workflow requires it
- Redis recommended for production

### Clone and install

```bash
git clone https://github.com/saukakke/e-commerce.git
cd e-commerce
composer install
```

### Environment

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database and other services, then run:

```bash
php artisan migrate --seed
```

Start locally:

```bash
php artisan serve
```

Application URL:

```text
http://localhost:8000
```

## Environment configuration

A typical production environment includes:

```env
APP_NAME=Shoply
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
SESSION_DRIVER=file

PAYSTACK_SECRET_KEY=
PAYSTACK_PUBLIC_KEY=
```

For Redis-backed infrastructure:

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Credentials must be supplied through deployment secrets/environment variables.

## Demo accounts

Seed data provides development accounts:

```text
Admin
Email: admin@shoply.test
Password: Admin@12345

Customer
Email: customer@shoply.test
Password: Customer@12345
```

Change these credentials before any real deployment.

## Payments

### Cash on delivery

Cash on delivery requires no external payment provider and creates an order with a pending payment state.

### Paystack

Configure:

```env
PAYSTACK_SECRET_KEY=your_secret_key
PAYSTACK_PUBLIC_KEY=your_public_key
```

The application supports transaction initialization and server-side verification.

### Paystack webhook

Configure the Paystack dashboard to send transaction events to:

```text
POST /api/payment/paystack/webhook
```

The webhook validates the Paystack `x-paystack-signature` using HMAC-SHA512 before accepting payment events.

Do not disable signature verification in production.

## Shipping

The platform supports configurable shipping zones and rates. A production deployment can create rates for states, cities or other configured zones and associate them with eligible orders.

The application stores shipment/tracking information with orders so a courier integration can update the delivery lifecycle without changing the customer-facing order model.

## Queues and scheduled jobs

Start a queue worker with:

```bash
php artisan queue:work --tries=3
```

Run the scheduler locally with:

```bash
php artisan schedule:work
```

Scheduled application operations include:

- abandoned-cart processing
- vendor commission calculation
- loyalty processing

For production, run queue workers under a process manager and run Laravel's scheduler every minute through the hosting platform's scheduler/cron mechanism.

## API

### Public endpoints

```http
GET /api/health
GET /api/products
GET /api/products/{product}
```

### Authentication

```http
POST /api/auth/login
GET  /api/me
POST /api/auth/logout
```

### Customer orders

```http
GET /api/orders
GET /api/orders/{order}
```

Authenticated endpoints require a valid Laravel Sanctum bearer token.

Example login request:

```json
{
  "email": "customer@shoply.test",
  "password": "Customer@12345"
}
```

The API architecture is suitable for future native mobile clients without exposing browser session state.

## Security

The application includes:

- Laravel CSRF protection
- Session authentication
- Sanctum token authentication
- Role-aware admin/vendor middleware
- Password hashing
- Server-side request validation
- Transactional checkout
- Database row locking for inventory
- Server-side coupon calculations
- Paystack webhook signature validation
- Environment-based secret management
- Administrative audit records

Production requirements:

1. Set `APP_DEBUG=false`.
2. Use HTTPS.
3. Use strong unique administrative credentials.
4. Store secrets in deployment environment variables.
5. Use a persistent database and backup strategy.
6. Use Redis for distributed cache/session/queue workloads where appropriate.
7. Restrict filesystem permissions.
8. Keep PHP, Laravel and Composer dependencies patched.
9. Monitor queue failures and payment exceptions.
10. Configure appropriate rate limits and infrastructure-level protection.

## Testing

Run:

```bash
php artisan test
```

The repository includes GitHub Actions CI for installation, environment setup, database migrations/seeding and automated Laravel tests.

For local test execution using SQLite:

```bash
mkdir -p database
touch database/database.sqlite
```

Then configure the test environment to use SQLite.

## Production deployment

Install production dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

Run database migrations:

```bash
php artisan migrate --force
```

Cache framework configuration:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Start workers:

```bash
php artisan queue:work --tries=3
```

Run the scheduler:

```bash
php artisan schedule:work
```

The web server document root must point to Laravel's `public/` directory.

For a scalable deployment, use:

- MySQL/PostgreSQL-compatible managed database
- Redis
- persistent object storage for product media
- CDN
- queue workers managed by Supervisor/systemd/container orchestration
- HTTPS/TLS
- database backups
- centralized logs and monitoring
- automated CI/CD

## External services

The core application does not embed real provider credentials. External services are enabled through environment configuration.

### Implemented provider integration

- Paystack payment initialization
- Paystack payment verification
- Paystack signed webhook verification
- Paystack refund integration

### Provider-ready application integrations

- Courier/shipping providers
- Email delivery providers
- SMS providers
- WhatsApp messaging providers
- Redis
- Object storage/CDN

Actual provider accounts, credentials, sender IDs, webhook configuration and production infrastructure must be supplied by the deployment owner.

## Repository workflow

The `main` branch contains the implemented application. Before merging future changes:

```bash
php artisan test
php artisan migrate --force
```

and review any new database migrations, API changes and security-sensitive payment/marketplace logic.

## License

Private project. All rights reserved unless separately licensed by the project owner.
