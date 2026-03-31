# Movie-Rating-website
# 🎬 MyMovieDB - Personal Movie Collection Manager

This project is a comprehensive **PHP/MySQL CRUD** (Create, Read, Update, Delete) application developed as part of the Web Programming course at **Istanbul Medipol University**. It allows users to maintain a personal digital library of movies they have watched or plan to watch, featuring a modern dark-themed interface.

## 🚀 Key Features
* **Full CRUD Functionality:** Add, view, edit, and delete movie records seamlessly.
* **Watch Status Tracking:** Categorize movies as 'Watched', 'Want to Watch', or 'Currently Watching'.
* **Rating System:** Visual star-rating system (1-5 stars) for personal evaluations.
* **Database Security:** Utilizes **PHP Data Objects (PDO)** with prepared statements to prevent SQL injection.
* **Responsive Design:** A custom CSS-styled UI with a blurred glass effect (backdrop-filter) and a dynamic grid layout.

## 🛠️ Technical Stack
* **Language:** PHP 8.x
* **Database:** MySQL / MariaDB (XAMPP environment)
* **Frontend:** HTML5, CSS3 (Modern Flexbox/Grid)
* **Connectivity:** PDO Extension

## 📦 Project Structure
* `index.php`: The main application logic handling all CRUD operations and UI rendering.
* `config.php`: Centralized database connection settings and UI helper functions.
* `mymoviedb.sql`: Complete database schema and sample movie data.
* `test_connection.php`: A dedicated debugging tool to verify environment and database health.

## 🔧 Installation & Setup
1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/sabina-novruzova/mymoviedb.git](https://github.com/sabina-novruzova/mymoviedb.git)
    ```
2.  **Database Configuration:**
    * Open XAMPP and start Apache and MySQL.
    * Create a database named `mymoviedb` in phpMyAdmin.
    * Import the `mymoviedb.sql` file.
3.  **Connection Settings:**
    * Check `config.php` to ensure `DB_USER` and `DB_PASS` match your local server settings.
4.  **Run Application:**
    * Place the folder in `htdocs` and visit `localhost/mymoviedb/index.php` in your browser.

---
**Developer:** Sabina Novruzova  
**Student ID:** 61220061  
**Department:** Electrical and Electronics Engineering, Istanbul Medipol University
