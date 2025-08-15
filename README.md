# 📦 PROYECTO SACINV

## Entorno Docker
Sistema de inventario para papelería y miscelánea, desarrollado con **PHP 8.2**, **Apache**, **PostgreSQL** y **Node.js** dentro de contenedores Docker.

---

## 🚀 Requisitos previos

- Docker y Docker Compose instalados.
- Git instalado.
- Archivo `.env` configurado con las variables de entorno necesarias, incluido en el proyecto

---

## ⚙️ Preparación inicial

1. **Clonar el repositorio**
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd sacinv
   ```

2. **Ajustar permisos para PostgreSQL** (solo la primera vez o si cambias de máquina)
   ```bash
   ./scripts/ajustar_permisos_postgres.sh
   ```

3. **Colocar el backup de la base de datos**
   - Guardar el archivo `backup_db.backup` dentro del directorio `backup/`.

---

## ▶️ Levantar el proyecto

1. **Construir e iniciar los contenedores**
   ```bash
   docker compose up -d --build
   ```

2. **Verificar que los contenedores estén activos**
   ```bash
   docker ps
   ```

3. **Acceder a la aplicación**
   - Local: [http://localhost](http://localhost)
   - En la LAN: [http://192.168.18.90](http://192.168.18.90)

---

## 🗄 Restaurar la base de datos

Para restaurar desde el backup:

```bash
docker exec -it postgres_db pg_restore   -U bfandino   -d dbdeudores   ./backup/backup_db.backup
```

---

## 📌 Notas

- El puerto `80` está configurado para acceso local y en la red LAN.
- Si modificas permisos en los volúmenes, puedes ejecutar el script de permisos nuevamente.
- El contenedor de **Node** ejecuta automáticamente `npm install` y `gulp dev` al iniciar.
- Los archivos estáticos y el `index.php` principal deben estar dentro de la carpeta `public/`.

---

## 📂 Estructura del proyecto

```
sacinv/
├── backup/               # Archivos de respaldo de la BD
├── data-postgres/        # Datos persistentes de PostgreSQL
├── docker/               # Archivos Dockerfile y scripts
│   ├── apache/
│   └── node/
├── includes/             # Configuración PHP (app.php, database.php, etc.)
├── public/               # Carpeta pública (index.php)
├── vistas/               # Vistas del sistema
├── docker-compose.yml
├── .env
└── README.md
```
