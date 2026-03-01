# PHPCRM — Open Source CRM  
## Installation & Setup Guide

---

## System Requirements

- PHP **7.4 or higher** (PHP 8+ recommended)
- MySQL / MariaDB
- Apache or Nginx Web Server
- Required PHP Extensions:
  - `mysqli`
  - `pdo_mysql` (optional, recommended)
  - `openssl`
  - `mbstring`
  - `json`
  - `curl`
- `mod_rewrite` enabled (optional, for clean URLs)

---

## Folder Structure

```
/public               → Public root (index & modules)
/app                  → MVC application files
/app/controllers      → Controllers
/app/models           → Data models
/app/views            → Views / templates
/app/config           → Configuration & database settings
/database             → SQL installation files
```

---

## Database Installation File

The SQL file required for database setup is located at:

```
/database/phpcrm_install.sql
```

---

## Installation Steps

1. Download the PHPCRM source code and extract it into your web directory.
2. Create a new MySQL database (**UTF8MB4 recommended**).
3. Import the SQL file:
   ```
   /database/phpcrm_install.sql
   ```
4. Open the file:
   ```
   /app/config/database.php
   ```
   Update the following values:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`

5. (Optional) Open:
   ```
   /app/config/config.php
   ```
   Update company or system settings if required.

---

## Accessing the CRM

### Localhost
```
http://localhost/your-crm-folder/public/
```

**Example:**
```
http://localhost/phpcrm/public/
```

### Live Server
```
https://yourdomain.com/public/
```

---

## Default Admin Login

After installation, use the following credentials:

- **Email:** admin@phpcrm.com  
- **Password:** 123456

> ⚠️ If login fails, ensure the `password_hash` field in table `phpcrm_users`
> is `VARCHAR(255)` and not truncated.

---

## File & Folder Permissions

Ensure the following directories are writable:

- `/public/uploads`
- `/app/logs` (if logging is enabled)

---

## Security Notes

- Delete `/database/phpcrm_install.sql` after installation
- Change the admin password immediately after first login
- Keep database credentials secure
- Do not expose `/app/config` directory publicly

---

## Recommended PHP Settings

```
max_execution_time = 300
memory_limit = 512M
upload_max_filesize = 32M
post_max_size = 32M
```

---

## Support

🌐 Website: https://www.phpcrm.com

---

## License

PHPCRM is released under the **MIT Open Source License**.

You are free to **use, modify, rebrand, and deploy commercially**,  
provided this copyright notice is retained.

---

### Thank you for choosing **PHPCRM Open Source** 🚀
