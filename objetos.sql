-- Vista que muestra el título, resumen y todos los tópicos asociados a un artículo
CREATE VIEW vista_articulos_topicos AS
SELECT 
    a.titulo, 
    a.resumen, 
    GROUP_CONCAT(t.nombre SEPARATOR ', ') AS topicos
FROM Articulo a
JOIN Articulo_Topico atp ON a.id = atp.id_articulo
JOIN Topico t ON atp.id_topico = t.id
GROUP BY a.id, a.titulo, a.resumen;



-- Función que devuelve la cantidad de artículos en los que un autor ha participado
DELIMITER // --“Voy a escribir varias líneas. No cortes hasta que vea //”

CREATE FUNCTION contar_articulos_autor(rut_input VARCHAR(20))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total INT;

    SELECT COUNT(*) INTO total
    FROM Articulo_Autor
    WHERE rut_autor = rut_input;

    RETURN total;
END;
//

--EJ que funciona:
--SELECT contar_articulos_autor('21738079-0') AS cantidad;

DELIMITER ;



DELIMITER //

CREATE PROCEDURE asignar_revisor(
    IN id_articulo_param INT,
    IN rut_revisor_param VARCHAR(20)
)
BEGIN
    -- Verifica si el revisor tiene una especialidad cuyo nombre coincida con algún tópico del artículo
    IF EXISTS (
        SELECT 1
        FROM articulo_topico atp
        JOIN topico t ON atp.id_topico = t.id
        JOIN especialidad e ON e.nombre = t.nombre
        JOIN revisor_especialidad re ON re.id_especialidad = e.id
        WHERE atp.id_articulo = id_articulo_param
          AND re.rut_revisor = rut_revisor_param
    ) THEN
        INSERT INTO revision(id_articulo, rut_revisor)
        VALUES (id_articulo_param, rut_revisor_param);
    END IF;
END;
//

--EJ que no funciona:
--CALL asignar_revisor(1, '009-79-8480')
--SELECT * FROM revision
--WHERE id_articulo = 1 AND rut_revisor = '009-79-8480';

--Ej que si funciona:
--CALL asignar_revisor(15, '009-79-8480');
--SELECT * FROM revision
--WHERE id_articulo = 15 AND rut_revisor = '009-79-8480';

DELIMITER ;



-- TRIGGER que impide que un autor sea asignado como revisor de su propio artículo.
DELIMITER //

CREATE TRIGGER evitar_autorevision
BEFORE INSERT ON revision
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM articulo_autor
        WHERE id_articulo = NEW.id_articulo
          AND rut_autor = NEW.rut_revisor
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un revisor no puede evaluar su propio artículo.';
    END IF;
END;
//

--Prueba: 
--INSERT INTO revision(id_articulo, rut_revisor)
--VALUES (401, '21738079-0');

DELIMITER ;

