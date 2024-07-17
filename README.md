# Parchment



https://github.com/user-attachments/assets/2282f1d2-2ea8-4d99-801f-bcf5bd1df0d4




Parchment is a full-stack web application designed to manage student flashcards. This project leverages PHP, HTML, CSS, JavaScript, MySQL, phpMyAdmin, PHPUnit, and Selenium to create a collaborative platform where users can create, share, and study flashcards.

## Features

- User authentication
- Deck and flashcard management
- Progress tracking
- Collaborative environment for sharing and accessing decks

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP, MySQL
- **Database Management**: phpMyAdmin
- **Testing**: PHPUnit, Selenium

## Getting Started

### Prerequisites

- PHP >= 7.4
- MySQL
- Apache or Nginx server
- Composer (for PHP dependencies)
- phpMyAdmin (optional, for database management)

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/mertcanvural/Parchment.git
   cd Parchment
   ```

2. **Set up the backend**:
   - Ensure you have PHP and MySQL installed.
   - Import the database schema using `Parchment.sql` file located in the root directory:
     ```bash
     mysql -u your_username -p your_database < Parchment.sql
     ```
   - Update the database configuration in `backend/config/db.php`.

3. **Install PHP dependencies**:
   ```bash
   composer install
   ```


4. **Run the application**:
   - Start your PHP server:
     ```bash
     php -S localhost:8000 -t backend/public
     ```
   - Start the frontend development server:
     ```bash
     cd frontend
     npm start
     ```

5. **Access the application**:
   Open your browser and navigate to `http://localhost:8000` to view the application.

## Contributing

Contributions are welcome! Please fork the repository and create a pull request with your changes. Make sure to follow the code style and include tests for any new features or bug fixes.

