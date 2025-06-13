# API Endpoints for Postman Testing

This document provides information about the API endpoints available for testing with Postman.

## Base URL
```
http://127.0.0.1:8000
```

## Available Endpoints

### 1. Products API
**Endpoint:** `GET /api/products`
**URL:** `http://127.0.0.1:8000/api/products`

**Query Parameters:**
- `search` (optional): Search term to filter products
- `paginate` (optional): Set to "false" to get all products without pagination
- `per_page` (optional): Number of items per page (default: 10)

**Examples:**
- Get all products: `GET http://127.0.0.1:8000/api/products?paginate=false`
- Get paginated products: `GET http://127.0.0.1:8000/api/products`
- Search products: `GET http://127.0.0.1:8000/api/products?search=laptop`
- Custom pagination: `GET http://127.0.0.1:8000/api/products?per_page=20`

### 2. Categories API
**Endpoint:** `GET /api/categories`
**URL:** `http://127.0.0.1:8000/api/categories`

**Query Parameters:**
- `search` (optional): Search term to filter categories
- `paginate` (optional): Set to "false" to get all categories without pagination
- `per_page` (optional): Number of items per page (default: 10)

**Examples:**
- Get all categories: `GET http://127.0.0.1:8000/api/categories?paginate=false`
- Search categories: `GET http://127.0.0.1:8000/api/categories?search=electronics`

### 3. Orders API
**Endpoint:** `GET /api/orders`
**URL:** `http://127.0.0.1:8000/api/orders`

**Query Parameters:**
- `search` (optional): Search term to filter orders
- `paginate` (optional): Set to "false" to get all orders without pagination
- `per_page` (optional): Number of items per page (default: 10)

**Examples:**
- Get all orders: `GET http://127.0.0.1:8000/api/orders?paginate=false`
- Search orders: `GET http://127.0.0.1:8000/api/orders?search=pending`

### 4. Users API
**Endpoint:** `GET /api/users`
**URL:** `http://127.0.0.1:8000/api/users`

**Query Parameters:**
- `search` (optional): Search term to filter users
- `paginate` (optional): Set to "false" to get all users without pagination
- `per_page` (optional): Number of items per page (default: 10)

**Examples:**
- Get all users: `GET http://127.0.0.1:8000/api/users?paginate=false`
- Search users: `GET http://127.0.0.1:8000/api/users?search=admin`

## Response Format

All endpoints return JSON responses in the following format:

```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [...],
    "total_count": 100,
    "additional_counts": {...}
}
```

## How to Use in Postman

1. **Create a new request** in Postman
2. **Set the method** to `GET`
3. **Enter the URL** (e.g., `http://127.0.0.1:8000/api/products`)
4. **Add query parameters** if needed in the Params tab
5. **Send the request**

## Notes

- All endpoints use GET method
- No authentication required for these API endpoints
- Data includes relationships (e.g., products include category information)
- Search functionality works across multiple fields
- Pagination can be disabled by setting `paginate=false`
