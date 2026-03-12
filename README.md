# Token Auth API (Laravel Sanctum)

A simple Laravel API with token authentication and CRUD for notes.

**Setup**
1. `composer install`
2. `cp .env.example .env`
3. Update database credentials in `.env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan serve`

**Auth Flow**
1. Register or login to receive a personal access token.
2. Send `Authorization: Bearer <token>` on protected endpoints.
3. Logout revokes the current token.

**API List**
Public endpoints:
- `POST /api/register` body: `name`, `email`, `password`
- `POST /api/login` body: `email`, `password`

Protected endpoints (require Bearer token):
- `GET /api/me`
- `POST /api/logout`
- `GET /api/notes` query: `search` (optional). Returns paginated list (10 per page).
- `GET /api/notes/{id}`
- `POST /api/notes` body: `title`, `description`
- `PUT /api/notes/{id}` body: `title` (optional), `description` (optional)
- `DELETE /api/notes/{id}`

**Bruno Collection**
- Collection path: `api_collection/`
- `Auth/Login` saves the token to the `token` environment variable automatically.
- All protected requests use the `token` variable as Bearer auth.

**Examples (curl)**
Register:
```bash
curl -X POST http://127.0.0.1:8000/api/register \\
  -H "Content-Type: application/json" \\
  -d '{\"name\":\"User\",\"email\":\"user@example.com\",\"password\":\"password123\"}'
```

Login:
```bash
curl -X POST http://127.0.0.1:8000/api/login \\
  -H "Content-Type: application/json" \\
  -d '{\"email\":\"user@example.com\",\"password\":\"password123\"}'
```

Me:
```bash
curl http://127.0.0.1:8000/api/me \\
  -H "Authorization: Bearer <token>"
```

Create Note:
```bash
curl -X POST http://127.0.0.1:8000/api/notes \\
  -H "Authorization: Bearer <token>" \\
  -H "Content-Type: application/json" \\
  -d '{\"title\":\"My Note\",\"description\":\"Hello\"}'
```

Search Notes:
```bash
curl "http://127.0.0.1:8000/api/notes?search=hello" \\
  -H "Authorization: Bearer <token>"
```
