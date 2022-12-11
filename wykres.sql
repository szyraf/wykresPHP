-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Paź 2022, 00:34
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `wykres`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wykres`
--

CREATE TABLE `wykres` (
  `id` int(11) NOT NULL,
  `stan` varchar(100) NOT NULL,
  `temp` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `wykres`
--

INSERT INTO `wykres` (`id`, `stan`, `temp`) VALUES
(1, 'pomiar', 36.4),
(2, 'pomiar', 36.3),
(3, 'pomiar', 36.2),
(4, 'pomiar', 36.3),
(5, 'pomiar', 36.4),
(6, 'pomiar', 36.35),
(7, 'pomiar', 36.4),
(8, 'pomiar', 36.2),
(9, 'pomiar', 36.4),
(10, 'pomiar', 36.3),
(11, 'pomiar', 36.2),
(12, 'pomiar', 36.7),
(13, 'pomiar', 36.1),
(14, 'pomiar', 36.6),
(15, 'pomiar', 36.6),
(16, 'pomiar', 36.7),
(17, 'pomiar', 36.6),
(18, 'pomiar', 36.7),
(19, 'pomiar', 36.7),
(20, 'brak', 0),
(21, 'choroba', 0),
(22, 'choroba', 0),
(23, 'brak', 0),
(24, 'brak', 0),
(25, 'brak', 0),
(26, 'pomiar', 36.5),
(27, 'pomiar', 36.6),
(28, 'pomiar', 36.6),
(29, 'pomiar', 36.6),
(30, 'pomiar', 36.6),
(31, 'pomiar', 36.6);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `wykres`
--
ALTER TABLE `wykres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `wykres`
--
ALTER TABLE `wykres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
