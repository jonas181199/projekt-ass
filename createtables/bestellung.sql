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
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `bestellnr` char(12) NOT NULL,
  `bestdatum` date DEFAULT curtime(),
  `email` varchar(70) DEFAULT NULL,
  `mid` char(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bestellung`
--

INSERT INTO `bestellung` (`bestellnr`, `bestdatum`, `email`, `mid`) VALUES
('1', '2022-05-08', 'v@test.de', '100'),
('10', '2022-05-16', 'b@test.de', '99'),
('11', '2022-06-08', 'v@test.de', '100'),
('12', '2022-06-04', 'b@test.de', '100'),
('13', '2022-06-12', 'c@test.de', '100'),
('14', '2022-06-28', 'd@test.de', '100'),
('15', '0000-06-10', 'j@test.de', '100'),
('16', '2022-06-06', 'l@test.de', '100'),
('17', '2022-02-27', 'm@test.de', '100'),
('18', '2022-01-07', 'n@test.de', '100'),
('2', '2022-05-04', 'b@test.de', '101'),
('20', '2022-06-13', 'b@test.de', '100'),
('21', '2022-06-18', 'v@test.de', '100'),
('22', '2022-06-22', 'b@test.de', '100'),
('23', '2022-06-25', 'c@test.de', '100'),
('24', '2022-06-29', 'd@test.de', '100'),
('25', '0000-06-15', 'j@test.de', '100'),
('26', '2022-06-11', 'l@test.de', '100'),
('27', '2022-06-10', 'm@test.de', '100'),
('28', '2022-06-09', 'n@test.de', '100'),
('3', '2022-04-12', 'c@test.de', '98'),
('30', '2022-06-23', 'b@test.de', '100'),
('31', '2022-06-18', 'v@test.de', '100'),
('4', '2022-04-28', 'd@test.de', '99'),
('40', '2022-06-23', 'b@test.de', '100'),
('5', '0000-00-00', 'j@test.de', '97'),
('6', '2022-05-06', 'l@test.de', '102'),
('7', '2022-04-27', 'm@test.de', '103'),
('8', '2022-05-07', 'n@test.de', '104');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`bestellnr`),
  ADD KEY `fk_kunde` (`email`),
  ADD KEY `fk_markt` (`mid`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD CONSTRAINT `fk_kunde` FOREIGN KEY (`email`) REFERENCES `kunde` (`email`),
  ADD CONSTRAINT `fk_markt` FOREIGN KEY (`mid`) REFERENCES `markt` (`mid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
