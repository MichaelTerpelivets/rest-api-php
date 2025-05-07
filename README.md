# REST API PHP Project

Цей проект - реалізація REST API для управління товарами в базі даних PostgreSQL. Використовується **PHP 8.2**, а також
такі бібліотеки як `FastRoute`, `Phinx` (для міграцій), `PHPUnit` (для тестів), і бібліотеки для роботи з PSR-7.

---

## Вимоги

- **PHP 8.2 або новіше**
- **Composer**
- **Docker**
- **PostgreSQL 15**

---

## Установка і запуск

1. **Клонуйте репозиторій:**
   ```bash
   git clone https://github.com/terpelyvets/rest-api-php.git
   cd rest-api-php
   ```

2. **Запустіть Docker-контейнери:**
   ```bash
   docker-compose up --build
   ```

3. **Встановіть залежності через Composer:**
   ```bash
   docker-compose exec app composer install
   ```

4. **Переконайтеся, що API доступне:**
   Відкрийте у браузері [http://localhost:8000](http://localhost:8000).

---

## Міграції бази даних

Проект використовує **Phinx** для управління міграціями.

1. **Застосуйте всі наявні міграції:**
   ```bash
   docker-compose exec app vendor/bin/phinx migrate -e development
   ```

2. **Створіть нову міграцію (за необхідності):**
   ```bash
   docker-compose exec app vendor/bin/phinx create MigrationName
   ```

3. **Відкіт останньої міграції (опціонально):**
   ```bash
   docker-compose exec app vendor/bin/phinx rollback -e development
   ```

---

## Тестування

Для перевірки функціоналу проекту використовується **PHPUnit**.

1. **Запустіть тести:**
   ```bash
   docker-compose exec app vendor/bin/phpunit tests/Integration
   docker-compose exec app vendor/bin/phpunit tests/Unit
   ```

2. **Тести знаходяться у папці `tests/`, додавайте нові для перевірки своїх модулів.**

---

## Використання API

Точка входу для API — [http://localhost:8000](http://localhost:8000). Отримати доступ можна за допомогою `curl`, Postman
або аналогічних інструментів.

### Приклади запитів:

#### 1. Отримати список продуктів:

bash curl -X GET [http://localhost:8000/products](http://localhost:8000/products)

#### 2. Створення нового продукту:

bash curl -X POST -H "Content-Type: application/json"
-d '{"name": "Test Product", "price": 10.5, "category": "electronics", "attributes": {}}'
[http://localhost:8000/products](http://localhost:8000/products)

#### 3. Оновлення продукту:

bash curl -X PATCH -H "Content-Type: application/json"
-d '{"name": "Updated Product Name"}'
[http://localhost:8000/products/{uuid}](http://localhost:8000/products/{uuid})

#### 4. Видалення продукту:

bash curl -X DELETE [http://localhost:8000/products/1](http://localhost:8000/products/{uuid})

## Запуск проекту в кілька кроків

1. Запустіть Docker:
   ```bash
   docker-compose up --build
   ```
2. Виконайте міграції:
   ```bash
   docker-compose exec app vendor/bin/phinx migrate -e development
   ```
3. API доступне за посиланням: [http://localhost:8000](http://localhost:8000).

4. Запустіть тести, щоб переконатися, що проект працює:
   ```bash
   docker-compose exec app vendor/bin/phpunit
   ```

---
