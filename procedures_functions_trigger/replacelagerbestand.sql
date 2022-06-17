<!-- Noah Schöne -->

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `replacelagerbestand`(`mid` CHAR(7) CHARSET utf8mb4, `ghersteller` VARCHAR(50) CHARSET utf8mb4, `gname` VARCHAR(30) CHARSET utf8mb4, `bestand` DECIMAL(7,0)) RETURNS INT(11) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY 
DEFINER 
BEGIN 
    if bestand < 0 
        then return 0;
    else 
        replace into lager (mid, gname, ghersteller, bestand) values (mid, gname, ghersteller, bestand);
        return 1; 
    end if; 
END$$
DELIMITER ;