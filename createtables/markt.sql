-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Jun 2022 um 11:38
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
-- Tabellenstruktur für Tabelle `markt`
--

CREATE TABLE `markt` (
  `mid` char(7) NOT NULL,
  `mpasswort` varchar(255) NOT NULL,
  `mname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `markt`
--

INSERT INTO `markt` (`mid`, `mpasswort`, `mname`) VALUES
('1', '$2y$10$FMd1AOVEdvenhZkinCDKEeBvWt.fEWwU8pu9hKvAbjC', 'test12'),
('100', '$2y$10$70zaSPZ0AVvMC1yL2ZHvD.qPu8YOiBBWIqYF84y7zPLHr54acbi0K', 'rewe100'),
('1000', '$2y$10$rqX1WEMVuskGELmyPe74HexN70ah10sAwQFYufRuLmj3FxrUZjYa6', 'hfle'),
('101', '$2y$10$zmJGoZ0OKvI1W4sAtmxCHOPujeaw3aflMdTgj61Hj7ISnhtyDYEz2', 'rewe101'),
('102', '$2y$10$FAV11mYb4hNXPV8W7.Nkp.mOdfgG8M4tE6hnn/X0c5Q2Hydj7Z3rq', 'rewe102'),
('103', '$2y$10$9gcBVCB8EyCWs3YExSTcV.vASesyBFYpX8kClcZSLxZ3dKkPt9uZG', 'rewe103'),
('104', '$2y$10$Rt9b9xis0WVjr0QXTqKSzO9.s2TtJAj9HxxaLjoIsFneakIM.WW5O', 'rewe104'),
('105', '$2y$10$Bmf42uVl7tS5C7VJF7t49uivDoUzSTTphlLRqHBaB89PNQ3dr15qK', 'rewe105'),
('106', '$2y$10$7KRHyWm0nVXNjUdtZqfoUOisaMR5/6GnMM9wIXL/UJ.7h/jS4LfuK', 'rewe106'),
('107', '$2y$10$79Y74Cl5i2iLLeaVbqA7v.I8nweP8ZS/4trCqJl7kE3edlr1RzyPm', 'rewe107'),
('109', '$2y$10$ixgpn6FA3n7pIFQrU86RxeBqBENvU9XXgjmWCe584B3r/dDroyfQm', 'simon'),
('12', '$2y$10$vr.wFyqF2PxVexdQYixTKOeMOXddF5LpLrpZrvExAEo', 'Rewe HH'),
('123', 'passwort', 'test'),
('12322', '$2y$10$9aUT8gUt6mqizD7GnLeaFerY/lHu7M2hAhq/xFDPWXU', 'Test'),
('1234', 'passwort', 'Test1'),
('12342', '$2y$10$KD/RLpx8SvQOJXHpYFqp1.uK0JEw5b2wQ85ixEwBtm3', 'Test11'),
('1236546', '$2y$10$B.6JowAW9qOwygnDwQIfj.DhyjKO6m6U7lPiQk9IBaP', 'Rewe'),
('13', '$2y$10$p1mIeDFEv1qc6nOY/3M65ezt0vFPayyFLk7OsVPISbbCXCEjEX6SW', 'Rewe MUC'),
('21', '$2y$10$wHLcfvmwiemfSi6uxqkgJ.M9aieVGc15evA4Wkrs5KL', 'jweo'),
('32', '$2y$10$ANWbUYomxc0XPeSH3p2baeimGAZKr7ZKWYfqVUUyufn', 'Testnedlcen'),
('34', '$2y$10$slPXEx.4e3/6/s/VH1D1l.52T.9msG8gH/dS/Skcw.hownJAS3cwC', 'Aldi'),
('785', '$2y$10$K1RweHstZNNlyS7tMGssVubzn9inN4QCSAPZjULnNic', 'Rewe Rudel'),
('87', '$2y$10$OuuRXP/uCitfDKkMCOVJSO0zeujClgrVt5k1hI/xWc295IlMXUPnO', 'Rewe87'),
('88', '$2y$10$q5TLQLfcSv0AXpI1FWKWIORud3gxgJXmCte9ykQAcR/yZdvP81XDS', 'rewe88'),
('90', '$2y$10$AcjMjkkGRrN8zlPlgYPB2uyLEkrhFhGUkBxQytLSF5n3.JT3aL3mC', 'rewe90'),
('91', '$2y$10$IU4iDUluxO0WivZV0Q2GHu3tkLm7ZPRBJR.yL2ATG4tYxR9NxA0qG', 'rewe91'),
('92', '$2y$10$2U5BLJFdzAF2nbpgK1nYqeSuZALubcjlRyNwVIfgXrpl1jp4QxAlu', 'rewe92'),
('93', '$2y$10$RWQSR1ZMILU61RsQOYr6p.mqDR5VGQFxbPcFd4fg7dci9U3D5HjpK', 'rewe93'),
('95', '$2y$10$FZXWm.IexkaKaY/x3PhNV.e.QstY8ZpEYwvXXMPN5yRvUXYx.Wa3W', 'rewe95'),
('96', '$2y$10$D7aVsls0S8SsY1i9x1yVbOhxWCfCmXwZ1a02WDVgpHLPyxDIpgepC', 'rewe96'),
('97', '$2y$10$YHvEMtyMjGLmxr/E7vlDhekmrCez6sAyB06tqqQ8kajvyMbDms1vK', 'rewe97'),
('98', '$2y$10$5bLC4mYddWGAfmTYjS8XkOm.9C.m0dOM8IxQZJUG8e7StMSjb2ouy', 'rewe98'),
('99', '$2y$10$b8IvuO8ocfudwd6nUyRVMe33Xh1G58a9EWNdPrnxuIO1vNN1AZg92', 'rewe99'),
('999', '$2y$10$EL7pHpJSFnpfMryKRBEyROzIaF7FATSFnIyAh/OQ0p7.fRQ6Ikd2e', 'rewe hsk');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `markt`
--
ALTER TABLE `markt`
  ADD PRIMARY KEY (`mid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
