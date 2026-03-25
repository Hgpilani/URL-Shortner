# URL Shortener (Laravel)

A simple web app to create **short links** (like `http://localhost:8000/s/abc12345`) that redirect to long URLs.

Built with **Laravel 11**.

This project has **roles**:
- **SuperAdmin**: manage companies, invitations, view stats, view clients list.
- **Admin**: manage invitations for their company, see team members, see dashboard.
- **Member**: create short URLs (limited by visibility rules) and view their URLs.

---

## Requirements

- PHP (8.x recommended)
- Composer
- MySQL (or another DB configured in `.env`)

---

## Setup (local)

From the project folder:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update your database settings in `.env`, then run:

```bash
php artisan migrate --seed
php artisan serve
```

If you want to import the provided database dump instead of running migrations:

- Database file: `imageshorten.sql`
- Import it into your MySQL database (example):

```bash
mysql -u root -p YOUR_DB_NAME < imageshorten.sql
```

Open:
- `http://localhost:8000/login`

---

## Main Pages (quick list)

- **Login**: `/login`
- **Dashboard**: `/dashboard` (auto-redirects based on your role)
- **URLs list + generate**: `/urls`
- **Profile (update name/email/password)**: `/profile`

### SuperAdmin
- **Dashboard**: `/superadmin/dashboard`
- **Companies**: `/superadmin/companies`
- **Invitations**: `/superadmin/invitations`
- **Invite admin to new company**: `/superadmin/invitations/create`
- **Clients**: `/superadmin/clients`
- **Stats**: `/superadmin/stats`

### Admin
- **Dashboard**: `/admin/dashboard`
- **Team Members**: `/admin/team-members`
- **Invitations**: `/admin/invitations`

---

## Password Help

- **Forgot password**: `/forgot-password`
- **Reset password**: link sent to your email

---

## Email (SMTP) Setup

Email is used for:
- **Invitations**
- **Forgot password / reset password**
- **Email verification**

Configure SMTP in your `.env` file (example):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="URL Shortener"
```

Notes:
- If you use **Gmail**, generate an **App Password** (recommended) instead of using your normal password.
- Don’t commit real credentials to git / screenshots. Keep them only in `.env`.

---

## Notes

- Pagination is set to **10 items per page** on list pages.
- The UI uses **Bootstrap 5**.

---

## AI Assistance (Disclosure)

Some parts of this project were assisted by AI tools:
- **UI/design updates**: assisted by ChatGPT and Claude(free Version)
- **Email formatting/content**: assisted by ChatGPT and Claude(free Version)
- **Testing/verification**: assisted by ChatGPT and Claude(free Version) (manual checks + guided debugging)
