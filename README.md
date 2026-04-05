# Workopia

A full-featured job board application built with Laravel 12, Blade, and Tailwind CSS 4. Users can post job listings, search by keyword and location, apply with a resume upload, and save bookmarks — all with role-based authorization baked in.

---

## Features

- **Job listings** — create, edit, and delete postings with company details, location, salary, and job type
- **Job search** — filter by keyword (title / description) and location (city / state / zipcode)
- **Applications** — apply to jobs with a PDF resume upload; duplicate applications are prevented
- **Bookmarks** — save and manage favourite listings per user
- **Authentication** — register, login, and logout with session-based auth
- **Authorization** — owners can only edit/delete their own listings (via `JobPolicy`)
- **Dashboard** — each user sees their posted jobs alongside applicant details
- **Profile management** — update name, email, and avatar
- **Map geocoding** — Mapbox integration for address lookup
- **Email notifications** — `JobApplied` mailable ready to send on application (queue-ready)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| PHP | ≥ 8.2 |
| Frontend | Blade + Tailwind CSS 4 |
| Build tool | Vite 7 |
| Database | SQLite (default) · MySQL compatible |
| Storage | Local disk (public) |
| Mail | Log driver (dev) · any SMTP in prod |
| Testing | Pest 4 |

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- SQLite (bundled with PHP) **or** a MySQL / PostgreSQL instance

---

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/muhammedprof/workopiatest.git
cd workopiatest
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies and build assets

```bash
npm install
npm run build
```

### 4. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and adjust as needed. The default config uses SQLite, so no database server is required for a quick start.

### 5. Run migrations and seed the database

```bash
php artisan migrate --seed
```

This runs all migrations and seeds the database with sample job listings and a test user.

### 6. Create the storage symlink

```bash
php artisan storage:link
```

### 7. Start the development server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000).

For hot-reloading assets in a separate terminal:

```bash
npm run dev
```

---

## Environment Variables

Key variables to configure in `.env`:

```dotenv
APP_NAME=Workopia
APP_URL=http://localhost:8000

# Database — SQLite (default, no extra setup needed)
DB_CONNECTION=sqlite

# Switch to MySQL if preferred
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=workopia
# DB_USERNAME=root
# DB_PASSWORD=secret

# Mail — uses "log" driver in development (check storage/logs/laravel.log)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@workopia.test"
MAIL_FROM_NAME="Workopia"

# Mapbox — required for geocoding on job forms
MAPBOX_API_KEY=your_mapbox_public_token
```

---

## Database Schema

```
users
  id, name, email, password, avatar, remember_token, timestamps

job_listings
  id, user_id (FK), title, description, salary, tags,
  job_type (enum), remote, requirements, benefits,
  address, city, state, zipcode,
  contact_email, contact_phone,
  company_name, company_description, company_logo, company_website,
  timestamps

applicants
  id, user_id (FK), job_id (FK),
  full_name, contact_phone, contact_email, message, location, resume_path,
  timestamps

job_user_bookmarks
  id, user_id (FK), job_id (FK), timestamps
```

---

## Seeders

| Seeder | Description |
|---|---|
| `TestUserSeeder` | Creates a single known test account |
| `RandomUserSeeder` | Generates a batch of fake users |
| `JobSeeder` | Seeds jobs from `database/seeders/data/job_listings.php` |
| `RandomJobSeeder` | Generates random job listings with Faker |
| `BookmarkSeeder` | Creates sample bookmarks across users and jobs |

Run a specific seeder:

```bash
php artisan db:seed --class=TestUserSeeder
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── JobController.php         # CRUD for job listings
│   │   ├── ApplicantController.php   # Store and delete applications
│   │   ├── BookmarkController.php    # Bookmark management
│   │   ├── DashboardController.php   # User dashboard
│   │   ├── ProfileController.php     # Profile updates
│   │   ├── HomeController.php        # Landing page
│   │   ├── LoginController.php       # Authentication
│   │   ├── RegisterController.php    # Registration
│   │   └── GeocodeController.php     # Mapbox geocoding proxy
│   └── Middleware/
│       └── LogRequest.php            # Request logging middleware
├── Mail/
│   └── JobApplied.php                # Application confirmation email
├── Models/
│   ├── Job.php                       # job_listings table
│   ├── User.php                      # users table
│   └── Applicant.php                 # applicants table
├── Policies/
│   └── JobPolicy.php                 # Owner-only update/delete
└── View/Components/                  # Blade UI components
    ├── Alert, Header, NavLink, JobCard, Search ...
    └── inputs/ (Text, TextArea, Select, File)

resources/views/
├── auth/          (login, register)
├── jobs/          (index, show, create, edit, bookmarked)
├── dashboard/
├── emails/
├── pages/         (home)
└── components/

routes/
└── web.php
```

---

## Key Routes

| Method | URI | Action | Auth |
|---|---|---|---|
| GET | `/` | Home page | — |
| GET | `/jobs` | All listings (paginated) | — |
| GET | `/jobs/search` | Search results | — |
| GET | `/jobs/{id}` | Job detail | — |
| GET | `/jobs/create` | Create form | ✓ |
| POST | `/jobs` | Store listing | ✓ |
| GET | `/jobs/{id}/edit` | Edit form | ✓ owner |
| PUT | `/jobs/{id}` | Update listing | ✓ owner |
| DELETE | `/jobs/{id}` | Delete listing | ✓ owner |
| POST | `/jobs/{job}/apply` | Submit application | — |
| GET | `/bookmarks` | Saved listings | ✓ |
| POST | `/bookmarks/{job}` | Bookmark a job | ✓ |
| DELETE | `/bookmarks/{job}` | Remove bookmark | ✓ |
| GET | `/dashboard` | User dashboard | ✓ |
| PUT | `/profile` | Update profile | ✓ |
| GET | `/register` | Register form | guest |
| POST | `/register` | Create account | guest |
| GET | `/login` | Login form | guest |
| POST | `/login` | Authenticate | guest |
| POST | `/logout` | Logout | — |
| GET | `/geocode` | Address lookup | — |

---

## Testing

This project uses [Pest](https://pestphp.com/). Run the test suite with:

```bash
php artisan test
```

Or directly:

```bash
./vendor/bin/pest
```

---

## Deployment Checklist

```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Optimise for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations
php artisan migrate --force

# Build frontend assets
npm ci
npm run build
```

Configure a queue worker for async email delivery:

```bash
php artisan queue:work --tries=3
```

---

## License

This project is open-source and available under the [MIT License](LICENSE).