-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2022 um 11:36
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
-- Tabellenstruktur für Tabelle `bestellpos`
--

CREATE TABLE `bestellpos` (
  `ganzahl` decimal(4,0) DEFAULT NULL,
  `position` decimal(3,0) NOT NULL,
  `bestellnr` char(12) NOT NULL,
  `ghersteller` varchar(50) DEFAULT NULL,
  `gname` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bestellpos`
--

INSERT INTO `bestellpos` (`ganzahl`, `position`, `bestellnr`, `ghersteller`, `gname`) VALUES
('2', '1', '1', 'Alasia', 'Medium'),
('2', '1', '10', 'Alasia', 'Medium'),
('2', '1', '11', 'Alasia', 'Medium'),
('2', '1', '12', 'Coca Cola', 'Cola'),
('5', '1', '13', 'Coca Cola', 'Sprite'),
('5', '1', '14', 'Dr. Pepper', 'Cola'),
('5', '1', '15', 'Paulaner', 'Radler'),
('5', '1', '16', 'Vita', 'Cola'),
('5', '1', '17', 'Paulaner', 'Radler'),
('5', '1', '18', 'Erdinger', 'Lager'),
('2', '1', '2', 'Coca Cola', 'Cola'),
('2', '1', '20', 'Alasia', 'Medium'),
('2', '1', '21', 'Alasia', 'Medium'),
('2', '1', '22', 'Coca Cola', 'Cola'),
('5', '1', '23', 'Coca Cola', 'Sprite'),
('5', '1', '24', 'Dr. Pepper', 'Cola'),
('5', '1', '25', 'Paulaner', 'Radler'),
('5', '1', '26', 'Vita', 'Cola'),
('5', '1', '27', 'Paulaner', 'Radler'),
('5', '1', '28', 'Erdinger', 'Lager'),
('5', '1', '3', 'Coca Cola', 'Sprite'),
('2', '1', '30', 'Alasia', 'Medium'),
('2', '1', '31', 'Alasia', 'Medium'),
('5', '1', '4', 'Dr. Pepper', 'Cola'),
('2', '1', '40', 'Alasia', 'Medium'),
('5', '1', '5', 'Paulaner', 'Radler'),
('5', '1', '6', 'Vita', 'Cola'),
('5', '1', '7', 'Paulaner', 'Radler'),
('5', '1', '8', 'Erdinger', 'Lager'),
('10', '2', '1', 'Alasia', 'Still'),
('10', '2', '10', 'Alasia', 'Still'),
('10', '2', '11', 'Alasia', 'Still'),
('5', '2', '12', 'Coca Cola', 'Cola Zero'),
('5', '2', '13', 'Coca Cola', 'Vio'),
('5', '2', '14', 'Erdinger', 'Lager'),
('5', '2', '15', 'Paulaner', 'Weissbier'),
('5', '2', '16', 'Pepsi', 'Cola'),
('5', '2', '17', 'Frucade', 'Orange'),
('5', '2', '18', 'Dr. Pepper', 'Cola'),
('5', '2', '2', 'Coca Cola', 'Cola Zero'),
('10', '2', '20', 'Alasia', 'Still'),
('10', '2', '21', 'Alasia', 'Still'),
('5', '2', '22', 'Coca Cola', 'Cola Zero'),
('5', '2', '23', 'Coca Cola', 'Vio'),
('5', '2', '24', 'Erdinger', 'Lager'),
('5', '2', '25', 'Paulaner', 'Weissbier'),
('5', '2', '26', 'Pepsi', 'Cola'),
('5', '2', '27', 'Frucade', 'Orange'),
('5', '2', '28', 'Dr. Pepper', 'Cola'),
('5', '2', '3', 'Coca Cola', 'Vio'),
('10', '2', '30', 'Alasia', 'Still'),
('10', '2', '31', 'Alasia', 'Still'),
('5', '2', '4', 'Erdinger', 'Lager'),
('10', '2', '40', 'Alasia', 'Still'),
('5', '2', '5', 'Paulaner', 'Weissbier'),
('5', '2', '6', 'Pepsi', 'Cola'),
('5', '2', '7', 'Frucade', 'Orange'),
('5', '2', '8', 'Dr. Pepper', 'Cola'),
('12', '3', '1', 'Albi', 'Orange mit Fruchtfleisch'),
('12', '3', '10', 'Albi', 'Orange mit Fruchtfleisch'),
('12', '3', '11', 'Albi', 'Orange mit Fruchtfleisch'),
('5', '3', '12', 'Coca Cola', 'Fanta'),
('5', '3', '13', 'Coke', 'Sprite'),
('5', '3', '14', 'Frucade', 'Orange'),
('5', '3', '15', 'Vita', 'Cola'),
('5', '3', '16', 'Paulaner', 'Weissbier'),
('5', '3', '2', 'Coca Cola', 'Fanta'),
('12', '3', '20', 'Albi', 'Orange mit Fruchtfleisch'),
('32', '3', '21', 'Albi', 'Orange mit Fruchtfleisch'),
('5', '3', '22', 'Coca Cola', 'Fanta'),
('5', '3', '23', 'Coke', 'Sprite'),
('5', '3', '24', 'Frucade', 'Orange'),
('5', '3', '25', 'Vita', 'Cola'),
('5', '3', '26', 'Paulaner', 'Weissbier'),
('5', '3', '3', 'Coke', 'Sprite'),
('32', '3', '30', 'Albi', 'Orange mit Fruchtfleisch'),
('32', '3', '31', 'Albi', 'Orange mit Fruchtfleisch'),
('5', '3', '4', 'Frucade', 'Orange'),
('32', '3', '40', 'Albi', 'Orange mit Fruchtfleisch'),
('5', '3', '5', 'Vita', 'Cola'),
('5', '3', '6', 'Paulaner', 'Weissbier'),
('5', '3', '7', 'Frucade', 'Zitrone'),
('5', '3', '8', 'Coke', 'Sprite'),
('5', '4', '4', 'Frucade', 'Zitrone'),
('5', '4', '5', 'Pepsi', 'Cola');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellpos`
--
ALTER TABLE `bestellpos`
  ADD PRIMARY KEY (`position`,`bestellnr`),
  ADD KEY `fk_best` (`bestellnr`),
  ADD KEY `fk_getraenkezwei` (`ghersteller`,`gname`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellpos`
--
ALTER TABLE `bestellpos`
  ADD CONSTRAINT `fk_best` FOREIGN KEY (`bestellnr`) REFERENCES `bestellung` (`bestellnr`),
  ADD CONSTRAINT `fk_getraenkezwei` FOREIGN KEY (`ghersteller`,`gname`) REFERENCES `getraenke` (`ghersteller`, `gname`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
