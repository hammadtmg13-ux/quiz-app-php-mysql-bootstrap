# QuizMaster - Interactive Quiz App

An interactive quiz app built with HTML, CSS, JavaScript, Bootstrap, MySQL and PHP. Pick a category, answer timed multiple choice questions and climb the leaderboard, all while your results are saved in MySQL.

## Features
- 5 quiz categories: HTML, CSS, JavaScript, PHP and MySQL
- Timed questions, 30 seconds each, with a visible countdown
- One question at a time with a progress bar and instant right or wrong feedback
- Final score screen with a message based on your result
- Results saved to MySQL, with player name, category, score and date
- Dashboard stats: quizzes taken, average score, best score and questions in bank
- Recent results table and a leaderboard ranked by best score
- Question bank manager: add, edit and delete questions, filter by category
- Three difficulty levels: easy, medium and hard

## Tech stack
- HTML and CSS
- JavaScript
- Bootstrap 5
- PHP with PDO
- MySQL

## Setup
1. Create the database and tables using `database.sql`:
```sql
mysql -u root -p < database.sql
```
2. Update the database details in `config.php` if needed.
3. Copy the project folder to your web server, for example `htdocs` for XAMPP.
4. Open `http://localhost/quiz-app-php-mysql/` in your browser.

## Database
Database name: `quizmaster`

Table: `categories`
- id: INT, auto increment, primary key
- name: VARCHAR(50)
- color: VARCHAR(20)
- description: VARCHAR(255)

Table: `questions`
- id: INT, auto increment, primary key
- category_id: INT, foreign key to categories
- question_text: VARCHAR(255)
- option_a, option_b, option_c, option_d: VARCHAR(255)
- correct_option: ENUM a, b, c, d
- difficulty: ENUM easy, medium, hard
- created_at: TIMESTAMP, default current timestamp

Table: `results`
- id: INT, auto increment, primary key
- player_name: VARCHAR(100)
- category_id: INT, foreign key to categories
- score: INT
- total_questions: INT
- taken_at: TIMESTAMP, default current timestamp

## Project structure
- `index.php` - dashboard, stats, categories, recent results and leaderboard
- `quiz.php` - quiz runner with timer, progress bar and score screen
- `save_result.php` - saves a finished quiz result to the database
- `manage_questions.php` - question bank list with add form and category filter
- `add_question.php` - handles adding a question
- `edit_question.php` - handles editing a question
- `delete_question.php` - deletes a question
- `config.php` - MySQL connection with PDO
- `database.sql` - database and table setup with 5 categories, 25 sample questions and 8 sample results
- `assets/css/style.css` - custom styles
- `assets/js/script.js` - quiz timer, question flow and scoring

## Author
Built by Hammad Khan
