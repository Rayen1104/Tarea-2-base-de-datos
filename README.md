
# Tarea 2 - INF-239 Bases de Datos

Información General

Integrantes:
- Cristóbal Fernandez — Rol: 202373017-3  
- Rayen Farias — Rol: 202373075-0

⚙️ Instrucciones para ejecutar el sistema

1. Requisitos

- XAMPP o equivalente (con Apache y MySQL)
- Navegador web moderno
- phpMyAdmin (vía `http://localhost/phpmyadmin`)

2. Crear la base de datos

1. Abrir `phpMyAdmin`.
2. Crear una nueva base de datos llamada `gescon`.
3. Importar los siguientes archivos **en orden**:

   - `BD/Create.sql`
   - `BD/Insert.sql`
   - `BD/Objetos.sql`

3. Ejecutar la aplicación

1. Copiar la carpeta `PHP/` dentro de `C:\xampp\htdocs\` (o tu carpeta `htdocs` local).
2. Renombrarla si quieres como `gescon`.
3. Abrir el navegador y entrar a:

```
http://localhost/gescon/login.php
```

Desde ahí se accede a las funcionalidades del sistema.


---

Supuestos asumidos

- Se asumió que los nombres de `especialidad` y `tópico` **coinciden** para permitir la asignación automática de revisores.
- El envío de correos se reemplazó por una alerta en pantalla que dice “CORREO ENVIADO”.
- Los usuarios se registran como autor o revisor al momento de crearse.
- Solo el jefe de comité puede acceder a las secciones de gestión de revisores y asignación.

---

Archivos SQL especiales (`Objetos.sql`)

Contiene:
- `CREATE VIEW vista_articulos_topicos`
- `CREATE FUNCTION contar_articulos_autor`
- `CREATE PROCEDURE asignar_revisor`
- `CREATE TRIGGER evitar_autorevision`

