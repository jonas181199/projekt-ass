<!-- Julian Alber -->
DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `BestellungPruefen`(`p_hname` VARCHAR(50), `p_gname` VARCHAR(30), `p_menge` DECIMAL(7,0), `p_mid` CHAR(7)) RETURNS tinyint(1)
BEGIN
	DECLARE gename VARCHAR(30) DEFAULT 'leer';
	DECLARE v_bestand DECIMAL(7,0) DEFAULT 0;

    IF p_menge <= 0 THEN
        return 0;
    END IF;

    Select gname into gename from getraenke where gname = p_gname AND ghersteller = p_hname;   
    IF gename = 'leer' THEN
        return 0;
    END IF;
    
    Select bestand into v_bestand from lager where gname = p_gname AND ghersteller = p_hname AND mid = p_mid;
    IF v_bestand = 0 OR v_bestand < p_menge THEN
        return 0;
    END IF;

    return 1;  
END$$
DELIMITER ;