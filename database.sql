-- QuizMaster - Interactive Quiz App
-- Database: quizmaster

CREATE DATABASE IF NOT EXISTS quizmaster;
USE quizmaster;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(20) NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    question_text VARCHAR(255) NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('a', 'b', 'c', 'd') NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'easy',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    score INT NOT NULL,
    total_questions INT NOT NULL,
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Categories
INSERT INTO categories (name, color, description) VALUES
('HTML', '#e34c26', 'Structure and markup of web pages'),
('CSS', '#264de4', 'Styling, layout and design'),
('JavaScript', '#f7df1e', 'Interactivity and logic in the browser'),
('PHP', '#777bb3', 'Server side programming'),
('MySQL', '#00758f', 'Databases and SQL queries');

-- HTML questions
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES
(1, 'What does HTML stand for?', 'HyperText Markup Language', 'Hyperlinks and Text Markup Language', 'Home Tool Markup Language', 'HyperText Making Language', 'a', 'easy'),
(1, 'Which tag creates a hyperlink?', '<a>', '<link>', '<href>', '<url>', 'a', 'easy'),
(1, 'Which tag is used for the largest heading?', '<h1>', '<head>', '<h6>', '<heading>', 'a', 'easy'),
(1, 'Which attribute specifies the source of an image?', 'src', 'href', 'link', 'source', 'a', 'easy'),
(1, 'Which tag creates an unordered list?', '<ul>', '<ol>', '<li>', '<list>', 'a', 'medium');

-- CSS questions
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES
(2, 'What does CSS stand for?', 'Computer Styled Sections', 'Cascading Style Sheets', 'Creative Style System', 'Colorful Style Sheets', 'b', 'easy'),
(2, 'Which property changes the text color of an element?', 'font-color', 'text-color', 'color', 'font-style', 'c', 'easy'),
(2, 'Which symbol selects an element by its id?', '#', '.', '$', '*', 'a', 'easy'),
(2, 'Which property controls the space inside an element, around its content?', 'margin', 'padding', 'border', 'spacing', 'b', 'medium'),
(2, 'How do you include an external CSS file in a page?', 'With the <style> tag', 'With the <link> tag', 'With the <css> tag', 'With the <script> tag', 'b', 'medium');

-- JavaScript questions
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES
(3, 'Which keyword declares a block scoped variable?', 'var', 'let', 'int', 'define', 'b', 'easy'),
(3, 'What does the === operator check?', 'Value only', 'Type only', 'Value and type', 'Reference only', 'c', 'medium'),
(3, 'Which method adds an element to the end of an array?', 'pop()', 'shift()', 'push()', 'unshift()', 'c', 'easy'),
(3, 'What does DOM stand for?', 'Document Object Model', 'Data Object Method', 'Document Order Model', 'Display Object Management', 'a', 'medium'),
(3, 'Which function runs code once after a delay?', 'setInterval()', 'setTimeout()', 'delay()', 'wait()', 'b', 'medium');

-- PHP questions
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES
(4, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Hosting Platform', 'Pre Hypertext Processor', 'b', 'easy'),
(4, 'Which symbol starts a PHP variable?', '$', '#', '@', '%', 'a', 'easy'),
(4, 'Which function outputs text in PHP?', 'print_r() only', 'echo', 'console.log()', 'document.write()', 'b', 'easy'),
(4, 'Which superglobal holds form data sent with the POST method?', '$_GET', '$_POST', '$_SESSION', '$_SERVER', 'b', 'medium'),
(4, 'How does a PHP code block start?', '<?php', '<php>', '<script php>', '<?', 'a', 'easy');

-- MySQL questions
INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_option, difficulty) VALUES
(5, 'Which statement retrieves data from a table?', 'GET', 'FETCH', 'SELECT', 'OPEN', 'c', 'easy'),
(5, 'Which clause filters the rows returned by a query?', 'WHERE', 'FILTER', 'HAVING', 'IF', 'a', 'easy'),
(5, 'What does a PRIMARY KEY do?', 'Speeds up text search', 'Uniquely identifies each row', 'Links two tables', 'Stores images', 'b', 'medium'),
(5, 'Which statement adds a new row to a table?', 'ADD ROW', 'INSERT INTO', 'APPEND', 'CREATE ROW', 'b', 'easy'),
(5, 'Which clause sorts the results of a query?', 'SORT BY', 'ORDER BY', 'GROUP BY', 'ARRANGE BY', 'b', 'medium');

-- Sample quiz results
INSERT INTO results (player_name, category_id, score, total_questions) VALUES
('Hammad Khan', 3, 5, 5),
('Hammad Khan', 4, 4, 5),
('Hammad Khan', 1, 5, 5),
('Sara Ahmed', 5, 4, 5),
('Ali Raza', 2, 3, 5),
('Fatima Noor', 3, 4, 5),
('Usman Tariq', 1, 3, 5),
('Bilal Ahmed', 4, 4, 5);
