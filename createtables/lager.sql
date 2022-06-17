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
-- Tabellenstruktur für Tabelle `lager`
--

CREATE TABLE `lager` (
  `bestand` decimal(7,0) DEFAULT 0,
  `mid` char(7) NOT NULL,
  `ghersteller` varchar(50) NOT NULL,
  `gname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `lager`
--

INSERT INTO `lager` (`bestand`, `mid`, `ghersteller`, `gname`) VALUES
('10000', '100', 'Alasia', 'Medium'),
('10000', '100', 'Alasia', 'Still'),
('10000', '100', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '100', 'Coca Cola', 'Cola'),
('10000', '100', 'Coca Cola', 'Cola Zero'),
('10000', '100', 'Coca Cola', 'Fanta'),
('10000', '100', 'Coca Cola', 'Sprite'),
('10000', '100', 'Coca Cola', 'Vio'),
('10000', '100', 'Coke', 'Sprite'),
('10000', '100', 'Dr. Pepper', 'Cola'),
('10000', '100', 'Erdinger', 'Lager'),
('10000', '100', 'Frucade', 'Orange'),
('10000', '100', 'Frucade', 'Zitrone'),
('10000', '100', 'Paulaner', 'Radler'),
('10000', '100', 'Paulaner', 'Weissbier'),
('10000', '100', 'Pepsi', 'Cola'),
('10000', '100', 'Vita', 'Cola'),
('10000', '101', 'Alasia', 'Medium'),
('10000', '101', 'Alasia', 'Still'),
('9999999', '101', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '101', 'Coca Cola', 'Cola'),
('10000', '101', 'Coca Cola', 'Cola Zero'),
('10000', '101', 'Coca Cola', 'Fanta'),
('10000', '101', 'Coca Cola', 'Sprite'),
('10000', '101', 'Coca Cola', 'Vio'),
('10000', '101', 'Coke', 'Sprite'),
('10000', '101', 'Dr. Pepper', 'Cola'),
('10000', '101', 'Erdinger', 'Lager'),
('10000', '101', 'Frucade', 'Orange'),
('10000', '101', 'Frucade', 'Zitrone'),
('10000', '101', 'Paulaner', 'Radler'),
('10000', '101', 'Paulaner', 'Weissbier'),
('10000', '101', 'Pepsi', 'Cola'),
('10000', '101', 'Vita', 'Cola'),
('10000', '102', 'Alasia', 'Medium'),
('10000', '102', 'Alasia', 'Still'),
('10000', '102', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '102', 'Coca Cola', 'Cola'),
('10000', '102', 'Coca Cola', 'Cola Zero'),
('10000', '102', 'Coca Cola', 'Fanta'),
('10000', '102', 'Coca Cola', 'Sprite'),
('10000', '102', 'Coca Cola', 'Vio'),
('10000', '102', 'Coke', 'Sprite'),
('10000', '102', 'Dr. Pepper', 'Cola'),
('10000', '102', 'Erdinger', 'Lager'),
('10000', '102', 'Frucade', 'Orange'),
('10000', '102', 'Frucade', 'Zitrone'),
('10000', '102', 'Paulaner', 'Radler'),
('10000', '102', 'Paulaner', 'Weissbier'),
('10000', '102', 'Pepsi', 'Cola'),
('10000', '102', 'Vita', 'Cola'),
('10000', '103', 'Alasia', 'Medium'),
('10000', '103', 'Alasia', 'Still'),
('10000', '103', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '103', 'Coca Cola', 'Cola'),
('10000', '103', 'Coca Cola', 'Cola Zero'),
('10000', '103', 'Coca Cola', 'Fanta'),
('10000', '103', 'Coca Cola', 'Sprite'),
('10000', '103', 'Coca Cola', 'Vio'),
('10000', '103', 'Coke', 'Sprite'),
('10000', '103', 'Dr. Pepper', 'Cola'),
('10000', '103', 'Erdinger', 'Lager'),
('10000', '103', 'Frucade', 'Orange'),
('10000', '103', 'Frucade', 'Zitrone'),
('10000', '103', 'Paulaner', 'Radler'),
('10000', '103', 'Paulaner', 'Weissbier'),
('10000', '103', 'Pepsi', 'Cola'),
('10000', '103', 'Vita', 'Cola'),
('10000', '97', 'Alasia', 'Medium'),
('10000', '97', 'Alasia', 'Still'),
('10000', '97', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '97', 'Coca Cola', 'Cola'),
('10000', '97', 'Coca Cola', 'Cola Zero'),
('10000', '97', 'Coca Cola', 'Fanta'),
('10000', '97', 'Coca Cola', 'Sprite'),
('10000', '97', 'Coca Cola', 'Vio'),
('10000', '97', 'Coke', 'Sprite'),
('10000', '97', 'Dr. Pepper', 'Cola'),
('10000', '97', 'Erdinger', 'Lager'),
('10000', '97', 'Frucade', 'Orange'),
('10000', '97', 'Frucade', 'Zitrone'),
('10000', '97', 'Paulaner', 'Radler'),
('10000', '97', 'Paulaner', 'Weissbier'),
('10000', '97', 'Pepsi', 'Cola'),
('10000', '97', 'Vita', 'Cola'),
('10000', '98', 'Alasia', 'Medium'),
('10000', '98', 'Alasia', 'Still'),
('10000', '98', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '98', 'Coca Cola', 'Cola'),
('10000', '98', 'Coca Cola', 'Cola Zero'),
('10000', '98', 'Coca Cola', 'Fanta'),
('10000', '98', 'Coca Cola', 'Sprite'),
('10000', '98', 'Coca Cola', 'Vio'),
('10000', '98', 'Coke', 'Sprite'),
('10000', '98', 'Dr. Pepper', 'Cola'),
('10000', '98', 'Erdinger', 'Lager'),
('10000', '98', 'Frucade', 'Orange'),
('10000', '98', 'Frucade', 'Zitrone'),
('10000', '98', 'Paulaner', 'Radler'),
('10000', '98', 'Paulaner', 'Weissbier'),
('10000', '98', 'Pepsi', 'Cola'),
('10000', '98', 'Vita', 'Cola'),
('10000', '99', 'Alasia', 'Medium'),
('10000', '99', 'Alasia', 'Still'),
('10000', '99', 'Albi', 'Orange mit Fruchtfleisch'),
('10000', '99', 'Coca Cola', 'Cola'),
('10000', '99', 'Coca Cola', 'Cola Zero'),
('10000', '99', 'Coca Cola', 'Fanta'),
('10000', '99', 'Coca Cola', 'Sprite'),
('10000', '99', 'Coca Cola', 'Vio'),
('10000', '99', 'Coke', 'Sprite'),
('10000', '99', 'Dr. Pepper', 'Cola'),
('10000', '99', 'Erdinger', 'Lager'),
('10000', '99', 'Frucade', 'Orange'),
('10000', '99', 'Frucade', 'Zitrone'),
('10000', '99', 'Paulaner', 'Radler'),
('10000', '99', 'Paulaner', 'Weissbier'),
('10000', '99', 'Pepsi', 'Cola'),
('10000', '99', 'Vita', 'Cola');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `lager`
--
ALTER TABLE `lager`
  ADD PRIMARY KEY (`mid`,`ghersteller`,`gname`),
  ADD KEY `drei` (`ghersteller`,`gname`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `lager`
--
ALTER TABLE `lager`
  ADD CONSTRAINT `drei` FOREIGN KEY (`ghersteller`,`gname`) REFERENCES `getraenke` (`ghersteller`, `gname`),
  ADD CONSTRAINT `hallo` FOREIGN KEY (`mid`) REFERENCES `markt` (`mid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
