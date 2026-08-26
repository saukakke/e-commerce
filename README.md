# Shoply E-Commerce MVP

A premium Laravel 12 e-commerce MVP with a responsive storefront, product catalog, search, categories, product details, and session-based shopping cart.

## Stack

- Laravel 12 / PHP 8.2+
- MySQL 8
- Laravel Sanctum-ready API
- Blade + Tailwind CSS CDN for the MVP storefront

## Features

- Responsive landing page
- Product catalog with pagination
- Product search and category filtering
- Product detail pages
- Featured products
- Shopping cart with quantity management
- Session persistence for guest carts
- JSON product API
- Demo database seed data

## Installation

```bash
git clone https://github.com/saukakke/e-commerce.git
cd e-commerce
composer install
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`, then run:

```bash
php artisan migrate --seed
php artisan serve
```

Open `http://localhost:8000`.

## API

`GET /api/health` returns service status.

`GET /api/products` returns paginated products. Optional `per_page` controls page size.

## Project Structure

- `app/Models` — Product and Category models
- `app/Http/Controllers` — storefront and cart logic
- `database/migrations` — catalog schema
- `database/seeders` — demo catalog
- `resources/views` — storefront UI
- `routes/web.php` — browser routes
- `routes/api.php` — API routes

## MVP Scope

The current MVP prioritizes the customer shopping journey. Payment gateway integration, customer authentication, order management, coupon administration, inventory management, reviews, and a full administrator dashboard are intended as the next implementation phase.

## License

Private project. All rights reserved.
