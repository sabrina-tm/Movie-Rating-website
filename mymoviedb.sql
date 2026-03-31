-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 07:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mymoviedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `release_year` int(11) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `watch_status` enum('want_to_watch','currently_watching','watched') DEFAULT 'want_to_watch',
  `review_text` text DEFAULT NULL,
  `personal_notes` text DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `director`, `genre`, `release_year`, `duration`, `rating`, `watch_status`, `review_text`, `personal_notes`, `date_added`) VALUES
(1, 'The Shawshank Redemption', 'Frank Darabont', 'Drama', 1994, 142, 5, 'watched', 'An absolutely incredible movie about hope and friendship. Tim Robbins and Morgan Freeman deliver outstanding performances.', 'Rewatched this 3 times already. Never gets old!', '2025-06-05 13:19:25'),
(2, 'Inception', 'Christopher Nolan', 'Sci-Fi', 2010, 148, 5, 'watched', 'Mind-bending masterpiece! The concept of dreams within dreams is executed perfectly. Leonardo DiCaprio is fantastic.', 'Need to watch again to catch all the details I missed', '2025-06-05 13:19:25'),
(3, 'The Godfather', 'Francis Ford Coppola', 'Crime', 1972, 175, 5, 'watched', 'The ultimate crime saga. Marlon Brando is iconic as Vito Corleone. A masterclass in filmmaking.', 'Classic that defined the crime genre', '2025-06-05 13:19:25'),
(4, 'Pulp Fiction', 'Quentin Tarantino', 'Crime', 1994, 154, 4, 'watched', 'Tarantino\'s non-linear storytelling at its finest. Great dialogue and memorable characters.', 'Love the soundtrack too', '2025-06-05 13:19:25'),
(5, 'The Dark Knight', 'Christopher Nolan', 'Action', 2008, 152, 5, 'watched', 'Heath Ledger\'s Joker is absolutely phenomenal. Best superhero movie ever made.', 'RIP Heath Ledger - legendary performance', '2025-06-05 13:19:25'),
(6, 'Interstellar', 'Christopher Nolan', 'Sci-Fi', 2014, 169, 4, 'watched', 'Visually stunning with great emotional depth. The science is fascinating even if not always accurate.', 'The soundtrack by Hans Zimmer is incredible', '2025-06-05 13:19:25'),
(7, 'Parasite', 'Bong Joon-ho', 'Thriller', 2019, 132, 5, 'watched', 'Brilliant social commentary wrapped in a thrilling story. Well-deserved Oscar winner.', 'First foreign film to win Best Picture!', '2025-06-05 13:19:25'),
(8, 'Spider-Man: Into the Spider-Verse', 'Bob Persichetti', 'Animation', 2018, 117, 4, 'watched', 'Revolutionary animation style and great story. Miles Morales is a fantastic Spider-Man.', 'My favorite animated movie of recent years', '2025-06-05 13:19:25'),
(9, 'Dune', 'Denis Villeneuve', 'Sci-Fi', 2021, 155, 4, 'want_to_watch', NULL, 'Heard great things about this adaptation. Need to read the book first.', '2025-06-05 13:19:25'),
(10, 'Top Gun: Maverick', 'Joseph Kosinski', 'Action', 2022, 130, NULL, 'want_to_watch', NULL, 'Tom Cruise is back! Heard it\'s better than the original.', '2025-06-05 13:19:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
