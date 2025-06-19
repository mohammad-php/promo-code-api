# Promo Code API

A Laravel-based Promo Code API developed as part of a technical assessment. It exposes a clean, RESTful interface for admins to create promo codes with various constraints and for users to validate and apply them. The system includes features such as:

- Authentication & Authorization
- Promo usability rules
- Usage limits
- Per-user restrictions
- Redis caching
- Dockerized deployment
- API documentation
- Automated tests

---

## Tech Stack

- **PHP 8.2**
- **Laravel 11.31**
- **MySQL 8.0**
- **Redis (phpredis)**
- **Docker + Docker Compose**

### Laravel Packages

- [Sanctum](https://laravel.com/docs/11.x/sanctum) – Token-based API authentication
- [Spatie Laravel Data](https://spatie.be/docs/laravel-data) – Typed request/response DTOs
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) – Role and permission management
- [Scribe](https://scribe.knuckles.wtf/laravel/) – Auto-generated API documentation
- [Pest](https://pestphp.com/) – Elegant PHP testing framework
- [Laravel Pint](https://laravel.com/docs/11.x/pint) – Code style fixer

---

## 🚀 Getting Started

### Prerequisites

- Docker + Docker Compose
- Ensure port `8080` is available

##### Note: When using the provided Docker setup (./bin/start.sh), you can change them in .env file, the database will use:
- Username: promo
- Password: promoPWD123
- Database: promo_api

### Setup in One Command

```bash
chmod +x ./bin/start.sh
./bin/start.sh
```

#### This command will:
- Copy .env.example → .env (if missing)
- Build and start Docker containers
- Wait for MySQL to be ready
- Grant DB privileges for promo user
- Create the test database if missing
- Run database migrations and seeders
- Generate API documentation via Scribe
- Run tests (only if --test is passed)


### API Documentation
#### After setup, open:
http://localhost:8080/docs

##### From there you can:
- Explore endpoints interactively
- Export the collection to Postman


### Running Tests
```bash
./bin/start.sh --test
```
#### Runs the full test suite via Pest including
- Feature tests for both endpoints
- DB assertions
- Role/permission validations


### Authentication
#### This app uses Laravel Sanctum for API token authentication.
- Admins must be authenticated to create promo codes.
- Users must be authenticated to validate promo codes.


### API Endpoints Overview
#### 1. Admin: Create Promo Code
##### POST /api/v1/promo-codes/create

##### Supports:
- Manual or auto-generated code
- Expiry date
- Max usage and per-user usage
- Restricting code to specific users
- Type: percentage or fixed value

#### 2. User: Validate Promo Code
##### POST /api/v1/promo-codes/validate
##### Checks if:
- Promo exists and not expired
- Usages limits are not exceeded
- User is allowed to use it
##### Returns:
{
"price": 100,
"promocode_discounted_amount": 20,
"final_price": 80
}

### Bonus Features Implemented
- Rate limiting on promo validation endpoint
- Redis caching for promo code lookup (10 min TTL)
- Auto-generated API docs with Scribe
- Dockerized setup with MySQL & Redis
- Test support via .env.testing and separate test DB


### Docker Services
- nginx
- mysql
- redis
- promo_api


### Code Style
```bash
vendor/bin/pint
```
