# TableLink Technical Test - Analisa dan Instruction Plan

## 1. Analisis Requirements

### 1.1 Project Overview

| Aspek            | Detail                                         |
| ---------------- | ---------------------------------------------- |
| **Project Name** | TableLink Technical Test                       |
| **Company**      | PT. TableLink Digital Inovasi                  |
| **Framework**    | Laravel 12                                     |
| **Architecture** | MVC dengan REST API + Web Controllers Terpisah |
| **Deployment**   | Dockerized                                     |

### 1.2 Teknologi Stack

```
┌─────────────────────────────────────────┐
│           Technology Stack              │
├─────────────────────────────────────────┤
│  Backend     : Laravel 12               │
│  Database    : MySQL (Docker)           │
│  Auth        : Laravel Auth (Session)  │
│  API         : Laravel API Routes       │
│  Web         : Laravel Web Routes       │
│  Charts      : Chart.js                 │
│  Container   : Docker & Docker Compose │
│  Testing     : PHPUnit                  │
└─────────────────────────────────────────┘
```

### 1.3 User Roles

| Role      | Akses                                                     |
| --------- | --------------------------------------------------------- |
| **Admin** | Dashboard + Charts + User Management + Flight Information |
| **User**  | Basic Dashboard (kosong)                                  |

---

## 2. Feature Breakdown

### 2.1 Authentication Module (API & Web)

- [ ] **Registration**
  - API: `POST /api/auth/register`
  - Web: `POST /register`
  - Email & Password
  - Email harus unique
  - Default role: User
- [ ] **Login**
  - API: `POST /api/auth/login` (token-based)
  - Web: `POST /login` (session-based)
  - Update `last_login` timestamp saat success
- [ ] **Logout**
  - API: `POST /api/auth/logout`
  - Web: `POST /logout`

### 2.2 Authorization Module

- [ ] **Middleware**
  - `role:admin` middleware (API & Web)
  - `role:user` middleware (API & Web)
- [ ] **Policies/Gates**
  - UserPolicy untuk CRUD operations

### 2.3 User Management (Admin Only - API)

- [ ] **List Users**
  - Endpoint: `GET /api/users`
  - Pagination: 10 per page
  - Exclude soft-deleted users
- [ ] **Update User**
  - Endpoint: `PUT /api/users/{id}`
  - Email unique validation (exclude current user)
- [ ] **Delete User**
  - Endpoint: `DELETE /api/users/{id}`
  - Soft delete menggunakan `deleted_at`

### 2.4 Dashboard (Admin Only - API & Web)

- [ ] **Line Chart**
  - Reference:
    https://gist.githubusercontent.com/rachmanlatif/323bd55b284774bf98e11225ce2374e1/raw/c24b38cde2cc67a8fe07778187fea565b369049c/gistfile1.txt
- [ ] **Vertical Bar Chart**
  - Reference:
    https://gist.githubusercontent.com/rachmanlatif/51277a2070e6cd240bf471d9aead29d7/raw/d512dc21f6c336216368a22f363e26b1fcb69334/gistfile1.txt
- [ ] **Pie Chart**
  - Reference:
    https://gist.githubusercontent.com/rachmanlatif/ad0290b004c1bfa9ded5f872f680fea8/raw/83e9407fb76e7f8e2a24f09b35b42ec39baa6bfa/gistfile1.txt

**Notes**

- Charts are modularized into reusable view components
- Data is provided via REST API endpoints

### 2.5 Flight Information (Admin - API & Web)

- [ ] **Data Collection**
  - Source: Tiket.com
  - Route: Jakarta (CGK) → Bali (DPS)
  - Type: One-way
  - Class: Economy
  - Departure: Before 17:00
- [ ] **Data Fields**
  - Airline name
  - Flight number
  - Departure time
  - Price
  - Departure airport
  - Arrival airport
- [ ] **Display**
  - REST API endpoint
  - Data-table view (Web)

---

## 3. Database Schema

### 3.1 Users Table

| Field      | Type      | Description       |
| ---------- | --------- | ----------------- |
| id         | bigint    | Primary key       |
| name       | string    | User full name    |
| email      | string    | Unique email      |
| password   | string    | Hashed password   |
| role       | enum      | 'admin' or 'user' |
| last_login | timestamp | Last login time   |
| created_at | timestamp | Creation time     |
| updated_at | timestamp | Update time       |
| deleted_at | timestamp | Soft delete       |

### 3.2 Flights Table

| Field             | Type      | Description           |
| ----------------- | --------- | --------------------- |
| id                | bigint    | Primary key           |
| airline_name      | string    | Nama maskapai         |
| flight_number     | string    | Nomor penerbangan     |
| departure_time    | time      | Waktu keberangkatan   |
| price             | decimal   | Harga ticket          |
| departure_airport | string    | Airport keberangkatan |
| arrival_airport   | string    | Airport tujuan        |
| flight_type       | string    | Tipe penerbangan      |
| class_type        | string    | Kelas penerbangan     |
| created_at        | timestamp | Creation time         |
| updated_at        | timestamp | Update time           |

---

## 4. Controller Architecture

### 4.1 API Controllers (Backend Only)

```
app/Http/Controllers/Api/
├── Auth/
│   ├── ApiAuthController.php      # Register, Login, Logout (Token)
│   └── ApiUserController.php     # Current user profile
├── Users/
│   └── ApiUserController.php     # CRUD Users (Admin only)
├── Dashboard/
│   └── ApiDashboardController.php # Chart data JSON
└── Flights/
    └── ApiFlightController.php   # Flight CRUD + Scraping
```

### 4.2 Web Controllers (Frontend + Rendering)

```
app/Http/Controllers/Web/
├── Auth/
│   ├── WebAuthController.php     # Register, Login, Logout (Session)
│   └── WebUserController.php     # User profile page
├── Dashboard/
│   ├── WebDashboardController.php # Admin dashboard view
│   └── UserDashboardController.php # User basic dashboard
├── Users/
│   └── WebUserController.php    # User management UI (Admin)
└── Flights/
    └── WebFlightController.php   # Flight information page (Admin)
```

### 4.3 Service Layer

```
app/Services/
├── AuthService.php        # Authentication logic
├── UserService.php        # User CRUD logic
├── FlightService.php      # Flight scraping & CRUD
└── DashboardService.php   # Chart data generation
```

---

## 5. API Endpoints

### 5.1 Authentication API

| Method | Endpoint           | Description        | Access |
| ------ | ------------------ | ------------------ | ------ |
| POST   | /api/auth/register | Register new user  | Public |
| POST   | /api/auth/login    | User login (token) | Public |
| POST   | /api/auth/logout   | User logout        | Auth   |
| GET    | /api/auth/user     | Get current user   | Auth   |

### 5.2 User Management API

| Method | Endpoint        | Description            | Access |
| ------ | --------------- | ---------------------- | ------ |
| GET    | /api/users      | List users (paginated) | Admin  |
| GET    | /api/users/{id} | Get user detail        | Admin  |
| PUT    | /api/users/{id} | Update user            | Admin  |
| DELETE | /api/users/{id} | Delete user (soft)     | Admin  |

### 5.3 Dashboard API

| Method | Endpoint              | Description         | Access |
| ------ | --------------------- | ------------------- | ------ |
| GET    | /api/dashboard/charts | Get chart data JSON | Admin  |
| GET    | /api/dashboard/stats  | Get dashboard stats | Admin  |

### 5.4 Flight Information API

| Method | Endpoint            | Description           | Access |
| ------ | ------------------- | --------------------- | ------ |
| GET    | /api/flights        | Get flight data JSON  | Admin  |
| POST   | /api/flights/scrape | Scrape from Tiket.com | Admin  |
| GET    | /api/flights/{id}   | Get flight detail     | Admin  |
| PUT    | /api/flights/{id}   | Update flight         | Admin  |
| DELETE | /api/flights/{id}   | Delete flight         | Admin  |

---

## 6. Web Routes

### 6.1 Authentication Web

| Method | Endpoint  | Description     | Access |
| ------ | --------- | --------------- | ------ |
| GET    | /login    | Login page      | Public |
| POST   | /login    | Submit login    | Public |
| GET    | /register | Register page   | Public |
| POST   | /register | Submit register | Public |
| POST   | /logout   | Logout          | Auth   |

### 6.2 Dashboard Web

| Method | Endpoint         | Description     | Access |
| ------ | ---------------- | --------------- | ------ |
| GET    | /dashboard       | User dashboard  | Auth   |
| GET    | /admin/dashboard | Admin dashboard | Admin  |

### 6.3 User Management Web

| Method | Endpoint               | Description    | Access |
| ------ | ---------------------- | -------------- | ------ |
| GET    | /admin/users           | User list page | Admin  |
| GET    | /admin/users/{id}/edit | Edit user page | Admin  |

### 6.4 Flight Information Web

| Method | Endpoint              | Description        | Access |
| ------ | --------------------- | ------------------ | ------ |
| GET    | /admin/flights        | Flight list page   | Admin  |
| GET    | /admin/flights/create | Create flight page | Admin  |

---

## 7. Docker Configuration

### 7.1 Local Development (docker-compose.yml)

```yaml
version: "3.8"

services:
  # Laravel Application
  app:
    build:
      context: .
      dockerfile: docker/local/Dockerfile
    container_name: tablelink-app-local
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=local
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=tablelink
      - DB_USERNAME=root
      - DB_PASSWORD=root
    depends_on:
      - mysql
    networks:
      - tablelink-network

  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: tablelink-mysql-local
    ports:
      - "3306:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=tablelink
      - MYSQL_USER=tablelink
      - MYSQL_PASSWORD=tablelink
    volumes:
      - mysql-data:/var/lib/mysql
      - ./docker/mysql/local.cnf:/etc/mysql/conf.d/custom.cnf
    networks:
      - tablelink-network

  # Nginx Web Server
  nginx:
    image: nginx:alpine
    container_name: tablelink-nginx-local
    ports:
      - "80:80"
    volumes:
      - ./docker/nginx/local.conf:/etc/nginx/conf.d/default.conf
      - ./public:/var/www/html/public
    depends_on:
      - app
    networks:
      - tablelink-network

volumes:
  mysql-data:

networks:
  tablelink-network:
    driver: bridge
```

### 7.2 Dockerfile Local

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

### 7.3 Nginx Configuration Local

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 8. API vs Web Integration Analysis

### 8.1 Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        Client Layer                             │
├─────────────────────────┬───────────────────────────────────────┤
│    Web Browser          │          Mobile/External API          │
│  (Blade Templates)      │           (REST Clients)              │
└───────────┬─────────────┴───────────────┬───────────────────────┘
            │                               │
            ▼                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Laravel Application                         │
├─────────────────────────┬───────────────────────────────────────┤
│    Web Routes           │            API Routes                 │
│  (routes/web.php)       │          (routes/api.php)             │
├───────────┬─────────────┴───────────────┬───────────────────────┤
│  Web      │                              │     API               │
│ Controllers│                             │  Controllers          │
│  (View)   │                              │  (JSON Response)      │
└───────────┴──────────────────────────────┴──────────────────────┘
            │                              │
            ▼                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Service Layer                               │
│   AuthService | UserService | FlightService | DashboardService │
└─────────────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Model Layer                                 │
│              User Model | Flight Model                           │
└─────────────────────────────────────────────────────────────────┘
```

### 8.2 Integration Pattern

| Aspek              | API                      | Web               |
| ------------------ | ------------------------ | ----------------- |
| **Authentication** | Token (Sanctum/Passport) | Session           |
| **Response**       | JSON                     | Blade View + HTML |
| **Routes**         | `/api/*`                 | `/*`              |
| **Controllers**    | `Api\*Controller`        | `Web\*Controller` |
| **Middleware**     | auth:sanctum             | web + auth        |
| **Validation**     | Form Request             | Form Request      |

### 8.3 Data Flow

#### API Flow

```
Client Request → API Route → API Controller → Service → Model → JSON Response
```

#### Web Flow

```
Client Request → Web Route → Web Controller → Service → Model → Blade View (HTML)
```

---

## 9. Project Structure

```
table-link-test/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── Auth/
│   │   │   │   │   └── ApiAuthController.php
│   │   │   │   ├── Users/
│   │   │   │   │   └── ApiUserController.php
│   │   │   │   ├── Dashboard/
│   │   │   │   │   └── ApiDashboardController.php
│   │   │   │   └── Flights/
│   │   │   │       └── ApiFlightController.php
│   │   │   └── Web/
│   │   │       ├── Auth/
│   │   │       │   └── WebAuthController.php
│   │   │       ├── Dashboard/
│   │   │       │   ├── WebDashboardController.php
│   │   │       │   └── UserDashboardController.php
│   │   │       ├── Users/
│   │   │       │   └── WebUserController.php
│   │   │       └── Flights/
│   │   │           └── WebFlightController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── Api/
│   │       │   ├── RegisterRequest.php
│   │       │   └── LoginRequest.php
│   │       └── Web/
│   │           ├── RegisterRequest.php
│   │           └── LoginRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Flight.php
│   ├── Policies/
│   │   └── UserPolicy.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── UserService.php
│   │   ├── FlightService.php
│   │   └── DashboardService.php
│   └── Providers/
├── database/
│   └── migrations/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── users/
│   │   └── flights/
│   └── js/
│       └── components/
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
│   └── Unit/
├── docker/
│   ├── local/
│   │   ├── Dockerfile
│   │   └── nginx.conf
│   ├── prod/
│   │   ├── Dockerfile
│   │   └── nginx.conf
│   └── mysql/
├── docker-compose.yml
└── .env
```

---

## 10. Implementation Steps

### Phase 1: Setup & Configuration

- [ ] Initialize Laravel 12 project
- [ ] Setup Docker environment (local only)
- [ ] Configure MySQL database
- [ ] Setup environment variables (.env)
- [ ] Configure Nginx

### Phase 2: API Authentication

- [ ] Create User model dengan soft deletes
- [ ] Create migration untuk users table
- [ ] Create ApiAuthController
- [ ] Setup Laravel Sanctum
- [ ] Create token-based authentication

### Phase 3: Web Authentication

- [ ] Create WebAuthController
- [ ] Setup session-based authentication
- [ ] Create login/register views

### Phase 4: Authorization

- [ ] Create RoleMiddleware
- [ ] Create UserPolicy
- [ ] Setup role-based access routes

### Phase 5: User Management (API)

- [ ] Create ApiUserController
- [ ] Create UserService
- [ ] Implement CRUD operations
- [ ] Add pagination (10 per page)

### Phase 6: User Management (Web)

- [ ] Create WebUserController
- [ ] Create user management views

### Phase 7: Dashboard (Web)

- [ ] Create WebDashboardController
- [ ] Create chart view components
- [ ] Implement Line Chart
- [ ] Implement Bar Chart
- [ ] Implement Pie Chart

### Phase 8: Flight Information (API)

- [ ] Create Flight model & migration
- [ ] Create ApiFlightController
- [ ] Create FlightService
- [ ] Implement web scraping service

### Phase 9: Flight Information (Web)

- [ ] Create WebFlightController
- [ ] Create flight data-table view

### Phase 10: Testing

- [ ] Write unit tests for API Auth
- [ ] Write unit tests for Web Auth
- [ ] Write unit tests for Authorization
- [ ] Write unit tests for User CRUD
- [ ] Write unit tests for Dashboard

> **NOTE**: Production deployment di-skip - Local environment only

---

## 11. Docker Commands

### Local Development

```bash
# Start local environment
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop environment
docker-compose down

# Rebuild containers
docker-compose build --no-cache
```

---

## 12. Security Checklist

- [ ] Password hashing menggunakan bcrypt
- [ ] Role-based middleware enforcement
- [ ] CSRF protection
- [ ] Input validation (Form Requests)
- [ ] SQL injection prevention (Eloquent ORM)
- [ ] XSS prevention (Blade escaping)
- [ ] Soft delete untuk data preservation
- [ ] API rate limiting
- [ ] HTTPS enforcement (Production)

---

## 13. Testing Requirements

### Unit Tests Coverage

| Module             | Test Cases                       |
| ------------------ | -------------------------------- |
| API Authentication | Register, Login, Logout, Token   |
| Web Authentication | Register, Login, Logout, Session |
| Authorization      | Role check, Middleware           |
| User CRUD          | Create, Read, Update, Delete     |
| Dashboard          | Data generation                  |
| Flight Service     | Mockup data, CRUD                |

### Browser Tests (Laravel Dusk)

| Test Case         | Description                |
| ----------------- | -------------------------- |
| User Login        | Login via web interface    |
| User Registration | Register new user via web  |
| Admin Dashboard   | Access admin dashboard     |
| User Management   | CRUD operations via web UI |
| Flight List       | View flight data table     |

### Install Dusk

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

### Run Tests

```bash
# Run all tests
php artisan test

# Run Dusk tests only
php artisan dusk

# Run specific Dusk test
php artisan dusk --filter=LoginTest
```

---

## 14. Middleware Architecture (Token vs Session)

### 14.1 Authentication Flow Comparison

```mermaid
graph TB
    subgraph "API Flow (Token-based)"
        A1[Client] -->|POST /api/auth/login| B1[ApiAuthController]
        B1 --> C1[AuthService]
        C1 --> D1[User Model]
        D1 --> E1[Generate Token]
        E1 --> F1[Return JSON + Token]
        F1 --> G1[Client stores token]
        G1 -->|Authorization: Bearer {token}| H1[ApiAuthenticate Middleware]
        H1 --> I1[Allow/Deny]
    end

    subgraph "Web Flow (Session-based)"
        A2[Browser] -->|POST /login| B2[WebAuthController]
        B2 --> C2[AuthService]
        C2 --> D2[User Model]
        D2 --> E2[Create Session]
        E2 --> F2[Set Session Cookie]
        F2 --> G2[Redirect + Session]
        G2 -->|Cookie: session_id| H2[web Middleware]
        H2 --> I2[Allow/Deny]
    end
```

### 14.2 Middleware Structure

```
app/Http/Middleware/
├── Api/
│   ├── ApiAuthenticate.php      # Token validation (Sanctum)
│   ├── RoleApiMiddleware.php     # Role check for API
│   └── ThrottleRequests.php      # Rate limiting API
└── Web/
    ├── Authenticate.php          # Session validation (Laravel built-in)
    ├── RoleWebMiddleware.php     # Role check for Web
    └── VerifyCsrfToken.php       # CSRF protection (Laravel built-in)
```

### 14.3 API Middleware Implementation

#### ApiAuthenticate Middleware

```php
<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuthenticate
{
    /**
     * Handle an incoming API request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for Bearer token
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. No token provided.'
            ], 401);
        }

        // Validate token
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid token.'
            ], 401);
        }

        // Check if token is expired
        if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token expired.'
            ], 401);
        }

        // Set user in request
        $request->setUserResolver(function () use ($accessToken) {
            return $accessToken->tokenable;
        });

        return $next($request);
    }
}
```

#### RoleApi Middleware

```php
<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleApiMiddleware
{
    /**
     * Handle an incoming API request.
     *
     * @param  \Closure\Illuminate\Http\Request  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        // Check if user has required role
        if ($user->role !== $role) {
            // Also check if user is admin (admin has access to everything)
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => "Forbidden. You don't have {$role} access."
                ], 403);
            }
        }

        return $next($request);
    }
}
```

### 14.4 Web Middleware Implementation

#### RoleWeb Middleware

```php
<?php

namespace App\Http\Middleware\Web;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleWebMiddleware
{
    /**
     * Handle an incoming web request.
     *
     * @param  \Closure\Illuminate\Http\Request  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has required role
        if ($user->role !== $role) {
            // Check if user is admin
            if ($user->role !== 'admin') {
                // User doesn't have access
                abort(403, "You don't have {$role} access.");
            }
        }

        return $next($request);
    }
}
```

### 14.5 Kernel Configuration

#### API Kernel (app/Http/Kernel.php - API section)

```php
protected $middlewareAliases = [
    // Laravel built-in
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

    // Custom API middleware
    'api.auth' => \App\Http\Middleware\Api\ApiAuthenticate::class,
    'api.role' => \App\Http\Middleware\Api\RoleApiMiddleware::class,
    'api.throttle' => \App\Http\Middleware\Api\ApiThrottleRequests::class,
];
```

#### Web Kernel (app/Http/Kernel.php - Web section)

```php
protected $middlewareAliases = [
    // Laravel built-in
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'csrf' => \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    'web' => \Illuminate\Cookie\Middleware\EncryptCookies::class,

    // Custom Web middleware
    'web.role' => \App\Http\Middleware\Web\RoleWebMiddleware::class,
];
```

### 14.6 Route Configuration

#### API Routes with Middleware

```php
// routes/api.php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\Users\ApiUserController;
use App\Http\Controllers\Api\Dashboard\ApiDashboardController;
use App\Http\Controllers\Api\Flights\ApiFlightController;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
});

// Protected API routes (Token-based)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/user', [ApiAuthController::class, 'user']);
        Route::post('/logout', [ApiAuthController::class, 'logout']);
    });

    // User Management (Admin only)
    Route::middleware('role:admin')->prefix('users')->group(function () {
        Route::get('/', [ApiUserController::class, 'index']);
        Route::get('/{id}', [ApiUserController::class, 'show']);
        Route::put('/{id}', [ApiUserController::class, 'update']);
        Route::delete('/{id}', [ApiUserController::class, 'destroy']);
    });

    // Dashboard (Admin only)
    Route::middleware('role:admin')->prefix('dashboard')->group(function () {
        Route::get('/charts', [ApiDashboardController::class, 'charts']);
    });

    // Flight Information (Admin only)
    Route::middleware('role:admin')->prefix('flights')->group(function () {
        Route::get('/', [ApiFlightController::class, 'index']);
        Route::post('/scrape', [ApiFlightController::class, 'scrape']);
        Route::get('/{id}', [ApiFlightController::class, 'show']);
        Route::put('/{id}', [ApiFlightController::class, 'update']);
        Route::delete('/{id}', [ApiFlightController::class, 'destroy']);
    });
});
```

#### Web Routes with Middleware

```php
// routes/web.php

use App\Http\Controllers\Web\Auth\WebAuthController;
use App\Http\Controllers\Web\Dashboard\WebDashboardController;
use App\Http\Controllers\Web\Dashboard\UserDashboardController;
use App\Http\Controllers\Web\Users\WebUserController;
use App\Http\Controllers\Web\Flights\WebFlightController;
use Illuminate\Support\Facades\Route;

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

// Protected routes (Session-based)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [WebDashboardController::class, 'index']);

        // User Management
        Route::get('/users', [WebUserController::class, 'index']);
        Route::get('/users/{id}/edit', [WebUserController::class, 'edit']);
        Route::put('/users/{id}', [WebUserController::class, 'update']);
        Route::delete('/users/{id}', [WebUserController::class, 'destroy']);

        // Flight Information
        Route::get('/flights', [WebFlightController::class, 'index']);
        Route::get('/flights/create', [WebFlightController::class, 'create']);
        Route::post('/flights', [WebFlightController::class, 'store']);
        Route::get('/flights/{id}/edit', [WebFlightController::class, 'edit']);
        Route::put('/flights/{id}', [WebFlightController::class, 'update']);
        Route::delete('/flights/{id}', [WebFlightController::class, 'destroy']);
    });
});
```

### 14.7 Auth Service Integration

#### AuthService for Token-based (API)

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    /**
     * Login via API (Token-based)
     */
    public function loginApi(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }

        // Update last login
        $user->update(['last_login' => now()]);

        // Create Sanctum token
        $token = $user->createToken('api-token', ['*']);

        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    /**
     * Logout via API (Revoke token)
     */
    public function logoutApi(User $user): void
    {
        // Revoke all tokens
        $user->tokens()->delete();
    }
}
```

#### AuthService for Session-based (Web)

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Login via Web (Session-based)
     */
    public function loginWeb(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        // Update last login
        $user->update(['last_login' => now()]);

        return $user;
    }
}
```

### 14.8 Middleware Comparison Table

| Aspek             | API Middleware       | Web Middleware   |
| ----------------- | -------------------- | ---------------- |
| **Auth Type**     | Token (Bearer)       | Session (Cookie) |
| **Guard**         | sanctum              | web              |
| **Response**      | JSON                 | Redirect/View    |
| **Storage**       | Authorization Header | Session/Cookie   |
| **Expiration**    | Configurable         | Session lifetime |
| **CSRF**          | Not required         | Required         |
| **Rate Limiting** | Yes                  | Optional         |

### 14.9 Register Middleware in Kernel

```php
// app/Http/Kernel.php

protected $middlewareAliases = [
    // API Middleware
    'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'api.auth' => \App\Http\Middleware\Api\ApiAuthenticate::class,
    'api.role' => \App\Http\Middleware\Api\RoleApiMiddleware::class,
    'api.throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',

    // Web Middleware
    'web' => \Illuminate\Cookie\Middleware\EncryptCookies::class,
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'web.role' => \App\Http\Middleware\Web\RoleWebMiddleware::class,
    'csrf' => \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
];
```

---

## 15. Architecture Revision: Web as API Client

### 15.1 Architecture Overview

**IMPORTANT: Web layer adalah API Client, bukan database caller!**

```
┌─────────────────────────────────────────────────────────────────┐
│                        Client Layer                             │
├─────────────────────────┬───────────────────────────────────────┤
│    Web Browser          │          Mobile/External API          │
│  (Blade Templates)      │           (REST Clients)              │
│         │               │                    │                   │
│         ▼               │                    ▼                   │
│    Web Controllers      │            API Controllers             │
│  (API Client Only)      │         (Database + Logic)            │
│         │               │                    │                   │
│         └───────┬───────┘                    │                   │
│                 │                            │                   │
│                 ▼                            ▼                   │
│          ┌─────────────────────────────────────────┐            │
│          │         API Internal HTTP Calls          │            │
│          │   (Web Controller → API Controller)      │            │
│          └─────────────────────────────────────────┘            │
│                          │                                       │
│                          ▼                                       │
│          ┌─────────────────────────────────────────┐            │
│          │         Service Layer                   │            │
│          │   AuthService | UserService | etc       │            │
│          └─────────────────────────────────────────┘            │
│                          │                                       │
│                          ▼                                       │
│          ┌─────────────────────────────────────────┐            │
│          │         Model Layer                     │            │
│          │     User Model | Flight Model           │            │
│          └─────────────────────────────────────────┘            │
│                          │                                       │
│                          ▼                                       │
│          ┌─────────────────────────────────────────┐            │
│          │         Database (MySQL)                │            │
│          └─────────────────────────────────────────┘            │
└─────────────────────────────────────────────────────────────────┘
```

### 15.2 Web Controller sebagai API Client

**Konsep:**

- Web Controllers TIDAK melakukan database query langsung
- Web Controllers memanggil API Controllers melalui HTTP requests
- Web Controllers hanya merender view dan menerima response dari API

### 15.3 ApiClientService - HTTP Client untuk Web

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiClientService
{
    /**
     * Make GET request to API
     */
    public function get(string $endpoint, array $params = [])
    {
        return Http::withToken($this->getToken())
            ->get(config('app.api_url') . $endpoint, $params);
    }

    /**
     * Make POST request to API
     */
    public function post(string $endpoint, array $data = [])
    {
        return Http::withToken($this->getToken())
            ->post(config('app.api_url') . $endpoint, $data);
    }

    /**
     * Make PUT request to API
     */
    public function put(string $endpoint, array $data = [])
    {
        return Http::withToken($this->getToken())
            ->put(config('app.api_url') . $endpoint, $data);
    }

    /**
     * Make DELETE request to API
     */
    public function delete(string $endpoint)
    {
        return Http::withToken($this->getToken())
            ->delete(config('app.api_url') . $endpoint);
    }

    /**
     * Get token from session
     */
    protected function getToken(): ?string
    {
        return session('api_token');
    }
}
```

### 15.4 Revised Web Controller Implementation

#### WebAuthController - Hanya memanggil API

```php
<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;
use Illuminate\Http\Request;

class WebAuthController extends Controller
{
    protected ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login - call API
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Call API to login
        $response = $this->apiClient->post('/api/auth/login', $validated);

        if ($response->successful()) {
            $data = $response->json();

            // Store token in session (for web access)
            session(['api_token' => $data['token']]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        // Call API to logout
        $this->apiClient->post('/api/auth/logout');

        // Clear session
        session()->forget('api_token');

        return redirect('/login');
    }
}
```

#### WebUserController - Hanya memanggil API

```php
<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;
use Illuminate\Http\Request;

class WebUserController extends Controller
{
    protected ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * List users - call API
     */
    public function index()
    {
        $response = $this->apiClient->get('/api/users');

        if ($response->successful()) {
            $users = $response->json()['data'];
            return view('users.index', compact('users'));
        }

        return back()->with('error', 'Failed to load users');
    }

    /**
     * Edit user form - call API
     */
    public function edit(int $id)
    {
        $response = $this->apiClient->get("/api/users/{$id}");

        if ($response->successful()) {
            $user = $response->json();
            return view('users.edit', compact('user'));
        }

        return back()->with('error', 'User not found');
    }

    /**
     * Update user - call API
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        $response = $this->apiClient->put("/api/users/{$id}", $validated);

        if ($response->successful()) {
            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully');
        }

        return back()->with('error', 'Failed to update user');
    }

    /**
     * Delete user - call API
     */
    public function destroy(int $id)
    {
        $response = $this->apiClient->delete("/api/users/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'User deleted successfully');
        }

        return back()->with('error', 'Failed to delete user');
    }
}
```

#### WebFlightController - Hanya memanggil API

```php
<?php

namespace App\Http\Controllers\Web\Flights;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;

class WebFlightController extends Controller
{
    protected ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * List flights - call API
     */
    public function index()
    {
        $response = $this->apiClient->get('/api/flights');

        if ($response->successful()) {
            $flights = $response->json()['data'];
            return view('flights.index', compact('flights'));
        }

        return back()->with('error', 'Failed to load flights');
    }

    /**
     * Scrape flights - call API
     */
    public function scrape()
    {
        $response = $this->apiClient->post('/api/flights/scrape', [
            'from' => 'CGK',
            'to' => 'DPS',
            'date' => now()->format('Y-m-d'),
            'class' => 'economy'
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Flights scraped successfully');
        }

        return back()->with('error', 'Failed to scrape flights');
    }
}
```

### 15.5 Config untuk API Client

```php
// config/app.php

return [
    'name' => env('APP_NAME', 'TableLink'),
    'env' => env('APP_ENV', 'local'),
    'api_url' => env('API_URL', 'http://localhost:8000'),
    // ...
];
```

### 15.6 Comparison: Old vs New Architecture

| Aspek              | Old (DB langsung) | New (API Client)     |
| ------------------ | ----------------- | -------------------- |
| **Web Controller** | Direct DB query   | HTTP call to API     |
| **Database**       | Accessed by Web   | Only API accesses DB |
| **Business Logic** | 分散 di Web & API | 集中 di API only     |
| **Security**       | Less secure       | More secure          |
| **Scalability**    | Limited           | High                 |

### 15.7 Implementation Checklist - Web as API Client

- [ ] Create ApiClientService
- [ ] Update WebAuthController to use ApiClientService
- [ ] Update WebUserController to use ApiClientService
- [ ] Update WebDashboardController to use ApiClientService
- [ ] Update WebFlightController to use ApiClientService
- [ ] Remove direct Model usage from Web Controllers
- [ ] Add web.token middleware
- [ ] Configure API URL in config/app.php
- [ ] Update .env with API_URL
- [ ] Update all Blade views to use API response data

---

## 16. Session Creation Flow (Login Process)

### 16.1 Overview

**Session user dibuat ketika API login berhasil.** Berikut flow lengkapnya:

```
mermaid
sequenceDiagram
    participant User
    participant Browser
    participant WebAuthController
    participant ApiClientService
    participant API
    participant Session

    User->>Browser: POST /login (email, password)
    Browser->>WebAuthController: HTTP Request
    WebAuthController->>ApiClientService: post('/api/auth/login', data)
    ApiClientService->>API: HTTP POST /api/auth/login
    API->>API: Validate credentials
    API->>API: Generate Sanctum Token
    API-->>ApiClientService: { token, user }
    ApiClientService-->>WebAuthController: HTTP Response
    WebAuthController->>Session: session(['api_token' => token])
    WebAuthController->>Session: Auth::login(user)
    WebAuthController-->>Browser: Redirect /dashboard
    Browser->>Session: Cookie: session_id
```

### 16.2 Login Flow Steps

| Step | Action                             | Description                          |
| ---- | ---------------------------------- | ------------------------------------ |
| 1    | User submits login form            | POST /login dengan email & password  |
| 2    | WebAuthController receives request | Validasi input form                  |
| 3    | Call API via ApiClientService      | HTTP POST ke /api/auth/login         |
| 4    | API validates credentials          | Check email & password               |
| 5    | API generates token                | Create Sanctum personal access token |
| 6    | API returns JSON                   | { token, user, message }             |
| 7    | Web saves token to session         | session(['api_token' => $token])     |
| 8    | Web creates user session           | Auth::login($user)                   |
| 9    | Redirect to dashboard              | User logged in via session           |

### 16.3 WebAuthController Implementation

```php
<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    protected ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Handle login - call API then create session
     */
    public function login(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Step 1-6: Call API untuk login
        $response = $this->apiClient->post('/api/auth/login', $validated);

        if ($response->successful()) {
            $data = $response->json();

            // Step 7: Simpan token ke session
            session(['api_token' => $data['token']]);

            // Step 8: Buat user session (Laravel Auth)
            // Buat User object dari response API
            $user = new \App\Models\User($data['user']);
            Auth::login($user);

            // Step 9: Redirect ke dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    /**
     * Handle logout - clear session and call API
     */
    public function logout()
    {
        // Call API untuk revoke token
        $this->apiClient->post('/api/auth/logout');

        // Clear session
        session()->forget('api_token');
        Auth::logout();

        return redirect('/login');
    }
}
```

### 16.4 Session Data Structure

Setelah login berhasil, session menyimpan:

| Session Key        | Value                | Fungsi                     |
| ------------------ | -------------------- | -------------------------- |
| `api_token`        | Sanctum token string | Untuk call API ke backend  |
| `login_web_{sha1}` | User ID              | Laravel session identifier |
| `_token`           | CSRF token           | CSRF protection            |
| `password_hash`    | Hash                 | Remember me functionality  |

### 16.5 Request Selanjutnya (Authenticated)

```
Browser Request
    │
    ├─► Include Cookie: session_id
    │
    ▼
Middleware 'auth'
    │
    ├─► Cek session ada user?
    │
    ├─► Yes: $request->user() available
    │      Route processing continues
    │
    └─► No: Redirect ke /login
```

### 16.6 Middleware Check Flow

```php
// Dalam middleware auth (Laravel default)

public function handle($request, Closure $next)
{
    if (!$request->session()->has('login_web_' . sha1('App\Models\User'))) {
        // Tidak ada session user
        return redirect('/login');
    }

    return $next($request);
}
```

### 16.7 API Login Response

```json
// POST /api/auth/login
// Response:

{
  "success": true,
  "message": "Login successful",
  "token": "1|ABCdEfGhIjKlMnOpQrStUvWxYz1234567890",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin",
    "last_login": "2024-01-15T10:30:00Z"
  }
}
```

### 16.8 Kesimpulan

| Proses         | Yang Terjadi                                                                   |
| -------------- | ------------------------------------------------------------------------------ |
| **Login**      | API returns token → Web stores in session → Auth::login() creates user session |
| **API Calls**  | ApiClientService reads token from session, adds to request header              |
| **Middleware** | Laravel 'auth' middleware checks session for user, not API token               |
| **Logout**     | Clear session + Call API to revoke token                                       |

---

## 17. View Architecture - Server-Side Rendering

### 17.1 Overview

**Server-Side Rendering (SSR)** adalah pendekatan yang digunakan:

| Aspek            | Konfigurasi                     |
| ---------------- | ------------------------------- |
| **Build Tool**   | Vite (Laravel 11/12 default)    |
| **JS Framework** | Vanilla JS atau Alpine.js       |
| **CSS**          | Tailwind CSS                    |
| **API Calls**    | Di WebController, bukan di View |

### 17.2 Arsitektur Server-Side Rendering

```
Browser Request
    │
    ▼
WebController
    │
    ├─► ApiClientService.call('/api/...')
    │
    ▼
API Controller → Database
    │
    ▼
Return JSON
    │
    ▼
WebController
    │
    ▼
Render Blade View (HTML)
    │
    ▼
Browser receives complete HTML
```

### 17.3 View Structure

```
resources/
├── views/
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── logout.blade.php
│   ├── dashboard/
│   │   ├── admin.blade.php      # Charts, user management link
│   │   └── user.blade.php       # Basic dashboard
│   ├── users/
│   │   ├── index.blade.php      # User list table
│   │   ├── create.blade.php     # Create form
│   │   ├── edit.blade.php       # Edit form
│   │   └── _form.blade.php      # Shared form partial
│   ├── flights/
│   │   ├── index.blade.php      # Flight data-table
│   │   ├── create.blade.php     # Create form
│   │   └── _table.blade.php     # Flight table partial
│   ├── layouts/
│   │   ├── app.blade.php        # Main layout (auth)
│   │   ├── guest.blade.php      # Guest layout
│   │   └── partials/
│   │       ├── _header.blade.php
│   │       ├── _sidebar.blade.php
│   │       └── _footer.blade.php
│   └── components/              # Blade components
│       ├── button.blade.php
│       ├── input.blade.php
│       └── table.blade.php
└── js/
    ├── app.js                   # Main entry
    ├── bootstrap.js             # Laravel bootstrap
    └── components/             # Optional JS components
```

### 17.4 WebController - Data Preparation

```php
<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;

class WebUserController extends Controller
{
    protected ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * GET /admin/users
     * Data diambil dari API, view hanya render
     */
    public function index()
    {
        // Panggil API untuk get users
        $response = $this->apiClient->get('/api/users');

        if ($response->successful()) {
            // Extract data dari response
            $users = $response->json()['data'];
            $pagination = $response->json()['meta'];

            // Render view dengan data
            return view('users.index', [
                'users' => $users,
                'pagination' => $pagination
            ]);
        }

        return back()->with('error', 'Gagal memuat data users');
    }

    /**
     * GET /admin/users/{id}/edit
     */
    public function edit(int $id)
    {
        $response = $this->apiClient->get("/api/users/{$id}");

        if ($response->successful()) {
            $user = $response->json();
            return view('users.edit', compact('user'));
        }

        return back()->with('error', 'User tidak ditemukan');
    }
}
```

### 17.5 Blade View - Display Data

```blade
{{-- resources/views/users/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kelola Users</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last Login</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>
                    <span class="badge bg-{{ $user['role'] === 'admin' ? 'danger' : 'primary' }}">
                        {{ $user['role'] }}
                    </span>
                </td>
                <td>{{ $user['last_login'] ? \Carbon\Carbon::parse($user['last_login'])->format('d-m-Y H:i') : '-' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($pagination)
    <nav>
        <ul class="pagination">
            @for($i = 1; $i <= $pagination['last_page']; $i++)
            <li class="page-item {{ $i === $pagination['current_page'] ? 'active' : '' }}">
                <a class="page-link" href="{{ route('admin.users.index', ['page' => $i]) }}">{{ $i }}</a>
            </li>
            @endfor
        </ul>
    </nav>
    @endif
</div>
@endsection
```

### 17.6 Flight Data-Table View

```blade
{{-- resources/views/flights/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h1>Informasi Penerbangan</h1>
        <div>
            <button onclick="scrapeFlights()" class="btn btn-primary">
                Scrape dari Tiket.com
            </button>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Maskapai</th>
                <th>No. Penerbangan</th>
                <th>Jam Berangkat</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Kelas</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($flights as $flight)
            <tr>
                <td>{{ $flight['airline_name'] }}</td>
                <td>{{ $flight['flight_number'] }}</td>
                <td>{{ \Carbon\Carbon::parse($flight['departure_time'])->format('H:i') }}</td>
                <td>{{ $flight['departure_airport'] }}</td>
                <td>{{ $flight['arrival_airport'] }}</td>
                <td>{{ ucfirst($flight['class_type']) }}</td>
                <td>Rp {{ number_format($flight['price'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data penerbangan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function scrapeFlights() {
    if(confirm('Ambil data terbaru dari Tiket.com?')) {
        // Submit form ke route yang akan call API
        document.getElementById('scrape-form').submit();
    }
}
</script>
<form id="scrape-form" action="{{ route('admin.flights.scrape') }}" method="POST" style="display: none;">
    @csrf
</form>
@endpush
@endsection
```

### 17.7 Vite Configuration

```javascript
// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/js/app.js"],
      refresh: true,
    }),
  ],
  server: {
    host: "0.0.0.0",
    port: 5173,
  },
});
```

```javascript
// resources/js/app.js
import "./bootstrap";

// Optional: Add Alpine.js for interactivity
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();
```

### 17.8 Kesimpulan View Architecture

| Aspek              | Penjelasan                                            |
| ------------------ | ----------------------------------------------------- |
| **API Calls**      | Di WebController, via ApiClientService                |
| **Views**          | Hanya menerima data dari controller dan merender HTML |
| **No direct API**  | Views tidak melakukan fetch/axios langsung            |
| **Interaktivitas** | Minimal JS (Alpine.js opsional)                       |
| **SEO**            | Optimal karena HTML di-generate di server             |
| **Security**       | Token tidak expose di client side                     |

### 17.9 Implementation Checklist - Views

- [ ] Create layouts (app.blade.php, guest.blade.php)
- [ ] Create auth views (login, register)
- [ ] Create dashboard views (admin, user)
- [ ] Create user management views (index, edit)
- [ ] Create flight views (index, table)
- [ ] Configure Vite
- [ ] Add Tailwind CSS
- [ ] Test SSR flow (controller → API → view)

---

## 18. UI/UX Plan - Styling & Design

### 18.1 Technology Stack

| Technology   | Purpose                     | Version |
| ------------ | --------------------------- | ------- |
| Tailwind CSS | Utility-first CSS framework | v3.x    |
| Alpine.js    | Lightweight JavaScript      | v3.x    |
| Laravel Vite | Build tool & dev server     | Latest  |
| Heroicons    | SVG Icons                   | v2.x    |

### 18.2 Design System

#### Color Palette

```css
/* Primary Colors */
--color-primary: #2563eb; /* Blue 600 - Main brand color */
--color-primary-dark: #1d4ed8; /* Blue 700 - Hover state */

/* Status Colors */
--color-success: #10b981; /* Emerald 500 */
--color-warning: #f59e0b; /* Amber 500 */
--color-danger: #ef4444; /* Red 500 */

/* Neutral Colors */
--color-white: #ffffff;
--color-gray-50: #f8fafc; /* Background */
--color-gray-100: #f1f5f9; /* Card background */
--color-gray-200: #e2e8f0; /* Border */
--color-gray-700: #334155; /* Heading */
--color-gray-900: #0f172a; /* Primary text */
```

#### Typography

```css
--font-sans: "Inter", system-ui, -apple-system, sans-serif;
--text-sm: 0.875rem; /* 14px */
--text-base: 1rem; /* 16px */
--text-lg: 1.125rem; /* 18px */
--text-xl: 1.25rem; /* 20px */
--text-2xl: 1.5rem; /* 24px */
```

### 18.3 Component Library

#### Button Components

```blade
{{-- Primary Button --}}
<button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
    {{ $slot }}
</button>

{{-- Secondary Button --}}
<button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
    {{ $slot }}
</button>

{{-- Danger Button --}}
<button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
    {{ $slot }}
</button>
```

#### Form Components

```blade
{{-- Text Input --}}
<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
    <input type="text" id="name" name="name"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
</div>

{{-- Select --}}
<select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
    <option>Select option</option>
</select>
```

#### Card Component

```blade
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900">Card Title</h3>
    <p class="text-gray-600">Card content goes here...</p>
</div>
```

#### Table Component

```blade
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Column</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm text-gray-900">Data</td>
        </tr>
    </tbody>
</table>
```

### 18.4 Page Layouts

#### Auth Layout (Login/Register)

```
┌─────────────────────────────────────────┐
│           Logo + Brand Name              │
├─────────────────────────────────────────┤
│         ┌───────────────────┐           │
│         │   Login Form      │           │
│         │   - Email        │           │
│         │   - Password     │           │
│         │   [Login Button] │           │
│         └───────────────────┘           │
│         [Don't have account? Register]  │
└─────────────────────────────────────────┘
```

#### Admin Layout (with Sidebar)

```
┌──────────────────────────────────────────────────┐
│  Header: Logo | Search | User Menu | Logout       │
├──────────┬─────────────────────────────────────┤
│ Sidebar │         Main Content                 │
│ - Dash  │  ┌─────────────────────────────┐     │
│ - Users │  │  Page Title + Actions      │     │
│ - Flights│ ├─────────────────────────────┤     │
│          │  │      Content Area           │     │
│          │  │      (Table/Form/Chart)    │     │
│          │  └─────────────────────────────┘     │
└──────────┴─────────────────────────────────────┘
```

### 18.5 Responsive Breakpoints

| Breakpoint | Min Width | Description   |
| ---------- | --------- | ------------- |
| sm         | 640px     | Small phones  |
| md         | 768px     | Tablets       |
| lg         | 1024px    | Small laptops |
| xl         | 1280px    | Desktops      |

```blade
{{-- Mobile: Stack vertically | Desktop: Side by side --}}
<div class="flex flex-col md:flex-row gap-4">
    <div class="flex-1">Content 1</div>
    <div class="flex-1">Content 2</div>
</div>
```

### 18.6 Tailwind Configuration

```javascript
// tailwind.config.js
module.exports = {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./resources/views/**/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          500: "#3B82F6",
          600: "#2563EB",
          700: "#1D4ED8",
        },
      },
      fontFamily: {
        sans: ["Inter", "system-ui", "sans-serif"],
      },
    },
  },
  plugins: [require("@tailwindcss/forms"), require("@tailwindcss/typography")],
};
```

### 18.7 Required NPM Packages

```bash
# Install dependencies
npm install

# Additional packages
npm install -D @tailwindcss/forms @tailwindcss/typography
npm install alpinejs
```

### 18.8 Icon Usage (Heroicons)

```blade
{{-- Using Heroicons SVG --}}
<x-heroicon-o-plus class="w-5 h-5" />
<x-heroicon-o-user class="w-5 h-5" />
<x-heroicon-o-logout class="w-5 h-5" />
```

### 18.9 Animation & Transitions

```css
/* Button Hover Transition */
.transition-default {
  transition: all 0.2s ease-in-out;
}

/* Fade In */
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}
```

### 18.10 Implementation Checklist - UI/UX

- [ ] Setup Tailwind CSS
- [ ] Configure tailwind.config.js
- [ ] Install additional plugins (forms, typography)
- [ ] Create color palette & design tokens
- [ ] Build reusable Blade components
- [ ] Create auth layout (login, register)
- [ ] Create admin layout (with sidebar)
- [ ] Create user dashboard layout
- [ ] Style login/register pages
- [ ] Style admin dashboard
- [ ] Style user dashboard
- [ ] Style user management pages
- [ ] Style flight information page
- [ ] Add responsive design
- [ ] Add loading states
- [ ] Add empty states

---

## 19. DataTable Implementation - Alpine.js + TailwindCSS

### 19.1 Technology Choice

**NO jQuery, NO Bootstrap needed!**

| Technology  | Purpose                                  |
| ----------- | ---------------------------------------- |
| Alpine.js   | Interactivity (search, sort, pagination) |
| TailwindCSS | Styling                                  |

### 19.2 DataTable Features

| Feature    | Implementation                         |
| ---------- | -------------------------------------- |
| Search     | Real-time filtering                    |
| Sort       | Click column header to sort (ASC/DESC) |
| Pagination | Server-side pagination                 |
| Show Row   | Dropdown untuk jumlah row per page     |
| Responsive | Mobile-friendly table                  |

### 19.3 DataTable Component (Alpine.js)

```blade
{{-- resources/views/components/data-table.blade.php --}}

<div x-data="{
    data: [],
    search: '',
    sortBy: 'id',
    sortAsc: true,
    currentPage: 1,
    perPage: 10,
    total: 0,
    loading: false,

    async fetchData() {
        this.loading = true;
        const params = new URLSearchParams({
            page: this.currentPage,
            per_page: this.perPage,
            search: this.search,
            sort_by: this.sortBy,
            sort_order: this.sortAsc ? 'asc' : 'desc'
        });

        const response = await fetch(`/api/users?${params}`, {
            headers: {
                'Authorization': `Bearer {{ session('api_token') }}`,
                'Content-Type': 'application/json'
            }
        });
        const result = await response.json();
        this.data = result.data;
        this.total = result.meta.total;
        this.loading = false;
    },

    init() {
        this.fetchData();
        this.$watch('search', () => { this.currentPage = 1; this.fetchData(); });
        this.$watch('perPage', () => { this.currentPage = 1; this.fetchData(); });
    },

    get totalPages() {
        return Math.ceil(this.total / this.perPage);
    },

    get startItem() {
        return (this.currentPage - 1) * this.perPage + 1;
    },

    get endItem() {
        return Math.min(this.currentPage * this.perPage, this.total);
    },

    sort(field) {
        if (this.sortBy === field) {
            this.sortAsc = !this.sortAsc;
        } else {
            this.sortBy = field;
            this.sortAsc = true;
        }
        this.fetchData();
    }
}">
    {{-- Toolbar: Search & Show Row --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-4 justify-between">
        {{-- Search --}}
        <div class="relative">
            <input
                type="text"
                x-model="search"
                placeholder="Search..."
                class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        {{-- Show Row --}}
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Show</label>
            <select x-model="perPage" class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label class="text-sm text-gray-600">entries</label>
        </div>
    </div>

    {{-- Loading State --}}
    <div x-show="loading" class="flex justify-center py-8">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200" x-show="!loading">
            <thead class="bg-gray-50">
                <tr>
                    <th @click="sort('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Name
                            <span x-show="sortBy === 'name'" x-text="sortAsc ? '↑' : '↓'" class="text-blue-600"></span>
                        </div>
                    </th>
                    <th @click="sort('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Email
                            <span x-show="sortBy === 'email'" x-text="sortAsc ? '↑' : '↓'" class="text-blue-600"></span>
                        </div>
                    </th>
                    <th @click="sort('role')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Role
                            <span x-show="sortBy === 'role'" x-text="sortAsc ? '↑' : '↓'" class="text-blue-600"></span>
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="item in data" :key="item.id">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="item.name"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.email"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                :class="item.role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                                x-text="item.role">
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="$dispatch('view', item)" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                            <button @click="$dispatch('edit', item)" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</button>
                            <button @click="$dispatch('delete', item)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </template>
                <tr x-show="data.length === 0 && !loading">
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No data available</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row items-center justify-between mt-4 gap-4">
        <div class="text-sm text-gray-700">
            Showing <span x-text="startItem"></span> to <span x-text="endItem"></span> of <span x-text="total"></span> entries
        </div>
        <div class="flex gap-1">
            <button
                @click="currentPage--; fetchData()"
                :disabled="currentPage === 1"
                class="px-3 py-1 border rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
                Previous
            </button>
            <template x-for="i in Array.from({length: Math.min(5, totalPages)}, (_, i) => i + Math.max(1, currentPage - 2)).filter(i => i <= totalPages)" :key="i">
                <button
                    @click="currentPage = i; fetchData()"
                    :class="currentPage === i ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                    class="px-3 py-1 border rounded-lg text-sm"
                    x-text="i"
                ></button>
            </template>
            <button
                @click="currentPage++; fetchData()"
                :disabled="currentPage >= totalPages"
                class="px-3 py-1 border rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
                Next
            </button>
        </div>
    </div>
</div>
```

### 19.4 Modal Component (Alpine.js)

```blade
{{-- resources/views/components/modal.blade.php --}}

<div
    x-data="{ open: false, mode: 'view', item: null }"
    @view.window="mode = 'view'; item = $event.detail; open = true"
    @edit.window="mode = 'edit'; item = $event.detail; open = true"
    @delete.window="mode = 'delete'; item = $event.detail; open = true"
    @close-modal.window="open = false"
>
    {{-- Modal Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        @click="open = false"
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md" @click.stop>
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <span x-text="mode === 'view' ? 'Detail User' : mode === 'edit' ? 'Edit User' : 'Konfirmasi Hapus'"></span>
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body: View Mode --}}
                <div x-show="mode === 'view'" class="p-4">
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Name</dt>
                            <dd class="text-gray-900 font-medium" x-text="item?.name"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="text-gray-900" x-text="item?.email"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Role</dt>
                            <dd class="text-gray-900" x-text="item?.role"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Last Login</dt>
                            <dd class="text-gray-900" x-text="item?.last_login || '-'"></dd>
                        </div>
                    </dl>
                </div>

                {{-- Modal Body: Edit Mode --}}
                <form x-show="mode === 'edit'" @submit.prevent="submitEdit" class="p-4">
                    <input type="hidden" x-model="item.id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" x-model="item.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" x-model="item.email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select x-model="item.role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </form>

                {{-- Modal Body: Delete Mode --}}
                <div x-show="mode === 'delete'" class="p-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Delete User</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Are you sure you want to delete <span x-text="item?.name" class="font-semibold"></span>? This action cannot be undone.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-2 p-4 border-t bg-gray-50">
                    <button @click="open = false" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button x-show="mode === 'view'" @click="mode = 'edit'; item = {...item}" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Edit
                    </button>
                    <button x-show="mode === 'edit'" @click="submitEdit(); open = false" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Save Changes
                    </button>
                    <button x-show="mode === 'delete'" @click="submitDelete(); open = false" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 19.5 Usage in Page

```blade
{{-- resources/views/users/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Kelola Users</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <x-data-table
            :data-url="route('api.users.index')"
            :columns="['name', 'email', 'role']"
        />
    </div>

    <x-modal />
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userCrud', () => ({
        async submitEdit() {
            const response = await fetch(`/api/users/${this.item.id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer {{ session('api_token') }}`,
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.item)
            });
            if (response.ok) {
                location.reload();
            }
        },
        async submitDelete() {
            const response = await fetch(`/api/users/${this.item.id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer {{ session('api_token') }}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (response.ok) {
                location.reload();
            }
        }
    }));
});
</script>
@endpush
@endsection
```

### 19.6 Features Summary

| Feature            | Description                          |
| ------------------ | ------------------------------------ |
| **Search**         | Real-time filtering dengan debounce  |
| **Sort**           | Click column header, toggle ASC/DESC |
| **Pagination**     | Server-side dengan prev/next buttons |
| **Show Row**       | Dropdown: 10, 25, 50, 100            |
| **View Modal**     | Popup untuk lihat detail             |
| **Edit Modal**     | Popup untuk edit data                |
| **Delete Confirm** | Popup konfirmasi hapus               |

### 19.7 Implementation Checklist - DataTable

- [ ] Create DataTable component (Alpine.js)
- [ ] Create Modal component (Alpine.js)
- [ ] Add search functionality
- [ ] Add sort functionality
- [ ] Add pagination functionality
- [ ] Add show row dropdown
- [ ] Create View modal
- [ ] Create Edit modal with form
- [ ] Create Delete confirmation modal
- [ ] Connect to API endpoints
- [ ] Handle loading states
- [ ] Handle empty states
- [ ] Test CRUD operations

---

## 20. Admin Dashboard Charts - Implementation

### 20.1 Chart References

| Chart Type         | Source Reference                              |
| ------------------ | --------------------------------------------- |
| Line Chart         | rachmanlatif/323bd55b284774bf98e11225ce2374e1 |
| Vertical Bar Chart | rachmanlatif/51277a2070e6cd240bf471d9aead29d7 |
| Pie Chart          | rachmanlatif/ad0290b004c1bfa9ded5f872f680fea8 |

### 20.2 Technology Stack

| Technology  | Purpose                             |
| ----------- | ----------------------------------- |
| Chart.js    | Chart rendering library             |
| Alpine.js   | Chart initialization & data loading |
| TailwindCSS | Styling container                   |

### 20.3 NPM Installation

```bash
npm install chart.js
```

### 20.4 Chart Components Structure

```
resources/
├── views/
│   ├── components/
│   │   ├── charts/
│   │   │   ├── line-chart.blade.php
│   │   │   ├── bar-chart.blade.php
│   │   │   └── pie-chart.blade.php
│   └── dashboard/
│       └── admin.blade.php
└── js/
    └── charts/
        └── admin-charts.js
```

### 20.5 Line Chart Component

```blade
{{-- resources/views/components/charts/line-chart.blade.php --}}

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title ?? 'Line Chart' }}</h3>
    <div class="relative h-64">
        <canvas id="{{ $id ?? 'line-chart' }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id ?? 'line-chart' }}').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: '{{ $label ?? 'Data' }}',
                data: @json($data ?? []),
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
```

### 20.6 Bar Chart Component

```blade
{{-- resources/views/components/charts/bar-chart.blade.php --}}

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title ?? 'Bar Chart' }}</h3>
    <div class="relative h-64">
        <canvas id="{{ $id ?? 'bar-chart' }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id ?? 'bar-chart' }}').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: '{{ $label ?? 'Data' }}',
                data: @json($data ?? []),
                backgroundColor: [
                    '#2563EB', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'
                ],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
```

### 20.7 Pie Chart Component

```blade
{{-- resources/views/components/charts/pie-chart.blade.php --}}

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title ?? 'Pie Chart' }}</h3>
    <div class="relative h-64">
        <canvas id="{{ $id ?? 'pie-chart' }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id ?? 'pie-chart' }}').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: '{{ $label ?? 'Data' }}',
                data: @json($data ?? []),
                backgroundColor: [
                    '#2563EB', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right'
                }
            }
        }
    });
});
</script>
@endpush
```

### 20.8 API Endpoint for Chart Data

```php
// routes/api.php

Route::middleware(['auth:sanctum', 'api.role:admin'])->group(function () {
    Route::get('/dashboard/charts', [ApiDashboardController::class, 'charts']);
});
```

```php
// app/Http/Controllers/Api/Dashboard/ApiDashboardController.php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class ApiDashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get chart data for dashboard
     */
    public function charts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'line_chart' => $this->dashboardService->getLineChartData(),
                'bar_chart' => $this->dashboardService->getBarChartData(),
                'pie_chart' => $this->dashboardService->getPieChartData()
            ]
        ]);
    }
}
```

### 20.9 DashboardService

```php
// app/Services/DashboardService.php

namespace App\Services;

class DashboardService
{
    /**
     * Get line chart data
     */
    public function getLineChartData(): array
    {
        // Example: User registrations per month
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [65, 59, 80, 81, 56, 55]
        ];
    }

    /**
     * Get bar chart data
     */
    public function getBarChartData(): array
    {
        // Example: Flight bookings per airline
        return [
            'labels' => ['Garuda', 'Citilink', 'Lion Air', 'Batik Air', 'Sriwijaya'],
            'data' => [120, 85, 65, 45, 30]
        ];
    }

    /**
     * Get pie chart data
     */
    public function getPieChartData(): array
    {
        // Example: User distribution by role
        return [
            'labels' => ['Admin', 'User', 'Guest'],
            'data' => [5, 150, 45]
        ];
    }
}
```

### 20.10 Admin Dashboard Page

```blade
{{-- resources/views/dashboard/admin.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Total Flights</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_flights'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Active Users</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['active_users'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-500">Today's Bookings</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['today_bookings'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Line Chart --}}
        <x-charts.line-chart
            id="line-chart"
            title="User Registrations"
            :labels="$lineChart['labels']"
            :data="$lineChart['data']"
            label="New Users"
        />

        {{-- Bar Chart --}}
        <x-charts.bar-chart
            id="bar-chart"
            title="Flight Bookings by Airline"
            :labels="$barChart['labels']"
            :data="$barChart['data']"
            label="Bookings"
        />
    </div>

    {{-- Pie Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-charts.pie-chart
            id="pie-chart"
            title="User Distribution"
            :labels="$pieChart['labels']"
            :data="$pieChart['data']"
            label="Users"
        />
    </div>
</div>
@endsection
```

### 20.11 WebDashboardController

```php
// app/Http/Controllers/Web/Dashboard/WebDashboardController.php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;
use App\Services\DashboardService;
use Illuminate\View\View;

class WebDashboardController extends Controller
{
    protected ApiClientService $apiClient;
    protected DashboardService $dashboardService;

    public function __construct(ApiClientService $apiClient, DashboardService $dashboardService)
    {
        $this->apiClient = $apiClient;
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show admin dashboard
     */
    public function index(): View
    {
        // Get chart data from API
        $chartsResponse = $this->apiClient->get('/api/dashboard/charts');
        $charts = $chartsResponse->successful() ? $chartsResponse->json()['data'] : [];

        // Get stats
        $statsResponse = $this->apiClient->get('/api/dashboard/stats');
        $stats = $statsResponse->successful() ? $statsResponse->json()['data'] : [];

        return view('dashboard.admin', [
            'lineChart' => $charts['line_chart'] ?? [],
            'barChart' => $charts['bar_chart'] ?? [],
            'pieChart' => $charts['pie_chart'] ?? [],
            'stats' => $stats
        ]);
    }
}
```

### 20.12 Implementation Checklist - Charts

- [ ] Install Chart.js
- [ ] Create line-chart component
- [ ] Create bar-chart component
- [ ] Create pie-chart component
- [ ] Create DashboardService
- [ ] Create API endpoint for chart data
- [ ] Create WebDashboardController
- [ ] Create admin dashboard view
- [ ] Add chart components to dashboard
- [ ] Style chart containers
- [ ] Test chart rendering
- [ ] Connect to API data

---

## 21. Flight Scraping Service - Tiket.com

> **NOTE: Skip for now, prepare skeleton only**
>
> Flight scraping ke Tiket.com akan di-skip untuk saat ini karena
> legal/technical issues. View akan menggunakan mockup data.

### 21.1 Overview

Flight scraping service untuk mengambil data penerbangan dari Tiket.com:

| Criteria  | Value                      |
| --------- | -------------------------- |
| Route     | Jakarta (CGK) → Bali (DPS) |
| Type      | One-way                    |
| Class     | Economy                    |
| Departure | Before 17:00 (5 PM)        |

**Status**: ⏸️ SKIPPED - Use mockup data instead

### 21.2 FlightService Implementation

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FlightService
{
    /**
     * Scrape flights from Tiket.com
     */
    public function scrapeFlights(array $params = []): array
    {
        $defaultParams = [
            'from' => 'CGK',
            'to' => 'DPS',
            'date' => now()->addDays(7)->format('Y-m-d'),
            'class' => 'economy',
            'adult' => 1,
            'child' => 0,
            'infant' => 0
        ];

        $params = array_merge($defaultParams, $params);

        try {
            // Simulate scraping (replace with actual Tiket.com scraping)
            // Note: scraping harus sesuai Terms of Service Tiket.com
            $flights = $this->simulateScrape($params);

            // Save to database
            $savedFlights = [];
            foreach ($flights as $flight) {
                $savedFlights[] = $this->saveFlight($flight);
            }

            return [
                'success' => true,
                'message' => count($savedFlights) . ' flights scraped successfully',
                'data' => $savedFlights
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to scrape flights: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Simulate flight data (replace with actual scraping)
     */
    protected function simulateScrape(array $params): array
    {
        // Sample data - replace with actual Tiket.com scraping
        return [
            [
                'airline_name' => 'Garuda Indonesia',
                'flight_number' => 'GA 401',
                'departure_time' => '06:00:00',
                'price' => 1250000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Citilink',
                'flight_number' => 'QG 501',
                'departure_time' => '08:30:00',
                'price' => 850000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Lion Air',
                'flight_number' => 'JT 701',
                'departure_time' => '14:00:00',
                'price' => 750000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
        ];
    }

    /**
     * Save flight to database
     */
    protected function saveFlight(array $flightData): \App\Models\Flight
    {
        // Check if flight already exists
        $flight = \App\Models\Flight::where('flight_number', $flightData['flight_number'])
            ->where('departure_date', $flightData['departure_date'] ?? now()->toDateString())
            ->first();

        if ($flight) {
            // Update existing
            $flight->update($flightData);
        } else {
            // Create new
            $flight = \App\Models\Flight::create($flightData);
        }

        return $flight;
    }

    /**
     * Get all flights with filters
     */
    public function getFlights(array $filters = []): array
    {
        $query = \App\Models\Flight::query();

        if (!empty($filters['departure_airport'])) {
            $query->where('departure_airport', $filters['departure_airport']);
        }

        if (!empty($filters['arrival_airport'])) {
            $query->where('arrival_airport', $filters['arrival_airport']);
        }

        if (!empty($filters['class_type'])) {
            $query->where('class_type', $filters['class_type']);
        }

        // Filter departure before 17:00
        $query->whereRaw('TIME(departure_time) < "17:00:00"');

        $flights = $query->orderBy('price', 'asc')->get();

        return [
            'success' => true,
            'data' => $flights
        ];
    }
}
```

### 21.3 ApiFlightController

```php
<?php

namespace App\Http\Controllers\Api\Flights;

use App\Http\Controllers\Controller;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiFlightController extends Controller
{
    protected FlightService $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Get all flights
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['departure_airport', 'arrival_airport', 'class_type']);
        $result = $this->flightService->getFlights($filters);

        return response()->json($result);
    }

    /**
     * Scrape flights from Tiket.com
     */
    public function scrape(Request $request): JsonResponse
    {
        $params = $request->only(['from', 'to', 'date', 'class']);
        $result = $this->flightService->scrapeFlights($params);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Get single flight
     */
    public function show(int $id): JsonResponse
    {
        $flight = \App\Models\Flight::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $flight
        ]);
    }

    /**
     * Update flight
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $flight = \App\Models\Flight::findOrFail($id);

        $validated = $request->validate([
            'airline_name' => 'sometimes|string|max:255',
            'flight_number' => 'sometimes|string|max:50',
            'departure_time' => 'sometimes',
            'price' => 'sometimes|numeric|min:0',
            'departure_airport' => 'sometimes|string|size:3',
            'arrival_airport' => 'sometimes|string|size:3',
            'flight_type' => 'sometimes|string|in:one-way,round-trip',
            'class_type' => 'sometimes|string|in:economy,business,first'
        ]);

        $flight->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Flight updated successfully',
            'data' => $flight
        ]);
    }

    /**
     * Delete flight
     */
    public function destroy(int $id): JsonResponse
    {
        $flight = \App\Models\Flight::findOrFail($id);
        $flight->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flight deleted successfully'
        ]);
    }
}
```

### 21.4 API Routes

```php
// routes/api.php

Route::middleware(['auth:sanctum', 'api.role:admin'])->group(function () {
    // Flight routes
    Route::prefix('flights')->group(function () {
        Route::get('/', [ApiFlightController::class, 'index']);
        Route::post('/scrape', [ApiFlightController::class, 'scrape']);
        Route::get('/{id}', [ApiFlightController::class, 'show']);
        Route::put('/{id}', [ApiFlightController::class, 'update']);
        Route::delete('/{id}', [ApiFlightController::class, 'destroy']);
    });

    // Dashboard stats
    Route::get('/dashboard/stats', [ApiDashboardController::class, 'stats']);
});
```

### 21.5 Dashboard Stats API

```php
// app/Http/Controllers/Api/Dashboard/ApiDashboardController.php

public function stats(): JsonResponse
{
    $stats = [
        'total_users' => User::count(),
        'total_flights' => Flight::count(),
        'active_users' => User::whereNull('deleted_at')->count(),
        'today_bookings' => Flight::whereDate('created_at', today())->count()
    ];

    return response()->json([
        'success' => true,
        'data' => $stats
    ]);
}
```

### 21.6 Notes

- **Terms of Service**: Scraping harus sesuai dengan Terms of Service Tiket.com
- **Rate Limiting**: Batasi request untuk menghindari blocking
- **Caching**: Simpan hasil scrape untuk mengurangi request berulang
- **Alternative**: Pertimbangkan menggunakan API resmi Tiket.com jika tersedia

---

## 22. Database Seeder

### 22.1 Seeder Structure

```
database/
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   └── FlightSeeder.php
└── factories/
    ├── UserFactory.php
    └── FlightFactory.php
```

### 22.2 User Seeder (1 Admin + 20 Dummy Users)

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates: 1 admin + 20 dummy users
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@tablelink.com'],
            [
                'name' => 'Admin TableLink',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create 20 Dummy Users
        $users = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com'],
            ['name' => 'Citra Dewi', 'email' => 'citra@example.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@example.com'],
            ['name' => 'Eka Putri', 'email' => 'eka@example.com'],
            ['name' => 'Fajar Rahman', 'email' => 'fajar@example.com'],
            ['name' => 'Gita Lestari', 'email' => 'gita@example.com'],
            ['name' => 'Hadi Wijaya', 'email' => 'hadi@example.com'],
            ['name' => 'Indra Gunawan', 'email' => 'indra@example.com'],
            ['name' => 'Jasmine Ayu', 'email' => 'jasmine@example.com'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@example.com'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman@example.com'],
            ['name' => 'Mira Fatmawati', 'email' => 'mira@example.com'],
            ['name' => 'Nico Pratama', 'email' => 'nico@example.com'],
            ['name' => 'Olivia Natalia', 'email' => 'olivia@example.com'],
            ['name' => 'Putra Mahkota', 'email' => 'putra@example.com'],
            ['name' => 'Qori Amelia', 'email' => 'qori@example.com'],
            ['name' => 'Rina Susilowati', 'email' => 'rina@example.com'],
            ['name' => 'Sandi Pratama', 'email' => 'sandi@example.com'],
            ['name' => 'Tika Hartati', 'email' => 'tika@example.com'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'last_login' => now()->subDays(rand(0, 30)),
                ]
            );
        }

        $this->command->info('Seeded: 1 admin + 20 users');
    }
}
```

### 22.3 Flight Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flights = [
            [
                'airline_name' => 'Garuda Indonesia',
                'flight_number' => 'GA 401',
                'departure_time' => '06:00:00',
                'price' => 1250000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Citilink',
                'flight_number' => 'QG 501',
                'departure_time' => '08:30:00',
                'price' => 850000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Lion Air',
                'flight_number' => 'JT 701',
                'departure_time' => '10:15:00',
                'price' => 750000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Batik Air',
                'flight_number' => 'ID 601',
                'departure_time' => '12:45:00',
                'price' => 950000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
            [
                'airline_name' => 'Sriwijaya Air',
                'flight_number' => 'SJ 801',
                'departure_time' => '14:30:00',
                'price' => 680000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy'
            ],
        ];

        foreach ($flights as $flight) {
            Flight::updateOrCreate(
                ['flight_number' => $flight['flight_number']],
                $flight
            );
        }

        $this->command->info('Seeded: 5 flights');
    }
}
```

### 22.4 Database Seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FlightSeeder::class,
        ]);
    }
}
```

### 22.5 Run Seeder

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Migrate and seed
php artisan migrate:fresh --seed

# Seed with force (production)
php artisan db:seed --force
```

---

## 23. Deployment - Local Environment Only

### 23.1 Local Development Setup

```bash
# Clone repository
git clone <repository-url> tablelink-test
cd tablelink-test

# Start Docker
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Access application
# http://localhost:8000
```

### 23.2 Docker Commands

```bash
# Development
docker-compose up -d              # Start all services
docker-compose down              # Stop all services
docker-compose logs -f app       # View logs
docker-compose exec app bash     # Access container shell

# Rebuild
docker-compose build --no-cache  # Rebuild containers
```

### 23.3 Environment Variables

```env
APP_NAME=TableLink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tablelink
DB_USERNAME=root
DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

---

## 24. API Documentation

### 24.1 Postman Collection

```json
{
  "info": {
    "name": "TableLink API",
    "description": "API Documentation for TableLink Technical Test",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000/api"
    },
    {
      "key": "token",
      "value": ""
    }
  ],
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Register",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/auth/register",
            "body": {
              "mode": "json",
              "raw": "{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"password123\"}"
            }
          }
        },
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/auth/login",
            "body": {
              "mode": "json",
              "raw": "{\"email\":\"admin@tablelink.com\",\"password\":\"password123\"}"
            }
          }
        }
      ]
    },
    {
      "name": "Users (Admin)",
      "item": [
        {
          "name": "List Users",
          "request": {
            "method": "GET",
            "url": "{{base_url}}/users",
            "header": [{ "key": "Authorization", "value": "Bearer {{token}}" }]
          }
        }
      ]
    }
  ]
}
```

### 24.2 API Endpoints Summary

| Method | Endpoint              | Description            | Auth   |
| ------ | --------------------- | ---------------------- | ------ |
| POST   | /api/auth/register    | Register new user      | Public |
| POST   | /api/auth/login       | User login             | Public |
| POST   | /api/auth/logout      | User logout            | Token  |
| GET    | /api/auth/user        | Get current user       | Token  |
| GET    | /api/users            | List users (paginated) | Admin  |
| GET    | /api/users/{id}       | Get user detail        | Admin  |
| PUT    | /api/users/{id}       | Update user            | Admin  |
| DELETE | /api/users/{id}       | Delete user            | Admin  |
| GET    | /api/dashboard/charts | Get chart data         | Admin  |
| GET    | /api/dashboard/stats  | Get dashboard stats    | Admin  |
| GET    | /api/flights          | List flights           | Admin  |
| POST   | /api/flights/scrape   | Scrape Tiket.com       | Admin  |

---

## 25. Error Handling - Centralized

### 25.1 Exception Handler

```php
// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // API Error Response
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exception.
     */
    protected function handleApiException($request, Throwable $e): \Illuminate\Http\JsonResponse
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        // Default server error
        return response()->json([
            'success' => false,
            'message' => config('app.debug') ? $e->getMessage() : 'Server error'
        ], 500);
    }
}
```

### 25.2 Custom API Response

```php
// app/Http/Controllers/Api/ApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Success response.
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error response.
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Not found response.
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Unauthorized response.
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Forbidden response.
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}
```

---

## 26. Logging System

### 26.1 Log Configuration

```php
// config/logging.php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api.log'),
            'level' => 'info',
            'days' => 30,
        ],
    ],
];
```

### 26.2 API Logging Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration' => $duration . 'ms',
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
        ];

        \Log::channel('api')->info('API Request', $logData);

        return $response;
    }
}
```

### 26.3 Logging Usage

```php
// In controllers/services

\Log::info('User logged in', ['user_id' => $user->id]);
\Log::warning('Invalid login attempt', ['email' => $email]);
\Log::error('Flight scrape failed', ['error' => $e->getMessage()]);

// API specific
\Log::channel('api')->info('API Request processed', [
    'endpoint' => '/api/users',
    'user_id' => auth()->id()
]);
```

---

## 27. Chart Data - Mockup References

### 27.1 Chart Reference URLs

| Chart Type | Gist Reference                                |
| ---------- | --------------------------------------------- |
| Line Chart | rachmanlatif/323bd55b284774bf98e11225ce2374e1 |
| Bar Chart  | rachmanlatif/51277a2070e6cd240bf471d9aead29d7 |
| Pie Chart  | rachmanlatif/ad0290b004c1bfa9ded5f872f680fea8 |

### 27.2 Mockup Data Structure

```php
// Line Chart Data
$lineChart = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    'data' => [65, 59, 80, 81, 56, 55]
];

// Bar Chart Data
$barChart = [
    'labels' => ['Garuda', 'Citilink', 'Lion Air', 'Batik Air', 'Sriwijaya'],
    'data' => [120, 85, 65, 45, 30]
];

// Pie Chart Data
$pieChart = [
    'labels' => ['Admin', 'User', 'Guest'],
    'data' => [5, 150, 45]
];
```

---

## 28. Testing Plan

### 28.1 Testing Types

| Type          | Tool         | Coverage                   |
| ------------- | ------------ | -------------------------- |
| Unit Tests    | PHPUnit      | Business logic, services   |
| Feature Tests | PHPUnit      | API endpoints, controllers |
| Browser Tests | Laravel Dusk | UI interactions            |

### 28.2 Unit Tests

```bash
# Run all tests
php artisan test

# Run unit tests only
php artisan test --testsuite=Unit

# Run specific test
php artisan test --filter=UserTest
```

```php
// tests/Unit/AuthServiceTest.php

namespace Tests\Unit;

use App\Services\AuthService;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthServiceTest extends TestCase
{
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /** @test */
    public function it_can_login_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('12345678')
        ]);

        $result = $this->authService->loginApi($user->email, '12345678');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('token', $result);
    }

    /** @test */
    public function it_fails_login_with_invalid_credentials()
    {
        $result = $this->authService->loginApi('wrong@email.com', 'wrongpassword');

        $this->assertFalse($result['success']);
    }
}
```

### 28.3 Feature Tests

```php
// tests/Feature/ApiAuthTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiAuthTest extends TestCase
{
    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token']
            ]);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token']
            ]);
    }

    /** @test */
    public function admin_can_access_user_management()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/users');

        $response->assertStatus(200);
    }
}
```

### 28.4 Browser Tests (Dusk)

```bash
# Install Dusk
php artisan dusk:install

# Run Dusk tests
php artisan dusk

# Run specific test
php artisan dusk --filter=LoginTest
```

```php
// tests/Browser/LoginTest.php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends DuskTestCase
{
    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password123')
                ->press('Login')
                ->assertPathIs('/dashboard');
        });
    }

    /** @test */
    public function admin_can_access_user_management()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password123')
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users')
                ->assertSee('Kelola Users');
        });
    }
}
```

### 28.5 Testing Checklist

- [ ] Unit Tests: AuthService
- [ ] Unit Tests: UserService
- [ ] Unit Tests: FlightService
- [ ] Unit Tests: DashboardService
- [ ] Feature Tests: API Authentication
- [ ] Feature Tests: User CRUD
- [ ] Feature Tests: Flight CRUD
- [ ] Feature Tests: Dashboard
- [ ] Browser Tests: Login/Logout
- [ ] Browser Tests: User Management
- [ ] Browser Tests: Flight Management

---

_Document Version: 3.0_
_Updated: Flight Scraping (skipped), Docker (local only), Admin password, CORS removed_
_Created for: PT. TableLink Digital Inovasi Technical Test_
