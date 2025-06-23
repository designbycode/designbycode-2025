# Laravel Project with Filament

This is a skeleton application for the Laravel framework, enhanced with the Filament admin panel and Graham Campbell's Markdown package for content rendering.

## Key Features & Dependencies

*   **Laravel:** A web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:
    *   [Simple, fast routing engine](https://laravel.com/docs/routing).
    *   [Powerful dependency injection container](https://laravel.com/docs/container).
    *   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
    *   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
    *   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
    *   [Robust background job processing](https://laravel.com/docs/queues).
    *   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).
*   **Filament:** A collection of tools for rapidly building beautiful TALL stack admin panels, forms, and tables for Laravel.
*   **Graham Campbell's Markdown:** A CommonMark compliant Markdown parser for Laravel.

## Installation Instructions

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2.  **Copy environment file:**
    Laravel utilizes an `.env` file for configuration. Copy the example file:
    ```bash
    cp .env.example .env
    ```
    *You should then update `.env` with your specific configuration, such as database credentials, mail server settings, etc.*

3.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

4.  **Install NPM dependencies:**
    ```bash
    npm install
    ```

5.  **Generate application key:**
    This key is used for encryption and should be set securely.
    ```bash
    php artisan key:generate
    ```

6.  **Run database migrations:**
    This will create the necessary database tables.
    ```bash
    php artisan migrate
    ```

7.  **(Optional) Seed the database:**
    If the project includes database seeders for initial data:
    ```bash
    php artisan db:seed
    ```

## Running the Application

### Development Server

To run the Laravel development server (usually on `http://127.0.0.1:8000`):
```bash
php artisan serve
```

### Frontend Assets (Vite)

To compile frontend assets (CSS, JavaScript) and run the Vite development server with hot module replacement:
```bash
npm run dev
```

This command is also available as part of a concurrent script defined in `composer.json`:
```json
"scripts": {
    "dev": [
        "Composer\\Config::disableProcessTimeout",
        "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite"
    ]
}
```
You can run this using:
```bash
composer run dev
```
This will start the PHP development server, queue worker, log tailing (Pail), and Vite dev server simultaneously.

## Running Tests

Laravel provides a robust testing environment using PHPUnit and Pest. To run your application's test suite:

```bash
php artisan test
```

This command is also available as a script in `composer.json`:
```json
"scripts": {
    "test": [
        "@php artisan config:clear --ansi",
        "@php artisan test"
    ]
}
```
You can run this using:
```bash
composer run test
```
This will clear any cached configuration before running the tests.

## Contributing

Contributions are welcome! If you have a feature request, bug report, or want to contribute code, please feel free to:

*   Open an issue on the project's issue tracker.
*   Fork the repository and submit a pull request.

Please ensure that your code adheres to the project's coding standards (e.g., by running `composer pint`) and that all tests pass before submitting a pull request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
