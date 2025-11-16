# Contacts App (Laravel 11)

Це простий CRUD застосунок для керування контактами та групами, побудований на Laravel 11. Проєкт демонструє роботу з міграціями, Eloquent моделями, Blade-шаблонами та базовими CRUD-операціями з пошуком, фільтрацією та пагінацією.

## Вимоги
- PHP 8.2+
- Composer
- MySQL 8+
- Node.js (для роботи Vite, опційно)

## Початок роботи

```bash
cp .env.example .env
php artisan key:generate
```

### Налаштування БД
Створіть базу `contacts_app` у MySQL та змініть значення `DB_*` у `.env`. Наприклад:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contacts_app
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### Встановлення залежностей
```bash
composer install
npm install # або pnpm/yarn за бажанням
```

### Міграції та сидери
```bash
php artisan migrate
php artisan db:seed # опційно, створює тестові контакти
```

### Запуск дев-сервера
```bash
php artisan serve
```
Відкрийте [http://127.0.0.1:8000/contacts](http://127.0.0.1:8000/contacts) і перевірте сценарії створення/редагування/видалення контактів, роботу пошуку та фільтрації за групами.

### Побудова фронтенду (опційно)
```bash
npm run dev    # режим розробки
npm run build  # production-білд
```

## Структура
```
app/
├─ Http/
│  └─ Controllers/ContactController.php
├─ Models/
│  ├─ Contact.php
│  └─ Group.php
resources/
└─ views/
   ├─ layouts/app.blade.php
   └─ contacts/
      ├─ index.blade.php
      └─ form.blade.php
routes/
└─ web.php
```

## Тестування
```bash
php artisan test
```

## Корисні команди
- `php artisan migrate:fresh --seed` — пересоздає структуру БД з тестовими даними
- `php artisan tinker` — швидка перевірка запитів до моделей

