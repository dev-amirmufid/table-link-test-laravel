# TableLink Technical Test

A Laravel 12 application with REST API and Web authentication for managing users, flights, and admin dashboard with charts.

## Requirements

- Docker & Docker Compose
- PHP 8.2+ (for local development without Docker)
- MySQL 8.0+ (provided via Docker)
- Node.js 18+ (for building assets)
- Composer 2+

## Tech Stack

- **Backend**: Laravel 12
- **Database**: MySQL 8.0 (Docker)
- **Authentication**: Session-based (Web) + Token-based (API) with Laravel Sanctum
- **Frontend**: Blade Templates + TailwindCSS + Chart.js
- **Testing**: PHPUnit + Laravel Dusk
- **Container**: Docker + Docker Compose

## Installation

### 1. Clone Repository

```bash
git clone <repository-url> tablelink-test
cd tablelink-test
```

### 2. Start Docker Containers

```bash
docker-compose up -d --build
```

This will start:
- Laravel Application (port 8000)
- MySQL Database (port 3306)
- Nginx Web Server (port 80)

### 3. Configure Environment

The `.env` file is already configured for Docker. If you need to modify:

```bash
# Edit environment file
nano .env

# Key configurations:
DB_HOST=mysql
DB_DATABASE=tablelink
DB_USERNAME=root
DB_PASSWORD=root
```

### 4. Run Migrations

```bash
docker-compose exec app php artisan migrate
```

### 5. Seed Database

```bash
docker-compose exec app php artisan db:seed
```

This creates:
- 1 Admin user
- 20 Dummy users
- 10 Sample flights

### 6. Build Assets (Optional)

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

---

## Installation (Without Docker)

If you prefer to run the application locally without Docker, follow these steps:

### 1. Clone Repository

```bash
git clone <repository-url> tablelink-test
cd tablelink-test
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file to match your local MySQL configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tablelink
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```bash
mysql -u root -p -e "CREATE DATABASE tablelink;"
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database

```bash
php artisan db:seed
```

### 7. Build Assets

```bash
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

The application will be available at http://localhost:8000

---

## Accessing the Application

| Service | URL |
|---------|-----|
| Web Application | http://localhost:8000 |
| MySQL | localhost:3306 |

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@tablelink.com | 12345678 |
| **User** | ahmad@example.com | password123 |

## Features

### Authentication
- [x] User Registration (Web)
- [x] User Login (Web + Session)
- [x] API Authentication (Token-based with Sanctum)
- [x] Role-based Access Control (Admin/User)

### User Management
- [x] List Users (Admin)
- [x] Edit User (Admin)
- [x] Delete User (Admin - Soft Delete)
- [x] Update User Role

### Dashboard (Admin)
- [x] Statistics Cards
- [x] Line Chart (User Registration Trend)
- [x] Bar Chart (Flights by Airline)
- [x] Pie Chart (Flight Class Distribution)

### Flight Information
- [x] List Flights
- [x] Add Flight
- [x] Edit Flight
- [x] Delete Flight
- [x] Scrape Flights (Mockup Data)

### API Endpoints

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| POST | /api/auth/register | Register new user | Public |
| POST | /api/auth/login | Login (get token) | Public |
| POST | /api/auth/logout | Logout | Auth |
| GET | /api/auth/user | Get current user | Auth |
| GET | /api/users | List users | Admin |
| PUT | /api/users/{id} | Update user | Admin |
| DELETE | /api/users/{id} | Delete user | Admin |
| GET | /api/dashboard/charts | Get chart data | Admin |
| GET | /api/flights | List flights | Admin |
| POST | /api/flights/scrape | Scrape flights | Admin |

### Web Routes

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| GET | /login | Login page | Guest |
| POST | /login | Submit login | Guest |
| GET | /register | Register page | Guest |
| POST | /register | Submit register | Guest |
| POST | /logout | Logout | Auth |
| GET | /dashboard | User dashboard | User |
| GET | /admin/dashboard | Admin dashboard | Admin |
| GET | /admin/users | User management | Admin |
| GET | /admin/flights | Flight list | Admin |

## Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app

# Access container shell
docker-compose exec app bash

# Rebuild containers
docker-compose build --no-cache
```

## Testing

### Run Tests (Docker)

```bash
# Run all tests
docker-compose exec app php artisan test

# Run Dusk tests
docker-compose exec app php artisan dusk
```

### Run Tests (Local/Without Docker)

```bash
# Create test database
mysql -u root -p -e "CREATE DATABASE tablelink_test;"

# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthenticationTest

# Run Dusk tests
php artisan dusk

# Run specific Dusk test
php artisan dusk --filter=LoginTest
```

## Project Structure

```
tablelink/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/          # API Controllers
│   │   │   └── Web/           # Web Controllers
│   │   └── Middleware/
│   │       ├── Api/          # API Middleware
│   │       └── Web/           # Web Middleware
│   ├── Models/
│   │   ├── User.php
│   │   └── Flight.php
│   └── Services/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
├── routes/
│   ├── api.php
│   └── web.php
├── docker/
│   ├── local/
│   ├── mysql/
│   └── nginx/
├── docker-compose.yml
└── .env
```

## Troubleshooting

### Database Connection Error

If you get database connection error, make sure DB_HOST is set to `mysql` in .env:

```env
DB_HOST=mysql
```

### Permission Issues

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Clear Cache

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
```

## License

This project is for technical test purposes.
