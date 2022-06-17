<!-- Jonas Schirm -->
DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `insertgetraenk`(`p_ghersteller` VARCHAR(50), `p_gname` VARCHAR(30), `p_kategorie` ENUM('Wasser','Saft','Bier','Wein','Limonade','Sonstiges'), `p_preis` DECIMAL(10,2)) RETURNS int(11)
BEGIN
DECLARE EXIT HANDLER FOR 1062
BEGIN
	return 2;
END;

IF (p_preis > 0.00) THEN
	INSERT into getraenke (ghersteller, gname, kategorie, preis) VALUES (p_ghersteller, p_gname, p_kategorie, p_preis);
	return 0;
ELSE
	return 1;
END IF;



END$$
DELIMITER ;