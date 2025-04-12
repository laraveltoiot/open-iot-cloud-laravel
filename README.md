# Laravel Application

This is a project built using [Laravel](https://laravel.com/), a modern PHP framework designed for building scalable and efficient web applications.

---

## Requirements

Ensure your system meets the following prerequisites before starting:

- PHP 8.3 or higher
- Composer (PHP package manager)
- Node.js and npm (JavaScript package manager)
- SQLite database server (default database), mongodb

---

## Installation

Follow these steps to set up the project:

1. Clone this repository:
   ```bash
   git clone https://github.com/laraveltoiot/open-iot-cloud-laravel.git
   cd <project-name>
   ```

2. Install all PHP dependencies:
   ```bash
   composer install
   ```

3. Install all JavaScript packages:
   ```bash
   npm install
   ```

4. Copy the `.env` file:
   ```bash
   cp .env.example .env
   ```

5. Generate the application key:
   ```bash
   php artisan key:generate
   ```

6. Configure the database (SQLite is the default database):

    - Create the database file:
      ```bash
      touch database/database.sqlite
      ```

    - Ensure `DB_CONNECTION=sqlite` and the correct database path are set in your `.env` file.

7. Run database migrations and (if needed) seed the database:
   ```bash
   php artisan migrate --seed
   ```

---

## Usage

**Run the application:**

1. Start the application:
   ```bash
   php artisan serve
   ```

2. Open your browser and visit: [http://127.0.0.1:8000](http://127.0.0.1:8000).

**Compile the frontend assets:**

1. Start the development watcher for frontend:
   ```bash
   npm run dev
   ```

2. Or build for production:
   ```bash
   npm run build
   ```

---

## Features

- **Laravel Framework (v12.8.1):** Provides complete features like authentication, routing, database migrations, and more.
- **SQLite Database:** A fast and simple database choice for development and testing.
- **Vite + TailwindCSS:** Enables modern and highly customizable frontend development.
- **Docker with Laravel Sail:** Optional containerized setup for running the application.
- **Database-based Queue System:** Enables asynchronous processing of tasks.
---

## Contributing

1. Clone the repository and create a new branch:
   ```bash
   git checkout -b feature-name
   ```

2. Make your changes and run tests to ensure everything works.
3. Open a **Pull Request** targeting the main branch.

---
