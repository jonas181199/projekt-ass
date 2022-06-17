<!-- Julian Alber -->
DELIMITER $$
CREATE TRIGGER `lagerAktualisieren` AFTER INSERT ON `bestellpos` FOR EACH ROW 
BEGIN
	UPDATE lager SET lager.bestand = lager.bestand - (SELECT bp.ganzahl FROM   bestellpos bp WHERE bp.bestellnr = NEW.bestellnr AND bp.position = NEW.position) 
				 WHERE EXISTS (SELECT * FROM bestellpos b WHERE b.ghersteller = lager.ghersteller AND b.gname = lager.gname AND b.bestellnr = NEW.bestellnr AND b.position = NEW.position);
END$$
DELIMITER ;