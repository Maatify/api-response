# 📦 Maatify API Response

---
![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---
[pkg]: <https://packagist.org/packages/maatify/slim-logger>
[pkg-stats]: <https://packagist.org/packages/maatify/slim-logger/stats>
[![Current version](https://img.shields.io/packagist/v/maatify/slim-logger)][pkg]
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/slim-logger)][pkg]
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/slim-logger)][pkg-stats]
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/slim-logger)][pkg-stats]
[![Stars](https://img.shields.io/packagist/stars/maatify/slim-logger)](https://github.com/maatify/slim-logger/stargazers)

[![Tests](https://github.com/maatify/slim-logger/actions/workflows/run-tests.yml/badge.svg)](https://github.com/maatify/slim-logger/actions)

**Maatify API Response** is a lightweight, PSR-7 compatible, Slim-friendly structured JSON response library for clean and consistent API responses. Built with production logging in mind via [maatify/slim-logger](https://github.com/maatify/slim-logger). It is part of the modular [Maatify]
(https://maatify.dev) ecosystem.

---

## 🚀 Features

- ✅ PSR-7 compatible (`ResponseInterface`)
- ✅ Slim-ready (`return Json::...`)
- ✅ Structured error responses (missing, invalid, etc.)
- ✅ Success responses with metadata
- ✅ Automatic route+line trace: `user:login::55`
- ✅ Production-safe JSON POST logging via `maatify/slim-logger`
- ✅ Environment toggles for logging

---

## 🛠 Installation

```bash
composer require maatify/api-response
```

Make sure to also install `maatify/slim-logger` (auto-installed as a dependency).

---

## 📦 Usage in Slim

```php
use Maatify\ApiResponse\Json;

$app->post('/register', function ($request, $response) {
    return Json::missing($response, 'email');
});
```

---

## ✨ Example Response

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

## 🔐 Secure Logging

Enable structured logging in production:

### 1. Enable it via `.env`

```bash
JSON_POST_LOG=1
```

### 2. Output example

```json
{
  "response": {
    "success": false,
    "response": 1000,
    ...
  },
  "posted_data": {
    "email": "user@test.com",
    "password": "******"
  },
  ...
}
```

**Log file:** `logs/api/response.log`  
**Log level:** `Info`

Sensitive fields like `password`, `token` are automatically masked.

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

Want custom error codes, new fields, or OpenAPI responses?

- Extend `BaseResponder` or `CoreResponder`
- Override any method
- Customize `logResponse()` for advanced audit logging

---

## 🧪 Sample Success Usage

```php
return Json::success($response, ['user_id' => 123], 'Registered');
```

```json
{
  "success": true,
  "response": 200,
  "result": {
    "user_id": 123
  },
  "description": "Registered",
  "more_info": "",
  "error_details": "user:register::72"
}
```

---

## 🧼 Logging Best Practices

- Enable logging only in staging/production
- Monitor file size or integrate with log rotation
- Use log levels (`Info`, `Error`, etc.) for filtering

---

## 📄 License

[MIT License](./LICENSE) © 2025 [Maatify.dev](https://maatify.dev)

---

## 🙋‍♂️ Questions or Feedback?

- Open an issue on [GitHub](https://github.com/maatify/slim-logger)

---