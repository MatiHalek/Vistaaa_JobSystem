-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 20, 2024 at 06:13 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vistaaa`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `advertisement`
--

CREATE TABLE `advertisement` (
  `advertisement_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `position_name` varchar(50) NOT NULL,
  `position_level` varchar(50) NOT NULL,
  `contract_type` enum('Umowa o pracę','Umowa zlecenie','Umowa o dzieło') NOT NULL,
  `employment_type` enum('Pełny etat','Pół etatu','1/4 etatu','3/4 etatu','Staż','Praktyki','Wolontariat') NOT NULL,
  `work_type` enum('Zdalna','Stacjonarna','Hybrydowa') NOT NULL,
  `salary_lowest` decimal(8,2) DEFAULT NULL,
  `salary_highest` decimal(8,2) NOT NULL,
  `work_days` varchar(50) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_expiration` datetime NOT NULL,
  `responsibilities` text NOT NULL,
  `requirements` text NOT NULL,
  `offer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `advertisement`
--

INSERT INTO `advertisement` (`advertisement_id`, `title`, `company_id`, `position_name`, `position_level`, `contract_type`, `employment_type`, `work_type`, `salary_lowest`, `salary_highest`, `work_days`, `date_added`, `date_expiration`, `responsibilities`, `requirements`, `offer`) VALUES
(1, 'Programista', 1, 'programista', 'starszy programista', 'Umowa zlecenie', 'Pełny etat', 'Zdalna', 2000.00, 4000.00, 'poniedziałek - piątek 8:00-16:00', '2024-04-17 17:31:21', '2024-04-17 17:31:21', 'test', 'test', 'test'),
(2, 'nowa23', 1, 'programista', 'starszy programista', 'Umowa o dzieło', '1/4 etatu', 'Hybrydowa', 1876.00, 3999.99, 'pn - pt 8:00-13:00\r\nsb 8:00-12:00aaa', '2024-05-05 00:48:19', '2024-06-01 23:27:00', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\r\nxD', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\r\nlol\r\nehhhh', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\r\nwtf'),
(5, 'Frontend developer', 2, 'Programista', 'Junior developer', 'Umowa zlecenie', '3/4 etatu', 'Hybrydowa', 4000.00, 6000.00, 'pn-pt 8:00-12:00', '2024-05-20 04:38:42', '2024-05-31 04:38:00', 'obowiązek1\r\nobowiązek2\r\nobowiązek3', 'wymaganie1\r\nwymaganie2', 'oferta1\r\noferta2\r\noferta3'),
(6, 'ogłoszenie - produkcja', 1, 'producent', 'test', 'Umowa o dzieło', 'Pół etatu', 'Zdalna', 5000.00, 7867.00, 'pn - pt 8:00-15:00', '2024-05-20 05:58:31', '2024-06-07 05:57:00', 'obowiązek1\r\nobowiązek2\r\nobowiązek3', 'wymag1\r\nwymag2\r\nwydłużenie', 'byle co\r\ntest\r\nez\r\nhaha'),
(7, 'Jazda', 2, 'kierowca', 'przewóz towarów', 'Umowa zlecenie', '1/4 etatu', 'Zdalna', 5000.00, 10000.00, 'zależnie', '2024-05-20 06:01:04', '2024-06-07 06:01:00', 'aaaaaaaaaaaaaaaaaaaaaa\r\naaaaaaaaaaaaaaaaaaaaaaaa\r\naaaaaaaaaaaaaaaaaaaaa', 'bbbbbbbbbbbbbbbbbbbb\r\nbbbbbbbbbbbbbbbbbbbbbb\r\nbbbbbbbbbbbbbbbbb', 'cccccccccccccccccc\r\ncccccccccccccccc\r\ncccccccccccccccccccccccc'),
(8, 'ostatnia', 1, 'przykład', 'reklamowanie usług', 'Umowa zlecenie', '3/4 etatu', 'Zdalna', 2000.00, 5000.00, 'pn - pt 8:00-12:00\r\nsb 8:00 - 17:00', '2024-05-20 06:02:44', '2024-05-30 06:02:00', 'aaaaaaaaaaaaaa\r\naaaaaaaaaaaaaa\r\naaaaaaaaaaaaaaaaaa', 'xDDDDDDDD\r\nxDDDDDDDDD\r\nxDDDDDDDD', 'oferta pracodawcy\r\noferta jakaś inna\r\nkoniec esa'),
(9, 'Profesjonalne ogłoszenie', 1, 'Programista', 'senior developer', 'Umowa zlecenie', '3/4 etatu', 'Hybrydowa', 7000.00, 15000.00, 'pn - pt 15:00 - 23:00', '2024-05-20 06:08:11', '2024-09-30 06:07:00', 'Biegła znajomość języka programowania Python oraz doświadczenie w jego praktycznym wykorzystaniu.\r\nUmiejętność pracy z bazami danych, takimi jak MySQL, PostgreSQL lub MongoDB.\r\nZnajomość systemów kontroli wersji, takich jak Git.\r\nZdolność do pracy w zespole oraz efektywnej komunikacji.\r\nMinimum 3 lata doświadczenia na podobnym stanowisku.', 'Tworzenie i utrzymywanie wysokiej jakości kodu zgodnie z najlepszymi praktykami programistycznymi.\r\nWspółpraca z zespołem projektowym nad opracowywaniem nowych funkcjonalności.\r\nAnalizowanie i rozwiązywanie problemów zgłaszanych przez użytkowników.\r\nOptymalizacja istniejących aplikacji pod kątem wydajności i bezpieczeństwa.\r\nUdział w spotkaniach zespołowych oraz raportowanie postępów prac.', 'Atrakcyjne wynagrodzenie oraz system premiowy.\r\nElastyczne godziny pracy oraz możliwość pracy zdalnej.\r\nPrywatna opieka medyczna oraz pakiet sportowy.\r\nDostęp do szkoleń i kursów podnoszących kwalifikacje.\r\nPrzyjazna atmosfera pracy w dynamicznie rozwijającej się firmie.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `advertisement_category`
--

CREATE TABLE `advertisement_category` (
  `advertisement_category_id` int(10) UNSIGNED NOT NULL,
  `advertisement_id` int(10) UNSIGNED NOT NULL,
  `category_id` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `advertisement_category`
--

INSERT INTO `advertisement_category` (`advertisement_category_id`, `advertisement_id`, `category_id`) VALUES
(1, 1, 1),
(15, 2, 1),
(16, 5, 1),
(17, 6, 2),
(18, 6, 4),
(19, 7, 4),
(20, 7, 5),
(21, 8, 2),
(22, 9, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE `category` (
  `category_id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'Programowanie'),
(2, 'Marketing'),
(3, 'Prawo'),
(4, 'Produkcja'),
(5, 'Transport');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `company`
--

CREATE TABLE `company` (
  `company_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(244) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `email`, `password`, `name`, `description`, `street`, `number`, `city`, `postcode`) VALUES
(1, 'vistaaa_advertising@outlook.com', '$2y$10$nZ0vnDlPKwlQM/a5.QvaW.Lyf1Q434IH25PRYx7M0Q11q38NNweGq', 'Vistaaa Sp. z o.o.', 'test', 'Zielona', '2', 'Limanowa', '34-600'),
(2, 'mh@op.pl', '$2y$10$aUCWiOll7WS7PERVcT3ftOrSYiI2Af9ndl4TIpO1bllP9xx.9wp5O', 'MH S.A.', NULL, 'Zielona', '5', 'Limanowa', '34-600');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `language`
--

CREATE TABLE `language` (
  `language_id` smallint(5) UNSIGNED NOT NULL,
  `language` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language`) VALUES
(1, 'angielski'),
(2, 'niemiecki'),
(3, 'francuski'),
(4, 'włoski');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `portal`
--

CREATE TABLE `portal` (
  `portal_id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `portal`
--

INSERT INTO `portal` (`portal_id`, `name`) VALUES
(1, 'GitHub'),
(2, 'LinkedIn');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `school`
--

CREATE TABLE `school` (
  `school_id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `school`
--

INSERT INTO `school` (`school_id`, `name`, `city`) VALUES
(1, 'Szkoła Podstawowa nr 3', 'Limanowa'),
(2, 'Zespół Szkół Technicznych i Ogólnokształcących', 'Limanowa'),
(3, 'Szkoła Podstawowa nr 2', 'Kraków');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `home_number` varchar(10) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `surname`, `email`, `password`, `date_of_birth`, `phone`, `street`, `home_number`, `city`, `postcode`, `position`, `experience`, `is_admin`) VALUES
(1, 'Mateusz', 'Marmuźniak', 'mateusz.marmuzniak.poland@gmail.com', '$2y$10$nZ0vnDlPKwlQM/a5.QvaW.Lyf1Q434IH25PRYx7M0Q11q38NNweGq', '2005-02-07', '123456789', 'Zielona', '52', 'Limanowa', '34-600', 'Programista', 'Jestem programistą od X lat', 1),
(3, 'aaa', 'aaa', 'matimarmuzniak@gmail.com', '$2y$10$kFe47sL4xtSow32gHPGcqumeV.2B8rPeaRihGdgW8JZy0/dBtTmY6', '2005-02-07', NULL, 'Zielona', '19', 'Limanowa', '34-600', 'aaaaaa', 'aaaaaaaaaaabbb', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_applied`
--

CREATE TABLE `user_applied` (
  `user_applied_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `advertisement_id` int(10) UNSIGNED NOT NULL,
  `applied_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_applied`
--

INSERT INTO `user_applied` (`user_applied_id`, `user_id`, `advertisement_id`, `applied_date`) VALUES
(14, 1, 2, '2024-05-18 04:29:57'),
(15, 1, 5, '2024-05-20 04:59:26'),
(16, 3, 5, '2024-05-20 05:06:08');

--
-- Wyzwalacze `user_applied`
--
DELIMITER $$
CREATE TRIGGER `add_applied_date` BEFORE INSERT ON `user_applied` FOR EACH ROW SET NEW.applied_date = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_course`
--

CREATE TABLE `user_course` (
  `user_course` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `company_id` int(11) UNSIGNED NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_course`
--

INSERT INTO `user_course` (`user_course`, `user_id`, `name`, `company_id`, `date_start`, `date_end`) VALUES
(36, 1, 'Kurs językowy', 1, '2019-09-01', NULL),
(37, 1, 'Programowanie w C++', 1, '2016-12-31', '2019-05-13'),
(38, 1, 'test', 1, '2024-05-02', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_education`
--

CREATE TABLE `user_education` (
  `user_education_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `school_id` mediumint(8) UNSIGNED NOT NULL,
  `level` enum('podstawowe','średnie','wyższe') NOT NULL,
  `field` varchar(50) DEFAULT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_education`
--

INSERT INTO `user_education` (`user_education_id`, `user_id`, `school_id`, `level`, `field`, `date_start`, `date_end`) VALUES
(53, 1, 1, 'podstawowe', NULL, '2014-09-01', '2020-06-26'),
(54, 1, 2, 'średnie', 'programista', '2020-09-01', NULL),
(55, 1, 3, 'wyższe', 'aaa', '2024-05-03', '2024-05-02');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_language`
--

CREATE TABLE `user_language` (
  `user_language_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `language_id` smallint(5) UNSIGNED NOT NULL,
  `level` enum('podstawowy','średniozaawansowany','zaawansowany','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_language`
--

INSERT INTO `user_language` (`user_language_id`, `user_id`, `language_id`, `level`) VALUES
(69, 1, 1, 'średniozaawansowany'),
(70, 1, 2, 'podstawowy'),
(71, 1, 4, 'podstawowy');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_link`
--

CREATE TABLE `user_link` (
  `user_link_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `portal_id` smallint(6) UNSIGNED NOT NULL,
  `link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_link`
--

INSERT INTO `user_link` (`user_link_id`, `user_id`, `portal_id`, `link`) VALUES
(51, 1, 1, 'https://google.com');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_position`
--

CREATE TABLE `user_position` (
  `user_position_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `position` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_position`
--

INSERT INTO `user_position` (`user_position_id`, `user_id`, `company_id`, `position`, `description`, `date_start`, `date_end`) VALUES
(81, 1, 1, 'Informatyk', NULL, '2024-04-01', '2024-04-15'),
(82, 1, 1, 'ez', NULL, '2024-05-16', NULL),
(85, 3, 1, 'aaaa', NULL, '2024-05-10', '2024-05-10');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_saved`
--

CREATE TABLE `user_saved` (
  `user_saved_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `advertisement_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `user_saved`
--

INSERT INTO `user_saved` (`user_saved_id`, `user_id`, `advertisement_id`) VALUES
(20, 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_skill`
--

CREATE TABLE `user_skill` (
  `user_skill_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `skill` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `advertisement`
--
ALTER TABLE `advertisement`
  ADD PRIMARY KEY (`advertisement_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indeksy dla tabeli `advertisement_category`
--
ALTER TABLE `advertisement_category`
  ADD PRIMARY KEY (`advertisement_category_id`),
  ADD KEY `advertisement_id` (`advertisement_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksy dla tabeli `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeksy dla tabeli `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indeksy dla tabeli `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`);

--
-- Indeksy dla tabeli `portal`
--
ALTER TABLE `portal`
  ADD PRIMARY KEY (`portal_id`);

--
-- Indeksy dla tabeli `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`school_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksy dla tabeli `user_applied`
--
ALTER TABLE `user_applied`
  ADD PRIMARY KEY (`user_applied_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `advertisement_id` (`advertisement_id`);

--
-- Indeksy dla tabeli `user_course`
--
ALTER TABLE `user_course`
  ADD PRIMARY KEY (`user_course`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user_education`
--
ALTER TABLE `user_education`
  ADD PRIMARY KEY (`user_education_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indeksy dla tabeli `user_language`
--
ALTER TABLE `user_language`
  ADD PRIMARY KEY (`user_language_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indeksy dla tabeli `user_link`
--
ALTER TABLE `user_link`
  ADD PRIMARY KEY (`user_link_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `portal_id` (`portal_id`);

--
-- Indeksy dla tabeli `user_position`
--
ALTER TABLE `user_position`
  ADD PRIMARY KEY (`user_position_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indeksy dla tabeli `user_saved`
--
ALTER TABLE `user_saved`
  ADD PRIMARY KEY (`user_saved_id`),
  ADD KEY `advertisement_id` (`advertisement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user_skill`
--
ALTER TABLE `user_skill`
  ADD PRIMARY KEY (`user_skill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisement`
--
ALTER TABLE `advertisement`
  MODIFY `advertisement_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `advertisement_category`
--
ALTER TABLE `advertisement_category`
  MODIFY `advertisement_category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `portal`
--
ALTER TABLE `portal`
  MODIFY `portal_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `school_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_applied`
--
ALTER TABLE `user_applied`
  MODIFY `user_applied_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_course`
--
ALTER TABLE `user_course`
  MODIFY `user_course` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `user_education`
--
ALTER TABLE `user_education`
  MODIFY `user_education_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user_language`
--
ALTER TABLE `user_language`
  MODIFY `user_language_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user_link`
--
ALTER TABLE `user_link`
  MODIFY `user_link_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_position`
--
ALTER TABLE `user_position`
  MODIFY `user_position_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user_saved`
--
ALTER TABLE `user_saved`
  MODIFY `user_saved_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_skill`
--
ALTER TABLE `user_skill`
  MODIFY `user_skill_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advertisement`
--
ALTER TABLE `advertisement`
  ADD CONSTRAINT `advertisement_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `advertisement_category`
--
ALTER TABLE `advertisement_category`
  ADD CONSTRAINT `advertisement_category_ibfk_1` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisement` (`advertisement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `advertisement_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_applied`
--
ALTER TABLE `user_applied`
  ADD CONSTRAINT `user_applied_ibfk_1` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisement` (`advertisement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_applied_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_course`
--
ALTER TABLE `user_course`
  ADD CONSTRAINT `user_course_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_course_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_education`
--
ALTER TABLE `user_education`
  ADD CONSTRAINT `user_education_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_education_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_language`
--
ALTER TABLE `user_language`
  ADD CONSTRAINT `user_language_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_link`
--
ALTER TABLE `user_link`
  ADD CONSTRAINT `user_link_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_link_ibfk_2` FOREIGN KEY (`portal_id`) REFERENCES `portal` (`portal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_position`
--
ALTER TABLE `user_position`
  ADD CONSTRAINT `user_position_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_position_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_saved`
--
ALTER TABLE `user_saved`
  ADD CONSTRAINT `user_saved_ibfk_1` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisement` (`advertisement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_saved_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_skill`
--
ALTER TABLE `user_skill`
  ADD CONSTRAINT `user_skill_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
