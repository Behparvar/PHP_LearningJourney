# Phone Book Application

Welcome to my Phone Book Application! This is a simple web app I built to manage contacts. You can add, edit, delete, search, and sort contacts. It’s built with PHP and uses a MySQL database to store all the contact info. I used Tailwind CSS to make it look nice and clean. Below, you’ll find everything you need to know to set it up and use it!


## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [File Structure](#file-structure)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [Future Improvements](#future-improvements)
- [Contributing](#contributing)
- [License](#license)

## Features
This app has the following features:
- **Add Contacts**: Add new contacts with first name, last name, phone number, email, and address.
- **Edit Contacts**: Update existing contact details.
- **Delete Contacts**: Remove contacts with a confirmation prompt to avoid accidental deletes.
- **Search Contacts**: Search by first name, last name, or phone number.
- **Sort Contacts**: Sort contacts by name (A-Z or Z-A) or creation date (newest or oldest).
- **Show Details**: Toggle extra details like email and address for each contact.
- **Responsive Design**: The app looks good on both desktop and mobile thanks to Tailwind CSS.
- **Error Handling**: Shows success or error messages when adding/editing/deleting contacts.

## Technologies Used
Here’s what I used to build this app:
- **PHP**: For backend logic and handling database operations.
- **MySQL**: To store contact information.
- **PDO**: For secure database queries.
- **Tailwind CSS**: For styling the frontend (loaded via CDN).
- **HTML**: For the structure of the web pages.
- **JavaScript**: For toggling contact details.
- **Apache**: The web server (you’ll need this to run PHP).

## Prerequisites
Before you start, make sure you have:
- A web server like **Apache** (you can use XAMPP, WAMP, or MAMP for local development).
- **PHP** (version 7.4 or higher recommended).
- **MySQL** or **MariaDB** for the database.
- A web browser (Chrome, Firefox, etc.).
- A text editor like VS Code to edit the files.

## Installation
Follow these steps to set up the project on your local machine:

1. **Clone or Download the Project**:
   - If you’re using Git, clone the repo:
     ```bash
     git clone <repository-url>
     ```
   - Or download the ZIP file and extract it to your web server’s root directory (e.g., `htdocs` for XAMPP).

2. **Move Files to Web Server**:
   - Copy the project folder to your web server’s root directory. For example:
     - XAMPP: `C:\xampp\htdocs\phonebook`
     - WAMP: `C:\wamp\www\phonebook`

3. **Set Up the Database**:
   - Follow the [Database Setup](#database-setup) section below to create the database and table.

4. **Configure Database Connection**:
   - Open `db_connect.php` and update the database credentials (host, database name, username, password) to match your setup. Example:
     ```php
     $host = 'localhost';
     $dbname = 'phonebook';
     $username = 'root';
     $password = '';
     ```

5. **Start Your Web Server**:
   - Start Apache and MySQL using XAMPP/WAMP control panel or your server’s command line.

6. **Access the App**:
   - Open your browser and go to `http://localhost/phonebook/index.php`.

## Database Setup
You need to create a MySQL database and a table for contacts. Here’s how:

1. **Access MySQL**:
   - Use phpMyAdmin (comes with XAMPP/WAMP) or the MySQL command line.

2. **Create Database**:
   - Run this SQL command to create a database named `phonebook`:
     ```sql
     CREATE DATABASE phonebook;
     ```

3. **Create Contacts Table**:
   - Select the `phonebook` database and run this SQL to create the `contacts` table:
     ```sql
     CREATE TABLE contacts (
         id INT AUTO_INCREMENT PRIMARY KEY,
         first_name VARCHAR(50) NOT NULL,
         last_name VARCHAR(50) NOT NULL,
         phone VARCHAR(20) NOT NULL,
         email VARCHAR(100),
         address VARCHAR(255),
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

4. **Verify**:
   - Check phpMyAdmin or run `DESCRIBE contacts;` to make sure the table was created correctly.

## File Structure
Here’s what each file in the project does:
```
phonebook/
├── index.php           # Main page (displays contacts, search, and add form)
├── db_connect.php      # Database connection settings
├── add_contact.php     # Handles adding new contacts
├── edit_contact.php    # Handles editing existing contacts
├── delete_contact.php  # Handles deleting contacts
└── README.md           # This file
```

- **index.php**: The homepage where you see the contact list, search/sort options, and the form to add new contacts.
- **db_connect.php**: Connects to the MySQL database using PDO.
- **add_contact.php**: Processes the form to add a new contact and redirects back to `index.php`.
- **edit_contact.php**: Shows a form to edit a contact and updates the database.
- **delete_contact.php**: Deletes a contact from the database and redirects back.

## Usage
Here’s how to use the app:

1. **View Contacts**:
   - Open `http://localhost/phonebook/index.php` to see all contacts in a table.
   - Use the search bar to filter by name or phone number.
   - Use the sort dropdown to change the order (e.g., by name or date).

2. **Add a Contact**:
   - Fill out the form at the bottom of the page (first name, last name, phone are required; email and address are optional).
   - Click “Add Contact” to save it. You’ll see a green success message if it works.

3. **Edit a Contact**:
   - Click the “Edit” link next to a contact. It takes you to a form with the contact’s details.
   - Update the fields and submit to save changes.

4. **Delete a Contact**:
   - Click the “Delete” link next to a contact. Confirm the action when prompted.
   - The contact will be removed, and you’ll see a success message.

5. **Show Details**:
   - Click “Show More” next to a contact to toggle extra details like email and address.

## Troubleshooting
If something goes wrong, try these:
- **“Error fetching contacts”**: Check `db_connect.php` credentials and make sure MySQL is running.
- **Page is blank**: Ensure PHP is enabled in your web server and the files are in the correct directory.
- **Styles not loading**: Make sure you have an internet connection (Tailwind CSS loads from a CDN).
- **Can’t add/edit contacts**: Verify the `contacts` table exists and has the correct columns.
- **Still stuck?**: Check the Apache error logs or enable PHP error reporting by adding this to `index.php`:
  ```php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  ```

## Future Improvements
Here are some ideas I have to make this app better:
- Add validation for phone numbers and email formats.
- Include pagination for large contact lists.
- Add a login system to secure the app.
- Allow uploading contact photos.
- Make it fully offline by including Tailwind CSS locally instead of using a CDN.

## Contributing
I’m still learning, but I’d love help to improve this project! If you want to contribute:
1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Make your changes and commit (`git commit -m 'Add some feature'`).
4. Push to your branch (`git push origin feature/your-feature`).
5. Create a pull request.

Please make sure your code is clear and includes comments so I can understand it!

## License
This project is open-source and available under the [MIT License](LICENSE). Feel free to use it, modify it, or share it!

---

Thanks for checking out my Phone Book Application! If you have questions or find bugs, let me know. I’m still learning, so any feedback is super helpful! 😊