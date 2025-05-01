# Simple To-Do List Application

This is a simple To-Do List application built using PHP, MySQL, and Tailwind CSS. It allows users to add, mark as completed, and delete tasks. This guide will walk you through creating this project step by step.
![screenshot of project]([https://i.ibb.co/xqhLDz2t/Screenshot-2025-05-01-140521.jpg](https://github.com/Behparvar/PHP_LearningJourney/blob/main/simple_ToDoList/Screenshot%202025-05-01%20151320.jpg))
---

## Features
- Add new tasks.
- Mark tasks as completed or not completed.
- Delete tasks.
- Responsive and clean UI using Tailwind CSS.

---

## Prerequisites
Before starting, ensure you have the following installed:
1. **PHP** (version 7.4 or higher)
2. **MySQL** (or any compatible database server)
3. **A web server** (e.g., Apache or Nginx)
4. **A code editor** (e.g., Visual Studio Code)

---

## Step-by-Step Guide

### 1. Set Up the Database
1. Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or command line).
2. Create a new database:
   ```sql
   CREATE DATABASE todo_db;
3. Switch to the new database : 
    ```sql
    USE todo_db;
4. Create a tasks table: 
    ```sql
    CREATE TABLE tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task_name VARCHAR(255) NOT NULL,
        is_completed BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
### 2. Set Up the Project Files
1. Create a folder for your project, e.g., simple_ToDoList.
2. Inside the folder, create the following files:
index.php
add_task.php
delete_task.php
toggle_task.php
db_connect.php

### 3. Configure the Database Connection
1. Open db_connect.php and add the following code: 
    ```php
        <?php
        $host = 'localhost';
        $dbname = 'todo_db';
        $username = 'root'; // Replace with your MySQL username
        $password = '';     // Replace with your MySQL password

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        ?>
### 4. Create the Main Page (index.php)



