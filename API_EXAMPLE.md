# Laravel API Example - Generic REST API

This is a complete example of building a REST API with Laravel 12. It demonstrates best practices for junior developers to learn how to create, test, and document APIs.

**Note:** This is a generic example using the existing `User` model as data source. Replace `items` with your actual resource name (posts, products, articles, etc.) and update the controller/model accordingly.

## Overview

This API example uses the existing `User` model (no new database required) and implements full CRUD operations with validation, error handling, and comprehensive tests. You can copy this pattern to any resource you want to create an API for.

## API Endpoints

### Health Check
```
GET /api/health
```
Returns API health status.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2026-02-05T10:30:00.000000Z"
}
```

### Get All Items
```
GET /api/items?per_page=15&page=1
```
List all items with pagination.

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)
- `page` (optional): Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Item Name",
      "email": "item@example.com",
      "created_at": "2026-02-05T10:00:00.000000Z",
      "updated_at": "2026-02-05T10:00:00.000000Z"
    }
  ],
  "pagination": {
    "total": 25,
    "per_page": 15,
    "current_page": 1,
    "last_page": 2
  }
}
```

### Create Item
```
POST /api/items
```
Create a new item.

**Request Body:**
```json
{
  "name": "Item Name",
  "email": "item@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Item created successfully",
  "data": {
    "id": 5,
    "name": "Item Name",
    "email": "item@example.com",
    "created_at": "2026-02-05T10:30:00.000000Z",
    "updated_at": "2026-02-05T10:30:00.000000Z"
  }
}
```

**Error Response (422 Validation Failed):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Get Single Item
```
GET /api/items/{id}
```
Get a specific item by ID.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Item Name",
    "email": "item@example.com",
    "created_at": "2026-02-05T10:00:00.000000Z",
    "updated_at": "2026-02-05T10:00:00.000000Z"
  }
}
```

### Update Item
```
PUT /api/items/{id}
```
Update an item's information.

**Request Body:**
```json
{
  "name": "Updated Item Name",
  "email": "updated@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Note:** All fields are optional. Only include fields you want to update.

**Response:**
```json
{
  "success": true,
  "message": "Item updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Item Name",
    "email": "updated@example.com",
    "created_at": "2026-02-05T10:00:00.000000Z",
    "updated_at": "2026-02-05T10:35:00.000000Z"
  }
}
```

### Delete Item
```
DELETE /api/items/{id}
```
Delete an item permanently.

**Response:**
```json
{
  "success": true,
  "message": "Item deleted successfully"
}
```

### Search Items
```
GET /api/items/search/{query}
```
Search items by name or email.

**Parameters:**
- `query` (required): Search term (minimum 2 characters)

**Response:**
```json
{
  "success": true,
  "query": "item",
  "results": [
    {
      "id": 1,
      "name": "Item Name",
      "email": "item@example.com",
      "created_at": "2026-02-05T10:00:00.000000Z",
      "updated_at": "2026-02-05T10:00:00.000000Z"
    }
  ],
  "count": 1
}
```

## Testing the API

### Using cURL

```bash
# Health check
curl http://localhost:8080/api/health

# Get all items
curl http://localhost:8080/api/items

# Create item
curl -X POST http://localhost:8080/api/items \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Item Name",
    "email": "item@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Get single item
curl http://localhost:8080/api/items/1

# Update item
curl -X PUT http://localhost:8080/api/items/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Item Name",
    "email": "updated@example.com"
  }'

# Delete item
curl -X DELETE http://localhost:8080/api/items/1

# Search items
curl "http://localhost:8080/api/items/search/item"
```

### Using Postman

1. Import the requests below into Postman
2. Replace `{id}` with actual item IDs
3. Set request headers: `Content-Type: application/json`

### Using Docker/Podman

```bash
# Create item via container
podman-compose exec app curl -X POST http://localhost:8080/api/items \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Item Name",
    "email": "item@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

## Running Tests

```bash
# Run all tests
podman-compose exec app php artisan test

# Run only API tests
podman-compose exec app php artisan test tests/Feature/ApiExampleTest.php

# Run with coverage
podman-compose exec app php artisan test --coverage

# Run specific test
podman-compose exec app php artisan test tests/Feature/ApiExampleTest.php --filter=test_create_item
```

Or using Make:
```bash
make test
make test path="tests/Feature/ApiExampleTest.php"
```

## File Structure

```
app/Http/Controllers/Api/
├── ApiExampleController.php    # Generic API controller example

routes/
├── api.php                      # API routes (items, health check)

tests/Feature/
├── ApiExampleTest.php           # API tests
```

## Key Concepts

### 1. Routes (routes/api.php)
```php
Route::apiResource('items', ApiExampleController::class);
Route::get('items/search/{query}', [ApiExampleController::class, 'search']);
```
- `apiResource` automatically creates REST routes (index, store, show, update, destroy)
- Custom routes for additional functionality like search

### 2. Controllers (app/Http/Controllers/Api/ApiExampleController.php)
- Separation of concerns - API logic is separate from web logic
- Request validation
- Consistent JSON responses
- Proper HTTP status codes

### 3. Validation
```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);
```
- Validates incoming data
- Returns 422 Unprocessable Entity on validation error
- Uses Laravel's built-in validation rules

### 4. JSON Responses
Always follow a consistent response format:
```json
{
  "success": true/false,
  "message": "...",
  "data": {...}
}
```

### 5. Error Handling
- 400 Bad Request - Invalid input
- 404 Not Found - Resource not found
- 422 Unprocessable Entity - Validation error
- 500 Server Error - Unexpected error

### 6. Testing
```php
public function test_create_item(): void
{
    $response = $this->postJson('/api/items', $itemData);
    
    $response->assertStatus(201)
        ->assertJson(['success' => true]);
}
```
- Use `getJson()`, `postJson()`, `putJson()`, `deleteJson()`
- Assert status codes and JSON structure
- Test both success and error cases

## How to Use This Example

This example is designed to be a template that you can copy and modify:

1. **Copy the controller:**
   ```bash
   cp app/Http/Controllers/Api/ApiExampleController.php app/Http/Controllers/Api/YourResourceController.php
   ```

2. **Update the route name:**
   ```php
   Route::apiResource('your-resources', YourResourceController::class);
   ```

3. **Update the model:**
   - Replace `User` model with your actual model (Post, Product, Article, etc.)

4. **Update validation rules:**
   - Modify the `validate()` calls to match your resource's fields

5. **Copy and update tests:**
   - Copy `tests/Feature/ApiExampleTest.php` 
   - Update test names and assertions

## Best Practices

1. **Always validate input** - Use request validation
2. **Return appropriate status codes** - 201 for create, 200 for success, 422 for validation errors
3. **Use resources** - Create `YourResource` class for consistent output formatting
4. **Document endpoints** - Include docblock comments
5. **Write tests** - Test all endpoints and edge cases
6. **Use proper naming** - Follow RESTful conventions
7. **Hash passwords** - Never store plain text passwords
8. **Use timestamps** - `created_at` and `updated_at` help track changes
9. **Pagination** - Return paginated results for large datasets
10. **Error messages** - Provide clear, helpful error messages

## Common Issues & Solutions

### Issue: 404 on API endpoints
**Solution:** Make sure `api.php` routes are registered in `bootstrap/app.php`

### Issue: CORS errors
**Solution:** Add CORS middleware or use a CORS package:
```bash
composer require fruitcake/laravel-cors
```

### Issue: 405 Method Not Allowed
**Solution:** Ensure request method matches the route (GET, POST, PUT, DELETE)

### Issue: 422 Validation errors
**Solution:** Check request body matches validation rules and use `password_confirmation`

## Next Steps for Learning

1. **Add authentication** - Implement API tokens or OAuth2
2. **Add authorization** - Use policies to check permissions
3. **Add resources** - Use Laravel Resource classes for formatting
4. **Add filtering** - Allow filtering by different fields
5. **Add sorting** - Allow sorting by different columns
6. **Add relationships** - Handle related data (comments on posts, etc.)
7. **Add caching** - Cache frequently accessed data
8. **Add rate limiting** - Prevent API abuse

## Related Files

- **Controller:** `app/Http/Controllers/Api/ApiExampleController.php`
- **Routes:** `routes/api.php`
- **Model:** `app/Models/User.php` (replace with your model)
- **Tests:** `tests/Feature/ApiExampleTest.php`
- **Bootstrap:** `bootstrap/app.php`


## API Endpoints

### Health Check
```
GET /api/health
```
Returns API health status.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2026-02-05T10:30:00.000000Z"
}
```

### Get All Users
```
GET /api/users?per_page=15&page=1
```
List all users with pagination.

**Query Parameters:**
- `per_page` (optional): Number of users per page (default: 15)
- `page` (optional): Page number (default: 1)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2026-02-05T10:00:00.000000Z",
      "updated_at": "2026-02-05T10:00:00.000000Z"
    }
  ],
  "pagination": {
    "total": 25,
    "per_page": 15,
    "current_page": 1,
    "last_page": 2
  }
}
```

### Create User
```
POST /api/users
```
Create a new user.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "User created successfully",
  "data": {
    "id": 5,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2026-02-05T10:30:00.000000Z",
    "updated_at": "2026-02-05T10:30:00.000000Z"
  }
}
```

**Error Response (422 Validation Failed):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Get Single User
```
GET /api/users/{id}
```
Get a specific user by ID.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2026-02-05T10:00:00.000000Z",
    "updated_at": "2026-02-05T10:00:00.000000Z"
  }
}
```

### Update User
```
PUT /api/users/{id}
```
Update a user's information.

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Note:** All fields are optional. Only include fields you want to update.

**Response:**
```json
{
  "success": true,
  "message": "User updated successfully",
  "data": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "created_at": "2026-02-05T10:00:00.000000Z",
    "updated_at": "2026-02-05T10:35:00.000000Z"
  }
}
```

### Delete User
```
DELETE /api/users/{id}
```
Delete a user permanently.

**Response:**
```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

### Search Users
```
GET /api/users/search/{query}
```
Search users by name or email.

**Parameters:**
- `query` (required): Search term (minimum 2 characters)

**Response:**
```json
{
  "success": true,
  "query": "john",
  "results": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2026-02-05T10:00:00.000000Z",
      "updated_at": "2026-02-05T10:00:00.000000Z"
    },
    {
      "id": 3,
      "name": "Bob Johnson",
      "email": "bob@example.com",
      "created_at": "2026-02-05T10:15:00.000000Z",
      "updated_at": "2026-02-05T10:15:00.000000Z"
    }
  ],
  "count": 2
}
```

## Testing the API

### Using cURL

```bash
# Health check
curl http://localhost:8080/api/health

# Get all users
curl http://localhost:8080/api/users

# Create user
curl -X POST http://localhost:8080/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Get single user
curl http://localhost:8080/api/users/1

# Update user
curl -X PUT http://localhost:8080/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com"
  }'

# Delete user
curl -X DELETE http://localhost:8080/api/users/1

# Search users
curl "http://localhost:8080/api/users/search/john"
```

### Using Postman

1. Import the requests below into Postman
2. Replace `{id}` with actual user IDs
3. Set request headers: `Content-Type: application/json`

### Using Docker/Podman

```bash
# Create user via container
podman-compose exec app curl -X POST http://localhost:8080/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

## Running Tests

```bash
# Run all tests
podman-compose exec app php artisan test

# Run only API tests
podman-compose exec app php artisan test tests/Feature/UserApiTest.php

# Run with coverage
podman-compose exec app php artisan test --coverage

# Run specific test
podman-compose exec app php artisan test tests/Feature/UserApiTest.php --filter=test_create_user
```

Or using Make:
```bash
make test
make test path="tests/Feature/UserApiTest.php"
```

## Key Concepts

### 1. Routes (routes/api.php)
```php
Route::apiResource('users', UserController::class);
Route::get('users/search/{query}', [UserController::class, 'search']);
```
- `apiResource` automatically creates REST routes (index, store, show, update, destroy)
- Custom routes for additional functionality

### 2. Controllers (app/Http/Controllers/Api/UserController.php)
- Separation of concerns - API logic is separate from web logic
- Request validation
- Consistent JSON responses
- Proper HTTP status codes

### 3. Validation
```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);
```
- Validates incoming data
- Returns 422 Unprocessable Entity on validation error
- Uses Laravel's built-in validation rules

### 4. JSON Responses
Always follow a consistent response format:
```json
{
  "success": true/false,
  "message": "...",
  "data": {...}
}
```

### 5. Error Handling
- 400 Bad Request - Invalid input
- 404 Not Found - Resource not found
- 422 Unprocessable Entity - Validation error
- 500 Server Error - Unexpected error

### 6. Testing
```php
public function test_create_user(): void
{
    $response = $this->postJson('/api/users', $userData);
    
    $response->assertStatus(201)
        ->assertJson(['success' => true]);
}
```
- Use `getJson()`, `postJson()`, `putJson()`, `deleteJson()`
- Assert status codes and JSON structure
- Test both success and error cases

## Best Practices

1. **Always validate input** - Use request validation
2. **Return appropriate status codes** - 201 for create, 200 for success, 422 for validation errors
3. **Use resources** - Create `UserResource` for consistent output formatting
4. **Document endpoints** - Include docblock comments
5. **Write tests** - Test all endpoints and edge cases
6. **Use proper naming** - Follow RESTful conventions
7. **Hash passwords** - Never store plain text passwords
8. **Use timestamps** - `created_at` and `updated_at` help track changes
9. **Pagination** - Return paginated results for large datasets
10. **Error messages** - Provide clear, helpful error messages

## Common Issues & Solutions

### Issue: 404 on API endpoints
**Solution:** Make sure `api.php` routes are registered in `bootstrap/app.php`

### Issue: CORS errors
**Solution:** Add CORS middleware or use a CORS package:
```bash
composer require fruitcake/laravel-cors
```

### Issue: 405 Method Not Allowed
**Solution:** Ensure request method matches the route (GET, POST, PUT, DELETE)

### Issue: 422 Validation errors
**Solution:** Check request body matches validation rules and use `password_confirmation`

## Next Steps for Learning

1. **Add authentication** - Implement API tokens or OAuth2
2. **Add authorization** - Use policies to check user permissions
3. **Add resources** - Use `UserResource` for consistent formatting
4. **Add pagination** - Return paginated results
5. **Add filtering** - Allow filtering by name, email, etc.
6. **Add sorting** - Allow sorting by different fields
7. **Add caching** - Cache frequently accessed data
8. **Add rate limiting** - Prevent API abuse

## Related Files

- **Controller:** `app/Http/Controllers/Api/UserController.php`
- **Routes:** `routes/api.php`
- **Model:** `app/Models/User.php`
- **Tests:** `tests/Feature/UserApiTest.php`
- **Bootstrap:** `bootstrap/app.php`
