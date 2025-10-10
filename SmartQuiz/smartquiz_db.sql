-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 10, 2025 at 03:56 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartquiz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'What is the capital of France?', 'Paris', 'London', 'Berlin', 'Madrid', 'A'),
(2, 1, 'Which planet is known as the Red Planet?', 'Earth', 'Mars', 'Jupiter', 'Venus', 'B'),
(3, 1, 'Who wrote the play Romeo and Juliet?', 'William Shakespeare', 'Charles Dickens', 'Mark Twain', 'Leo Tolstoy', 'A'),
(4, 1, 'Which ocean is the largest?', 'Atlantic', 'Indian', 'Pacific', 'Arctic', 'C'),
(5, 1, 'Which is the tallest mountain in the world?', 'K2', 'Everest', 'Kanchenjunga', 'Lhotse', 'B'),
(6, 1, 'Which country gifted the Statue of Liberty to USA?', 'France', 'UK', 'Germany', 'Italy', 'A'),
(7, 1, 'Which element has the chemical symbol O?', 'Oxygen', 'Gold', 'Iron', 'Hydrogen', 'A'),
(8, 1, 'Which year did the first man land on the moon?', '1965', '1969', '1971', '1967', 'B'),
(9, 1, 'Which is the fastest land animal?', 'Cheetah', 'Lion', 'Tiger', 'Leopard', 'A'),
(10, 1, 'Which instrument measures atmospheric pressure?', 'Thermometer', 'Barometer', 'Hygrometer', 'Anemometer', 'B'),
(11, 2, 'Which language is primarily used for web development?', 'Python', 'HTML', 'JavaScript', 'C++', 'C'),
(12, 2, 'What does HTML stand for?', 'Hypertext Markup Language', 'Hyperlinks and Text Markup Language', 'Home Tool Markup Language', 'Hyperlinking Text Mark Language', 'A'),
(13, 2, 'Which of these is a JavaScript framework?', 'React', 'Laravel', 'Django', 'Flask', 'A'),
(14, 2, 'What symbol is used to end a statement in PHP?', 'Period', 'Comma', 'Semicolon', 'Colon', 'C'),
(15, 2, 'Which company developed the Java programming language?', 'Sun Microsystems', 'Microsoft', 'Google', 'IBM', 'A'),
(16, 2, 'Which language is used for styling web pages?', 'HTML', 'CSS', 'JavaScript', 'PHP', 'B'),
(17, 2, 'Which PHP function is used to connect to MySQL?', 'mysqli_connect()', 'mysql_open()', 'connect_db()', 'db_connect()', 'A'),
(18, 2, 'Which is a server-side scripting language?', 'JavaScript', 'CSS', 'PHP', 'HTML', 'C'),
(19, 2, 'What does SQL stand for?', 'Structured Query Language', 'Simple Query List', 'Statement Question Language', 'Standard Query Language', 'A'),
(20, 2, 'Which symbol is used for variables in PHP?', '@', '$', '#', '&', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text,
  `category` varchar(100) DEFAULT NULL,
  `time_limit` int DEFAULT '10',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `category`, `time_limit`, `created_by`, `created_at`) VALUES
(1, 'General Knowledge Quiz', 'Test your general knowledge across various topics.', 'General', 10, 1, '2025-10-07 10:48:30'),
(2, 'Programming Quiz', 'Check your programming skills and coding knowledge.', 'Programming', 15, 1, '2025-10-07 10:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int DEFAULT NULL,
  `total_questions` int DEFAULT NULL,
  `correct_answers` int DEFAULT NULL,
  `attempted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `correct_answers`, `attempted_at`) VALUES
(1, 1, 2, 4, 6, 4, '2025-10-07 10:34:46'),
(2, 1, 5, 0, 0, 0, '2025-10-07 10:37:40'),
(3, 1, 2, 9, 10, 9, '2025-10-07 10:50:08'),
(4, 2, 1, 3, 10, 3, '2025-10-07 11:23:19'),
(5, 2, 1, 3, 10, 3, '2025-10-07 11:49:29'),
(6, 2, 1, 2, 10, 2, '2025-10-07 12:33:58'),
(7, 2, 3, 0, 1, 0, '2025-10-07 12:59:48'),
(8, 3, 1, 6, 10, 6, '2025-10-07 13:56:36'),
(9, 3, 1, 5, 10, 5, '2025-10-08 04:39:37'),
(10, 3, 1, 3, 10, 3, '2025-10-10 15:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ashutosh', 'ashutosh72004@gmail.com', '$2y$10$4/BiYj6EheaZo/pdh.Dsy.MdKSK/ZT07saR9.W9fHkDo0eQG94Vka', 'admin', '2025-10-07 09:49:18'),
(2, 'Shruti', 'shruti@gmail.com', '$2y$10$JlzZpj2feWaJmqpqhEWGwu08D3/aBKEmwHDS7.L0U6AG8/pm.HHkW', 'user', '2025-10-07 10:11:27'),
(3, 'Ashutosh', 'ashutosh@gmail.com', '$2y$10$/gkSdhYaSh89kpPOAdzrguK5SZabzbmRqDggsRGa2i70wcqoXD09a', 'user', '2025-10-07 13:52:39');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
