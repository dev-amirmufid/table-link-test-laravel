# MVC Architecture Implementation

## 📁 Project Structure (MVC Best Practices)

### ✅ **Model Layer**
- `app/Models/User.php` - Eloquent model dengan business logic methods
  - `isAdmin()` - Check admin role
  - `isUser()` - Check user role
  - Soft deletes implementation
  - Proper casting untuk timestamps

### ✅ **View Layer**
- `resources/views/` - Blade templates dengan reusable components
  - `components/charts/` - Reusable chart components (line, bar, pie)
  - `dashboard/` - Admin & user dashboard views
  - `flights/` - Flight information data table
  - `auth/` - Login/register forms
  - `layouts/` - Master layouts

### ✅ **Controller Layer**
- `app/Http/Controllers/` - Thin controllers yang hanya handle HTTP requests
  - `AuthController.php` - Authentication endpoints
  - `UserController.php` - User management CRUD
  - `DashboardController.php` - Dashboard data endpoints
  - `FlightController.php` - Flight information endpoints
  - `WebController.php` - Web page rendering

### ✅ **Service Layer (Business Logic)**
- `app/Services/` - Business logic dipisah dari controllers
  - `AuthService.php` - Authentication & user management logic
  - `UserService.php` - User CRUD operations & validation
  - `DashboardService.php` - Chart data generation & analytics
  - `FlightService.php` - Flight data processing

### ✅ **Request Validation**
- `app/Http/Requests/` - Form Request validation
  - `LoginRequest.php` - Login validation rules
  - `RegisterRequest.php` - Registration validation rules
  - `UpdateUserRequest.php` - User update validation rules

### ✅ **Middleware**
- `app/Http/Middleware/` - Custom middleware
  - `RoleMiddleware.php` - Role-based authorization
  - `ApiAuthMiddleware.php` - API token authentication

## 🔄 **Data Flow (MVC Pattern)**

### 1. User Registration Example:
```
Request → RegisterRequest (Validation) → AuthController (HTTP) 
→ AuthService (Business Logic) → User Model (Data) → Database
```

### 2. Dashboard Data Example:
```
Request → RoleMiddleware (Auth) → DashboardController (HTTP)
→ DashboardService (Business Logic) → User Model (Data) → Database
→ JSON Response → View (Chart Components)
```

## 🎯 **MVC Best Practices Applied**

### ✅ **Separation of Concerns**
- **Models**: Hanya handle data access & relationships
- **Controllers**: Hanya handle HTTP requests/responses
- **Services**: Business logic & complex operations
- **Views**: Presentation logic & UI components

### ✅ **Dependency Injection**
- Services di-inject ke controllers via constructor
- Testable & loosely coupled components

### ✅ **Single Responsibility Principle**
- Setiap class memiliki satu tanggung jawab
- Methods focused & reusable

### ✅ **Request Validation Separation**
- Form Request classes untuk validation logic
- Clean controllers tanpa validation rules

### ✅ **Reusable View Components**
- Chart components dapat digunakan di multiple views
- Modular Blade templates

### ✅ **Clean API/UI Separation**
- API endpoints return JSON
- Web controllers return views
- Shared service layer untuk business logic

## 📊 **Architecture Benefits**

1. **Maintainability**: Business logic terpusat di services
2. **Testability**: Services dapat di-test independently
3. **Scalability**: Easy untuk menambah fitur baru
4. **Code Reusability**: Services dapat digunakan oleh multiple controllers
5. **Clean Code**: Controllers tetap thin dan focused

## 🔍 **Compliance with Instruction**

✅ **"Business logic kept inside services or controllers"** - Implemented with dedicated service layer  
✅ **"Request validation separated using Form Request"** - All validation in separate classes  
✅ **"Views split into reusable components"** - Chart components & modular templates  
✅ **"Clean separation between API and UI logic"** - Separate controllers with shared services
