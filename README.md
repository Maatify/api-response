# 📦 Maatify API Response

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

[![Current version](https://img.shields.io/packagist/v/maatify/api-response)](https://packagist.org/packages/maatify/api-response)
[![PHP Version Support](https://img.shields.io/packagist/php-v/maatify/api-response)](https://packagist.org/packages/maatify/api-response)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/api-response)](https://packagist.org/packages/maatify/api-response/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/api-response)](https://packagist.org/packages/maatify/api-response/stats)
[![Stars](https://img.shields.io/packagist/stars/maatify/api-response)](https://github.com/maatify/api-response/stargazers)
[![Tests](https://github.com/maatify/api-response/actions/workflows/run-tests.yml/badge.svg)](https://github.com/maatify/api-response/actions)

---

**Maatify API Response** is a lightweight, PSR-7 compatible, Slim-friendly structured JSON response library for clean and consistent API responses. Built with production logging in mind via [maatify/slim-logger](https://github.com/maatify/slim-logger). It is part of the modular [Maatify.dev](https://www.maatify.dev) ecosystem.

---

## 🚀 Features

- ✅ PSR-7 compatible (`ResponseInterface`)
- ✅ Slim-ready (`return Json::...`)
- ✅ Structured error responses (missing, invalid, etc.)
- ✅ Success responses with metadata
- ✅ Automatic route+line trace: `user:login::55`
- ✅ Production-safe JSON POST logging via [maatify/slim-logger](https://github.com/maatify/slim-logger)
- ✅ Environment toggles for logging

---

## 🛠 Installation

```bash
composer require maatify/api-response
```

> Requires PHP 8.1+  
> Uses `maatify/slim-logger` under the hood for logging (installed automatically)

---

## 📦 Usage in Slim

```php
use Maatify\ApiResponse\Json;

$app->post('/register', function ($request, $response) {
    return Json::missing($response, 'email');
});
```

---

## 💡 Usage in Pure PHP (no framework)

You can use this library even in basic PHP without Slim.

```php
require 'vendor/autoload.php';

use Maatify\ApiResponse\Json;
use Nyholm\Psr7\Response;

$response = new Response();

if (empty($_POST['email'])) {
    $response = Json::missing($response, 'email', 'Email is required', __LINE__);
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header("$name: $value");
    }
}
echo (string)$response->getBody();
```

---

### 🔁 Output

```http
HTTP/1.1 400 Bad Request
Content-Type: application/json
```

```json
{
  "success": false,
  "response": 1000,
  "var": "email",
  "description": "MISSING Email",
  "more_info": "Email is required",
  "error_details": "index::15"
}
```

---

## ✨ Example Slim Response

```json
{
  "success": false,
  "response": 1000,
  "var": "email",
  "description": "MISSING Email",
  "more_info": "",
  "error_details": "register::34"
}
```

---

## ✅ Supported Methods

| Method               | Description                        |
|----------------------|------------------------------------|
| `missing()`          | Field missing from input           |
| `incorrect()`        | Incorrect format or value          |
| `invalid()`          | Invalid input                      |
| `notExist()`         | Value doesn't exist (e.g. user ID) |
| `success()`          | Standard success output            |
| `dbError()`          | Internal DB/system error           |
| `tooManyAttempts()`  | Rate limit exceeded                |

---

## 📊 HTTP Status Code Reference

| Method               | Status |
|----------------------|--------|
| `missing()`          | `400`  |
| `incorrect()`        | `400`  |
| `invalid()`          | `400`  |
| `notExist()`         | `400`  |
| `success()`          | `200`  |
| `dbError()`          | `500`  |
| `tooManyAttempts()`  | `429`  |

---

## 🔐 Secure Logging

Enable structured logging in production via [maatify/slim-logger](https://github.com/maatify/slim-logger)

### 1. Enable in your `.env`

```env
JSON_POST_LOG=1
```

### 2. Sample Logged Output

```json
{
  "response": {
    "success": false,
    "response": 1000
  },
  "posted_data": {
    "email": "user@test.com",
    "password": "******"
  }
}
```

**Log file:** `logs/api/response.log`  
**Log level:** `Info`

---

## 📂 Directory Structure

```
src/
├── CoreResponder.php        # Base logic + logger
├── BaseResponder.php        # Validation & error helpers
└── Json.php                 # Final static entrypoint class
```

---

## 🧩 Extend It

Want custom codes or more logic?

- Extend `BaseResponder` or `CoreResponder`
- Override any method (e.g. add audit log or rate limiter)
- Customize `logResponse()` for deeper monitoring

---

## 🧪 Testing

### ✅ Run Tests

```bash
composer test
```

### ✅ Run One Test

```bash
composer test -- --filter testSuccessResponse
```

### ✅ CI (GitHub Actions)

Tested on PHP 8.2, 8.3, and 8.4 using [run-tests.yml](.github/workflows/run-tests.yml)

---

## 🆚 Slim vs Pure PHP Comparison

| Feature        | Slim                        | Pure PHP                          |
|----------------|-----------------------------|-----------------------------------|
| Routing        | `$app->post(...`            | Native `index.php` or custom      |
| JSON Response  | `return Json::success(...)` | `$response = Json::success(...)`  |
| Headers/Status | Handled by Slim             | You manually set headers + status |
| Logging        | Via maatify/slim-logger     | ✅ Works the same                  |

---

## 📄 License

[MIT License](./LICENSE) © 2025 [Maatify.dev](https://maatify.dev)

---

## 🙋‍♂️ Questions or Feedback?

- Open an issue on [GitHub](https://github.com/maatify/slim-logger)
---