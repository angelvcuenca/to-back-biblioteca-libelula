<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# Despliegue de Laravel con MongoDB
Este repositorio contiene los archivos necesarios para desplegar una aplicación que administre una biblioteca virtual de libros y
autores en Laravel que utiliza MongoDB como base de datos.

A continuación, se detalla cómo configurar y ejecutar la aplicación en un entorno de local/producción.

## Requisitos previos
- PHP >= 8.1 
- Composer 
- MongoDB >= 4.0 
- Servidor web (por ejemplo, Nginx o Apache)
- XAMPP , WAMP, LARAGON

## Instalación

1. Clona este repositorio en tu local:
```bash
git clone https://github.com/angelvcuenca/to-back-biblioteca-libelula.git
```

## Uso
Una vez que la aplicación esté desplegada y configurada correctamente, puedes comenzar a utilizar la API. Aquí tienes algunos puntos de entrada:
### Endpoints Login
- POST /api/auth/login: Inicia la sesion y obtiene un token. 
- POST /api/auth/register: Registra un nuevo usuario. 
- POST /api/auth/logout: Cierra la sesion. 

### Endpoints Autor
- GET /api/v1/all-authors: Obtiene todos los autores.
- GET /api/v1/get-id-author/{id}: Obtiene un autor por su ID.
- POST /api/v1/save-author: Crea un nuevo autor.
- PUT /api/v1/update-author/{id}: Actualiza un autor existente.
- PACHT /api/v1/update-partial-author/{id}: Actualiza un autor segun un campo especifico.
- DELETE /api/v1/delete-author/{id}: Elimina un autor por su ID.

### Endpoints Libro
- GET /api/v1/all-books: Obtiene todos los libros.
- GET /api/v1/get-id-book/{id}: Obtiene un libro por su ID.
- POST /api/v1/save-book: Crea un nuevo libro.
- PUT /api/v1/update-book/{id}: Actualiza un libro existente.
- PACHT /api/v1/update-partial-book/{id}: Actualiza un libro segun un campo especifico.
- DELETE /api/v1/delete-book/{id}: Elimina un libro por su ID.

Asegúrate de consultar la documentación de la API para obtener más detalles sobre los endpoints disponibles y sus parámetros. En el siguiente enlace:

<a href="https://documenter.getpostman.com/view/3256348/2sA3QwcqAr">https://documenter.getpostman.com/view/3256348/2sA3QwcqAr</a>

## Ejecución

1. Instala las dependencias con Composer:
```bash
composer install --no-dev
```
2. Copia el archivo de configuración .env.example y renómbralo como .env:

3. Edita el archivo .env y configura las variables de entorno necesarias, como la conexión a la base de datos MongoDB:
```dotenv
DB_CONNECTION=mongodb
DB_URI="mongodb://localhost:27017"
DB_DATABASE=nombre_base_datos
DB_USERNAME=su_usuario
DB_PASSWORD=su_password

```
4. Genera la clave de la aplicación:
```bash
php artisan key:generate
```
5. Ejecuta las migraciones para crear las tablas en la base de datos:
```bash
php artisan migrate
```
6. Inicia el servidor local
```bash
php artisan serve
```
9. Accede a la API aplicación en tu navegador web en http://localhost:8000.

## Autor

-Este proyecto fue creado por Nombre del Angel Cuenca (angelvcuenca@gmail.com).

