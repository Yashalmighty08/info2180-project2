# Dolphin CRM: shared kit (read this first)

## One-time setup on your laptop
1. Start Apache and MySQL in the XAMPP Control Panel.
2. Open http://localhost/phpmyadmin, click **Import**, import `schema.sql`, then import `seed.sql`.
3. Check: the database `dolphin_crm` has 3 tables (`users`, `contacts`, `notes`); `users` has 4 rows.
4. In this folder run: `C:\xampp\php\php.exe -S localhost:8000`
5. Open http://localhost:8000/login.php and log in: `admin@project2.com` / `password123` (Admin), or `member@project2.com` / `Member123` (Member).

## Who owns what
| Part | Files |
|---|---|
| Kit (nobody edits) | config/, includes/, index.php, js/app.js, css/layout.css, schema.sql, seed.sql |
| 1 Access | login.php, logout.php, api/login.php, js/login.js, css/login.css, pages/new_user.php, pages/users.php, api/add_user.php, js/pages/new_user.js (the kit holds working copies of the login files, yours replace them) |
| 2 Dashboard | pages/dashboard.php, api/contacts_list.php, js/pages/dashboard.js, css/pages/dashboard.css |
| 3 Create contact | pages/new_contact.php, api/add_contact.php, js/pages/new_contact.js |
| 4 Contact details | pages/contact.php, api/contact_get.php, api/assign_contact.php, api/switch_type.php, js/pages/contact.js, css/pages/contact.css |
| 5 Notes | pages/notes.php, api/add_note.php, js/pages/notes.js, css/pages/notes.css |

## The agreements (the only places the parts touch)
- Any link or button that opens a screen is `data-page="name"` (plus `data-id="7"` where needed). The shell loads `pages/name.php` into the main area. Dashboard View = `data-page="contact" data-id="<id>"`. Dashboard button = `data-page="new_contact"`.
- After creating a contact, Part 3 calls `loadPage('contact', { id: reply.data.id })`.
- Part 4's page has `<section id="notes-section" data-contact-id="...">` and loads Part 5 into it with `loadFragment('#notes-section', 'notes', { contact_id: id })`.
- After a note is saved, Part 5 fires `document.dispatchEvent(new CustomEvent('contact:changed'))`; Part 4 listens and refreshes.
- Session: `$_SESSION['user']` has exactly `id, firstname, lastname, email, role`.
- Every api file replies with `json_ok(...)` or `json_fail(...)` only: `{success, message, errors, data}`.
- Every api file that saves data starts with `require_post();`. It allows POST only and checks the CSRF token. Always call your api files with `apiPost(...)` (never a plain `fetch`), because `apiPost` adds the token for you. If you see "Security check failed", you called the api another way or your session ended.
- Roles: `Admin`, `Member`. Contact types: `Sales Lead`, `Support`. Titles: Mr, Mrs, Ms, Dr, Prof.

## Testing your part alone
Open any screen directly: `http://localhost:8000/index.php?page=contact&id=1` or `?page=notes&contact_id=1`.
Call an api file from the browser console on any page of the shell, for example:
`apiPost('api/add_note.php', {contact_id: 1, comment: 'test'}).then(console.log)`

## Security built into the kit (lecture 8)
Prepared statements (SQL injection), `h()` and `textContent` (XSS), `password_hash`/`password_verify`, `session_regenerate_id` at login, a session that ends after 30 idle minutes, and a CSRF token. You use these by following the walkthrough; you don't write the token code.

## Rules
- Never rename the database, tables or columns. Don't edit the kit files. If your MySQL needs a password or another port, copy `config/db.local.example.php` to `config/db.local.php` and edit that copy (it is never committed).
- Reset your database any time: run `DROP DATABASE dolphin_crm;` in the SQL tab, then import `schema.sql` and `seed.sql` again.
