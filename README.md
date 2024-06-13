<h1 align="center">Laravel Boilerplate (Web, Api, Role-based System)</h1>

<h3>Used Packages</h3>
<ul>
    <li>spatie/laravel-permission</li>
    <li>laravel/sanctum</li>
    <li>yajra/laravel-datatables-oracle</li>
    <li>brian2694/laravel-toastr</li>
</ul>

## Installation Steps

1. **Clone this repo**:

    ```bash
    git clone https://github.com/parthdev9747/web-api-rolebased-laravel--boilerplate.git
    ```

2. **Install dependencies**:

    ```bash
    composer install
    ```

3. **Install dependencies**:

    ```bash
    npm install && npm run build
    ```

4. **Database migrate**:

    ```bash
    php artisan migrate
    ```

5. **Generate data into database**:

    ```bash
    php artisan db:seed
    php artisan db:seed --class=CreateAdminUserSeeder
    ```

6. **Run application**:

    ```bash
    php artisan serve
    ```
