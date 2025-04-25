# Laravel Transactions Management System

A modern application built with Laravel 12, Livewire, and Tailwind CSS for managing transactions and users with an easy-to-use interface.

## Features

- User authentication and role-based authorization
- Transaction management (credits and debits)
- Dashboard with key statistics
- Soft delete functionality for users and transactions
- Date range filtering for transactions
- Sorting and searching capabilities
- Responsive design

## Requirements

- PHP >= 8.1
- Composer
- MySQL or PostgreSQL
- Node.js & NPM

## Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/yourusername/transactions-management.git
cd transactions-management
```
### Step 2: Install dependencies

```bash
composer install
npm install
```
### Step 3: Set up the environment

```bash
cp .env.example .env
php artisan key:generate
```
### Step 4: Run migrations and seeders
```bash
php artisan migrate --seed
```
### Step 5: Build assets

```bash
npm run build
```
### Step 6: Start the server

```bash
php artisan serve
```
### Usage
### Default Admin Credentials
- Email: admin@admin.com
- Password: universal

### Main Components

### Dashboard
The dashboard provides an overview of:

- Active and inactive users
- Credit and debit transactions
- Total transactions
- Current month's transactions

### User Management

- View all users
- Create new users
- Edit existing users
- Deactivate users (soft delete)
- Assign roles

### Transaction Management

- View all transactions
- Create new transactions (credit/debit)
- Edit existing transactions
- Filter transactions by date range
- Sort by different columns

## Estructura de directorios

- **app/Http/Controllers**: Contiene los controladores de la aplicación.
- **app/Livewire**: Contiene los componentes de Livewire.
- **app/Models**: Contiene los modelos de Eloquent.
- **app/Services**: Contiene las clases de servicio para la lógica de negocio.
- **app/Traits**: Contiene traits reutilizables.
- **resources/views/livewire**: Contiene las vistas de Livewire.

## Desarrollo

### Agregar una nueva funcionalidad

1. Crear una nueva rama:

   ```bash
   git checkout -b feature/your-feature-name
    ```

I## Implement Your Feature

1. Implement your feature.
2. Write tests for your feature.

## Troubleshooting
### Common Issues

### Common Issues

#### Database connection issues

- Make sure your database credentials in `.env` are correct.
- Ensure your database server is running.

#### Permissions issues

- Make sure the `storage` and `bootstrap/cache` directories are writable by the web server.

#### Page not found errors

- Run `php artisan route:list` to check if your routes are registered correctly.

Run php artisan route:list to check if your routes are registered correctly


## License

This project is licensed under the MIT License - see the `LICENSE` file for details.

## Acknowledgements

- Laravel
- Livewire
- Tailwind CSS
- FluxUI

Feel free to contribute to this project by submitting issues or pull requests!
