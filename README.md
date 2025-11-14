# Symfony Notes Manager (без Vue)

Це простий CRUD менеджер нотаток, реалізований **повністю на Symfony 6.4** з Twig.

## Можливості

- Додавання, перегляд, редагування, видалення нотаток
- Масове видалення (через чекбокси)
- Пошук по заголовку та тексту
- Сортування за заголовком / датою (ASC/DESC)
- Пагінація (кількість елементів на сторінці можна міняти)
- Перемикач мови (🇺🇦/🇬🇧)
- Перемикач теми (світла/темна, зберігається в `localStorage`)

## Встановлення

1. Встановити залежності:

```bash
composer install
```

2. Переконатися, що в `.env` встановлено SQLite (вже налаштовано за замовчуванням):

```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

3. Створити файл бази та схему (створення таблиці `note`):

```bash
php bin/console doctrine:schema:update --force
```

4. Запустити вбудований веб-сервер (будь-який спосіб):

```bash
# варіант 1: через Symfony CLI
symfony server:start

# варіант 2: звичайний PHP сервер
php -S 127.0.0.1:8000 -t public
```

5. Відкрити в браузері:

```
http://127.0.0.1:8000/notes
```

## Структура

- `src/Entity/Note.php` — сутність нотатки
- `src/Repository/NoteRepository.php` — репозиторій з пошуком/сортуванням/пагінацією
- `src/Controller/NoteController.php` — контролер з усім CRUD
- `templates/note/*.html.twig` — Twig-шаблони
- `config/*.yaml` — мінімальні налаштування Symfony, Doctrine та Twig
