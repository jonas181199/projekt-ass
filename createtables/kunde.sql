-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2022 um 11:34
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
-- Tabellenstruktur für Tabelle `kunde`
--

CREATE TABLE `kunde` (
  `email` varchar(70) NOT NULL,
  `kkennwort` varchar(255) NOT NULL,
  `kname` varchar(50) NOT NULL,
  `plz` decimal(6,0) NOT NULL,
  `ort` varchar(50) NOT NULL,
  `strasse` varchar(50) NOT NULL,
  `hausnummer` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `kunde`
--

INSERT INTO `kunde` (`email`, `kkennwort`, `kname`, `plz`, `ort`, `strasse`, `hausnummer`) VALUES
('b@test.de', '$2y$10$pFL.fDXSFJAbkclqkfsly.Jds/VQbIHnSYFF6mn80XK', 'Berta', '12345', 'QWERTZ', 'Hauptstr.', '5'),
('c@test.de', '$2y$10$xBm8WSEAfPiKUMes/td2C.4jpfX98Xrd4VRtheAMmAZ', 'Christian', '12345', 'QWERTZ', 'Hauptstr.', '7'),
('d@test.de', '$2y$10$BnA6qELW9cdmUF57LcE06esM0FxjnYvlbxxkU7J4MFJ', 'Dora', '12345', 'QWERTZ', 'Hauptstr.', '8'),
('j@test.de', '$2y$10$.vyWubbG6U9CxW0StAfGYeVOfMMm113JzJOSMjjEckV', 'Karsten', '12345', 'QWERTZ', 'Hauptstr.', '1'),
('l@test.de', '$2y$10$6J/72Oz1RIWqFeyqtBRZOOqomSvdB1OEf4xh.sNfIi0', 'Lea', '12345', 'QWERTZ', 'Hauptstr.', '4'),
('m@test.de', '$2y$10$Dow923j0ibDHOpUmoCgNEuDaE2cKPMoV1X9W0XmTaDc', 'Michael', '12345', 'QWERTZ', 'Hauptstr.', '3'),
('n@test.de', '$2y$10$GPrUPUQ.ZVlg0dEXQZNjHeYHlZi4cum238uwp/Vyg3M', 'Karmen', '12345', 'QWERTZ', 'Hauptstr.', '2'),
('v@test.de', '$2y$10$DdqSUkMhX.VSo7EpAencBu6x/NEEXkelH5YBrd0ccdh', 'Vivien', '12345', 'QWERTZ', 'Hauptstr.', '6');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `kunde`
--
ALTER TABLE `kunde`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
