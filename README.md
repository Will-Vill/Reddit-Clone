# Reddit Clone (Full-Stack Web Application)

A Reddit-inspired web application built with PHP (Laravel), MariaDB, designed to manage communities, posts, comments, and user authentication.

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![MariaDB](https://img.shields.io/badge/MariaDB-03589C?style=for-the-badge&logo=mariadb&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black) ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

## 🧠 Architecture

<img width="1253" height="1253" alt="Screenshot da 2026-09-02 08-27-36" src="https://github.com/user-attachments/assets/235c4be1-e97b-4265-a873-a90ae41cfeb2" />

The Web Application presents as shown in this first image with the login page protected with Javascript and backend protection.

After the register/login the idex page shows initially a blank page because there aren't posts downloaded.

<img width="1253" height="1253" alt="Screenshot da 2026-09-02 08-46-30" src="https://github.com/user-attachments/assets/60fb304e-ac36-4e51-ac4f-4288480a5a9e" />

At the left we can see some subreddits, with a reddit API key and client ID if you click on them it will recover the lasts 10 post of that subreddit and show it on the page. If you want to download that certain post on your database you have to comment or like/dislike the post. After that the post is in your database, with an image if needed.

You can create posts with text/images 

<img width="1253" height="1253" alt="Screenshot da 2026-09-02 09-10-43" src="https://github.com/user-attachments/assets/b293b7be-4c4f-4bab-bf83-5bedc716a976" />

This one is an example of a post with only text and a comment. All this information are saved in the database.

<img width="1253" height="1253" alt="Screenshot da 2026-09-02 09-11-17" src="https://github.com/user-attachments/assets/7264c457-f3da-48d5-a402-d5e9c25cfabe" />

This is the main page with 2 posts

<img width="1253" height="1253" alt="Screenshot da 2026-09-02 09-15-51" src="https://github.com/user-attachments/assets/7d8b8a49-d949-4d44-903c-e4ace9a6b4ab" />

## Requirements

* **PHP:** >= 8.1 (with `pdo_mysql`, `bcmath`, and `iconv` extensions enabled)
* **Composer** & **Node.js / NPM**
* **MariaDB** / MySQL server

## 🚀 How to Run

### 1. Database setup
```bash
mariadb -u root < sql/Sql.sql
```

### 2. Install Dependencies
```bash
composer install
npm install
php artisan key:generate
```

### 3. Start Local Servers

Backed Laravel
```bash
php artisan serve
```

Frontend Vite
```bash
npm run dev
```
Open your browser and navigate to ```http://localhost:8000```

> Note: All the images and the Web Application routes are in Italian.


