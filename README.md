# Laravel Application

This is a project built using [Laravel](https://laravel.com/), a modern PHP framework designed for building scalable and
efficient web applications.

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

6. NPM build:

    ```bash
            npm build
       ```

7. Run database migrations and (if needed) seed the database:
   ```bash
   php artisan migrate
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
