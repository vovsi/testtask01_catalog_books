# Book Catalog (PHP Project)

A simple book catalog management system built with PHP and MySQL. Includes database migrations and a user-friendly web interface for managing book data.

## Getting Started

### Requirements

- XAMPP (or similar stack with Apache, PHP, and MySQL)

### Setup Instructions

1. Place the project folder inside the `htdocs` directory (e.g., `C:/xampp/htdocs/your-folder-name`)
2. Open XAMPP and start both **Apache** and **MySQL**
3. In your browser, go to:  
   `http://localhost/your-folder-name`

### Before Using the Site

1. Create an **empty MySQL database** named `catalog_books` with `utf8_general_ci` collation.
2. Make sure the database configuration in the project is correct:  
   - Default: `username: root`, `password: (empty)`  
   - To change credentials, edit the file:  
     `/Database/DbConfig.php`
3. On the website, navigate to the **"Migrations"** section and run the migration process.
4. All necessary tables and seed data will be loaded into the `catalog_books` database.

## How Migrations Work

- Migration files are located at:  
  `/Database/migrations/sqlFiles/`
- Each file represents a new version of the database. Files are **numbered** and include a short **description**.
- When a new migration is added and applied through the site, the corresponding SQL script is executed.
- The `versions` table in the database keeps track of applied migrations, including the timestamp, to prevent duplicate runs.

## License

This project is open-source and free to use under the MIT License.
