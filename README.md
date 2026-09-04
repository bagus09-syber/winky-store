# WINKY STORE

E-commerce store built with Laravel 12, Tailwind CSS, and SQLite.

## Requirements

- PHP 8.2+
- Node.js 18+
- Composer

## Installation

```bash
# Clone project
git clone <repo-url>
cd winky-store

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Create storage link
php artisan storage:link
```

## Run

```bash
php artisan serve
```

Open http://localhost:8000

## Admin Access

- URL: http://localhost:8000/admin
- Email: admin@winky.store
- Password: password

## Database

Uses SQLite at `database/database.sqlite`.

Tables: users, categories, brands, products, product_images, product_variants, addresses, carts, cart_items, wishlists, orders, order_items, payments.

## Features

### Customer
- Homepage with flash sale, featured products, categories
- Product catalog with search, filter, sort
- Product detail with variants, gallery, related products
- Register / Login / Logout
- Profile, security (password), addresses
- Cart (add, update, delete)
- Wishlist (toggle)
- Checkout with address selection
- Payment (QRIS, Bank Transfer, E-Wallet) - simulated
- Order history and detail

### Admin
- Dashboard with stats (products, customers, orders, revenue)
- CRUD Products (name, category, brand, SKU, price, stock, image)
- CRUD Categories
- CRUD Brands
- Order management (list, detail, update status)
- Sidebar navigation, responsive

## Tech Stack

- Laravel 12
- Tailwind CSS v4 (via Vite)
- SQLite
- Blade templates
- Vanilla JavaScript

## Production Notes

- Change `APP_DEBUG=false` in `.env`
- Change `APP_URL` to production domain
- Configure real payment gateway (Midtrans)
- Configure shipping API (RajaOngkir)
- Set up queue worker for jobs
- Configure caching (Redis)
- Set up SSL/HTTPS
