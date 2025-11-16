# Contacts App (Laravel 11)

This project recreates the CRUD scenario from the Symfony notes sample but with a Laravel 11 backend. It provides:

* Contact and group entities with a one-to-many relation.
* CRUD controller with server-side validation, pagination, filtering and search.
* Blade UI for listing, creating, editing and deleting contacts (with modal confirmation).
* Database migrations that also seed the default contact groups.
* Example feature test for the listing route and factories for both models.

## Requirements

* PHP 8.2+
* Composer
* MySQL 8.x (or any database supported by Laravel)
* Node.js (optional, only for Vite/Tailwind assets)

## Getting started

```bash
cd contacts-app
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Visit http://127.0.0.1:8000/contacts to manage the address book.

To run the Vite development server:

```bash
npm install
npm run dev
```

## Testing

```bash
php artisan test
```
