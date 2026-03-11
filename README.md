# Laravel User Authentication API

A RESTful API built with Laravel and Laravel Sanctum for user identity management, route protection, and profile data handling. Testable via Postman — no frontend required.

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [API Overview](#api-overview)
- [Authentication](#authentication)
- [Profile Management](#profile-management)
- [API Documentation](#api-documentation)
- [Testing Scenarios](#testing-scenarios)
- [Technical Constraints](#technical-constraints)

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL or SQLite
- Laravel 10+
- Laravel Sanctum

---

## Installation

Clone the repository:

```bash
git clone https://github.com/ikara-py/System_d-Athentification_-_Gestion_de_Compte.git
```

Install dependencies:

```bash
composer install
```

Copy the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

---

## Configuration

Open the `.env` file and configure your database connection:

```env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auth_api
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

```

Run migrations:

```bash
php artisan migrate
```

---

## Running the Application

Start the local development server:

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`.

---

## API Overview

### Base URL

```
http://127.0.0.1:8000/api
```

### Authentication Method

This API uses token-based authentication via Laravel Sanctum. After a successful login, you will receive a token that must be included in the `Authorization` header of every protected request:

```
Authorization: Bearer <your_token>
```

---

## Authentication

### POST /api/register

Creates a new user account.

**Request body (JSON):**

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Responses:**

| Status | Message |
|--------|---------|
| 201 | "Account created successfully" |
| 422 | Validation error details |

---

### POST /api/login

Authenticates a user and returns an access token.

**Request body (JSON):**

```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Login successful" + token |
| 401 | "Invalid credentials" |

---

### POST /api/logout

Logs out the authenticated user and invalidates the current token.

**Headers required:**

```
Authorization: Bearer <your_token>
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Logout successful" |
| 401 | "Unauthorized" |

---

## Profile Management

All routes in this section require a valid token in the `Authorization` header. Requests without a valid token will receive a `401 Unauthorized` response.

---

### GET /api/me

Returns the authenticated user's profile.

**Headers required:**

```
Authorization: Bearer <your_token>
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Profile fetched successfully" + user data |
| 401 | "Unauthorized" |

---

### PUT /api/me

Updates the authenticated user's name and/or email.

**Headers required:**

```
Authorization: Bearer <your_token>
```

**Request body (JSON):**

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com"
}
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Profile updated successfully" |
| 401 | "Unauthorized" |
| 422 | Validation error details |

---

### PUT /api/me/password

Changes the authenticated user's password.

**Headers required:**

```
Authorization: Bearer <your_token>
```

**Request body (JSON):**

```json
{
  "current_password": "old_password",
  "new_password": "new_password123",
  "new_password_confirmation": "new_password123"
}
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Password updated successfully" |
| 401 | "Unauthorized" |
| 422 | "Current password is incorrect" or validation error |

---

### DELETE /api/me

Permanently deletes the authenticated user's account.

**Headers required:**

```
Authorization: Bearer <your_token>
```

**Responses:**

| Status | Message |
|--------|---------|
| 200 | "Account deleted successfully" |
| 401 | "Unauthorized" |

---

## API Documentation

The API is documented using a Postman Collection. The exported file is located at:

```
/docs/postman_collection.json
```

To use it:

1. Open Postman
2. Click "Import"
3. Select the file `postman_collection.json`
4. Set the `base_url` variable to `http://127.0.0.1:8000`
5. After login, copy the returned token and set it as the `token` variable in the collection

All routes are documented with their expected request bodies, required headers, and all possible HTTP responses. Protected routes are clearly marked as requiring a Bearer token.

---

## Testing Scenarios

The following sequence covers the full test flow for this API:

1. **Register** — `POST /api/register` with valid name, email, and password
2. **Login** — `POST /api/login` to receive an access token
3. **Access profile without token** — `GET /api/me` with no Authorization header, must return `401 Unauthorized`
4. **Access profile with token** — `GET /api/me` with the token, must return the user's profile
5. **Update profile** — `PUT /api/me` with a new name or email
6. **Change password** — `PUT /api/me/password` with current and new password
7. **Logout** — `POST /api/logout` to invalidate the token
8. **Access profile after logout** — `GET /api/me` with the old token, must return `401 Unauthorized`

---

## Technical Constraints

- Laravel API only — no Blade templates, no frontend
- Token-based authentication using Laravel Sanctum
- All inputs are validated on every route
- HTTP status codes used: `200`, `201`, `401`, `422`
- Users can only access and modify their own profile
- Passwords are always hashed using bcrypt — never stored in plain text

## Developer

**Kara Ali**