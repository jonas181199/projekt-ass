-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2022 um 11:37
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `getraenkeshop_ass`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `getraenke`
--

CREATE TABLE `getraenke` (
  `ghersteller` varchar(50) NOT NULL,
  `gname` varchar(30) NOT NULL,
  `kategorie` enum('Wasser','Saft','Limonade','Wein','Bier','Sonstiges') DEFAULT 'Sonstiges',
  `preis` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `getraenke`
--

INSERT INTO `getraenke` (`ghersteller`, `gname`, `kategorie`, `preis`) VALUES
('Alasia', 'Medium', 'Wasser', '0.80'),
('Alasia', 'Still ', 'Wasser', '0.70'),
('Albi', 'Orange mit Fruchtfleisch', 'Saft', '2.00'),
('Coca Cola', 'Cola', 'Limonade', '1.00'),
('Coca Cola', 'Cola evev', 'Wasser', '1.00'),
('Coca Cola', 'Cola Zero', 'Limonade', '1.20'),
('Coca Cola', 'Fanta', 'Limonade', '0.60'),
('Coca Cola', 'Sprite', 'Limonade', '1.90'),
('Coca Cola', 'Vio', 'Wasser', '0.80'),
('Coke', 'Sprite', 'Limonade', '1.30'),
('cola', 'coca cola', 'Limonade', '1.00'),
('cola', 'vita', 'Bier', '1.00'),
('Dr. Pepper', 'Cola', 'Wasser', '1.00'),
('Erdinger', 'Lager', 'Bier', '0.70'),
('Frucade', 'Orange', 'Limonade', '0.90'),
('Frucade', 'Zitrone', 'Wasser', '0.80'),
('Granini', 'Die Limo', 'Limonade', '0.90'),
('Neunknsa', 'cola', 'Limonade', '1.00'),
('Neunknsa', 'colaaa', 'Limonade', '1.00'),
('Paulaner', 'Radler', 'Bier', '0.70'),
('Paulaner', 'Weissbier', 'Bier', '0.70'),
('Pepsi', 'Cola', 'Limonade', '1.10'),
('Pepsi', 'Cola Max', 'Saft', '1.00'),
('Pepsi', 'Fanta', 'Limonade', '1.00'),
('ssss', 'Sprite', 'Wasser', '1.00'),
('veve', 'brbrbnhbrh', 'Limonade', '1.00'),
('Vita', 'Cola', 'Limonade', '1.20'),
('vnldkvd', 'jwlkvjwl', 'Wein', '2.00'),
('vola', 'xwcwc', 'Saft', '1.00'),
('xw', 'ss', 'Limonade', '1.00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `getraenke`
--
ALTER TABLE `getraenke`
  ADD PRIMARY KEY (`ghersteller`,`gname`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
