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
- **Container**: Docker + Docker Compose + Nginx + PHP-FPM

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

- Laravel Application (PHP-FPM) - container: `tablelink-app`
- MySQL Database (port 3306) - container: `tablelink-mysql`
- Nginx Web Server (port 8000) - container: `tablelink-nginx`

### 3. Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### 4. Configure Environment

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

### 5. Run Migrations

```bash
docker-compose exec app php artisan migrate
```

### 6. Seed Database

```bash
docker-compose exec app php artisan db:seed
```

This creates:

- 1 Admin user
- 20 Dummy users
- 10 Sample flights

### 7. Build Assets (Optional)

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

| Service         | URL                   |
| --------------- | --------------------- |
| Web Application | http://localhost:8000 |
| MySQL           | localhost:3306        |

## Demo Credentials

| Role      | Email               | Password    |
| --------- | ------------------- | ----------- |
| **Admin** | admin@tablelink.com | 12345678    |
| **User**  | ahmad@example.com   | password123 |

## Features

### Authentication

- [x] User Registration (Web)
- [x] User Login (Web + Session)
- [x] API Authentication (Token-based with Sanctum)
- [x] Role-based Access Control (Admin/User)

### User Management

- [x] List Users (Admin)
- [x] Create User (Admin)
- [x] Delete User (Admin - Soft Delete)
- [x] Update User Role
- [x] View User Details

> **Note**: Edit feature has been removed from User Management

### Dashboard (Admin)

- [x] Statistics Cards
- [x] Line Chart (User Registration Trend)
- [x] Bar Chart (Flights by Airline)
- [x] Pie Chart (Flight Class Distribution)
- [x] Chart data fetched from API

### Flight Information

- [x] List Flights
- [x] Add Flight
- [x] View Flight Details
- [x] Delete Flight
- [x] Search Flights with Default Filters

> **Note**: Edit feature has been removed from Flight Management

### Flight Search Filters

The flight search applies default filters:

- **From**: CGK (Jakarta)
- **To**: DPS (Denpasar)
- **Class**: Economy
- **Type**: One-way
- **Departure Time**: Before 17:00

---

## API Endpoints

| Method | Endpoint              | Description       | Access |
| ------ | --------------------- | ----------------- | ------ |
| POST   | /api/auth/register    | Register new user | Public |
| POST   | /api/auth/login       | Login (get token) | Public |
| POST   | /api/auth/logout      | Logout            | Auth   |
| GET    | /api/auth/user        | Get current user  | Auth   |
| GET    | /api/users            | List users        | Admin  |
| POST   | /api/users            | Create user       | Admin  |
| DELETE | /api/users/{id}       | Delete user       | Admin  |
| GET    | /api/dashboard/charts | Get chart data    | Admin  |
| GET    | /api/flights          | List flights      | Admin  |
| POST   | /api/flights          | Create flight     | Admin  |
| POST   | /api/flights/scrape   | Scrape flights    | Admin  |
| GET    | /api/flights/{id}     | View flight       | Admin  |
| DELETE | /api/flights/{id}     | Delete flight     | Admin  |

---

## Web Routes

| Method | Endpoint            | Description     | Access |
| ------ | ------------------- | --------------- | ------ |
| GET    | /login              | Login page      | Guest  |
| POST   | /login              | Submit login    | Guest  |
| GET    | /register           | Register page   | Guest  |
| POST   | /register           | Submit register | Guest  |
| POST   | /logout             | Logout          | Auth   |
| GET    | /dashboard          | User dashboard  | User   |
| GET    | /admin/dashboard    | Admin dashboard | Admin  |
| GET    | /admin/users        | User management | Admin  |
| POST   | /admin/users        | Create user     | Admin  |
| DELETE | /admin/users/{id}   | Delete user     | Admin  |
| GET    | /admin/users/{id}   | View user       | Admin  |
| GET    | /admin/flights      | Flight list     | Admin  |
| POST   | /admin/flights      | Create flight   | Admin  |
| DELETE | /admin/flights/{id} | Delete flight   | Admin  |
| GET    | /admin/flights/{id} | View flight     | Admin  |

---

## Docker Configuration

### Container Names

- `tablelink-app` - Laravel PHP-FPM application
- `tablelink-mysql` - MySQL 8.0 database
- `tablelink-nginx` - Nginx web server

### Port Mapping

- **8000** - Web Application (Nginx)
- **3306** - MySQL Database

### Docker Commands

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

# Check container status
docker-compose ps
```

---

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

### Test Database Configuration

Tests use separate database `tablelink_test` with the following configuration in `phpunit.xml`:

```xml
<env name="DB_HOST" value="127.0.0.1"/>
<env name="DB_DATABASE" value="tablelink_test"/>
```

---

## Project Structure

```
tablelink/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/          # API Controllers
│   │   │   └── Web/           # Web Controllers
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   └── Flight.php
│   ├── Services/
│   │   └── FlightService.php
│   └── View/
│       └── Components/       # Reusable View Components
│           ├── LineChart.php
│           ├── BarChart.php
│           └── PieChart.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── css/
│   ├── js/
│   │   ├── app.js
│   │   ├── bootstrap.js      # Axios + CSRF token config
│   │   └── pages/             # Page-specific JS
│   └── views/
│       ├── components/       # Blade components
│       └── layouts/
├── routes/
│   ├── api.php
│   └── web.php
├── docker/
│   ├── local/
│   │   └── Dockerfile        # PHP-FPM configuration
│   ├── mysql/
│   │   └── local.cnf
│   └── nginx/
│       └── local.conf
├── docker-compose.yml
├── vite.config.js
└── .env
```

---

## Key Implementation Details

### View Components

The dashboard charts are implemented as reusable Blade components:

- `<x-line-chart>` - Line chart for user registration trends
- `<x-bar-chart>` - Bar chart for flights by airline
- `<x-pie-chart>` - Pie chart for flight class distribution

Example usage:

```blade
<x-line-chart
    title="User Registration Trend"
    chart-id="lineChart"
    :data="$chartData"
/>
```

### CSRF Token Handling

CSRF token is automatically included in all axios requests via `resources/js/bootstrap.js`:

```javascript
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (csrfToken) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;
}
```

---

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

### 502 Bad Gateway Error

If you encounter 502 Bad Gateway, ensure PHP-FPM is running:

```bash
# Check if PHP-FPM is running
docker-compose exec app php-fpm

# Rebuild containers
docker-compose down
docker-compose up -d --build
```

### Temp Directory Permission

If you see tempnam() warnings, the PHP-FPM is configured with proper temp directories in `docker/local/Dockerfile`:

```dockerfile
RUN echo "[www]" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "php_admin_value[sys_temp_dir] = /tmp" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo "php_admin_value[open_basedir] = /tmp:/var/www/html" >> /usr/local/etc/php-fpm.d/zz-docker.conf
```

---

## License

This project is for technical test purposes.
