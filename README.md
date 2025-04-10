# Gestor de Usuarios - Prueba Full Stack UNBC

Aplicación web desarrollada como parte de la prueba Full Stack para UNBC. Consiste en un sistema de autenticación y un módulo CRUD (Crear, Leer, Actualizar, Eliminar) para la gestión de usuarios.

![PHP](https://img.shields.io/badge/PHP-^8.1-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-^12.x-FF2D20?logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-^3.x-4F549E?logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-^3.x-06B6D4?logo=tailwindcss&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-^12+-336791?logo=postgresql&logoColor=white)
![Railway](https://img.shields.io/badge/Deployed%20on-Railway-0B0D0E?logo=railway&logoColor=white)

## Descripción General

Este proyecto permite registrar usuarios, iniciar sesión y, una vez autenticado, gestionar un listado de usuarios a través de una interfaz ABM/CRUD. La interfaz fue desarrollada utilizando el stack TALL (Tailwind CSS, Alpine.js - via Livewire, Laravel, Livewire) y se conecta a una base de datos PostgreSQL. La aplicación está localizada en español.

## Características Principales

* Autenticación de Usuarios (Registro e Inicio de Sesión - via Laravel Breeze).
* Verificación de Email (Opcional, configurado por Breeze).
* Gestión de Perfil de Usuario (Básico, provisto por Breeze).
* CRUD completo para Usuarios:
    * Crear nuevos usuarios.
    * Listar usuarios existentes con paginación.
    * Editar información de usuarios.
    * Eliminar usuarios.
    * Buscar usuarios por nombre, apellido o email.
* Interfaz desarrollada con Livewire 3 y Tailwind CSS.
* Localización en Español.

## Stack Tecnológico

* **Backend:** PHP 8.1+, Laravel 12.x
* **Frontend:** Livewire 3.x, Tailwind CSS 3.x, Alpine.js (integrado con Livewire/Breeze)
* **Base de Datos:** PostgreSQL >= 12
* **Autenticación:** Laravel Breeze (con stack Livewire/Volt)
* **Servidor de Desarrollo:** `php artisan serve`
* **Compilación de Assets:** Vite
* **Despliegue:** Railway.app

## Prerrequisitos Locales

Antes de empezar, asegúrate de tener instalado:

* PHP >= 8.1 (con extensiones requeridas por Laravel: pdo_pgsql, bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml)
* Composer ([https://getcomposer.org/](https://getcomposer.org/))
* Node.js y npm (o yarn) ([https://nodejs.org/](https://nodejs.org/))
* Un servidor de base de datos PostgreSQL corriendo localmente.
* Git ([https://git-scm.com/](https://git-scm.com/))

## Instalación y Configuración Local (Paso a Paso)

Sigue estos pasos para poner en marcha el proyecto en tu entorno local:

1.  **Clonar el Repositorio:**
    ```bash
    git clone [[URL_DEL_REPOSITORIO_AQUI](https://github.com/patriciomelor/Prueba-Fullstack.git)](https://github.com/patriciomelor/Prueba-Fullstack.git) Prueba-Fullstack
    cd Prueba-Fullstack
    ```

2.  **Instalar Dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Instalar Dependencias de Node.js:**
    ```bash
    npm install
    # o si usas yarn:
    # yarn install
    ```

4.  **Configurar el Entorno:**
    * Copia el archivo de ejemplo `.env.example` a `.env`:
        ```bash
        cp .env.example .env
        ```
    * Abre el archivo `.env` en tu editor de texto.
    * **Configura la conexión a tu base de datos PostgreSQL local:**
        * Crea una base de datos y un usuario para el proyecto en tu PostgreSQL local.
        * Actualiza las siguientes variables en `.env`:
            ```dotenv
            DB_CONNECTION=pgsql
            DB_HOST=127.0.0.1  # o el host de tu BD local
            DB_PORT=5432      # o el puerto de tu BD local
            DB_DATABASE=tu_nombre_bd_local
            DB_USERNAME=tu_usuario_bd_local
            DB_PASSWORD=tu_contraseña_bd_local
            ```
    * **Otras variables:** Revisa si necesitas ajustar otras variables como las de correo (`MAIL_...`) si quieres probar esa funcionalidad localmente (puedes usar Mailtrap.io o similar).

5.  **Generar la Clave de Aplicación:**
    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar las Migraciones:**
    Esto creará la estructura de tablas en tu base de datos local.
    ```bash
    php artisan migrate
    ```

7.  **Compilar los Assets (CSS/JS):**
    * Para desarrollo (con auto-recarga):
        ```bash
        npm run dev
        ```
    * Para producción (archivos minificados):
        ```bash
        npm run build
        ```

8.  **Iniciar el Servidor de Desarrollo:**
    ```bash
    php artisan serve
    ```

¡Listo! Ahora deberías poder acceder a la aplicación en tu navegador web visitando `http://127.0.0.1:8000` (o el puerto que indique `serve`).

## Credenciales de Prueba

Puedes usar las siguientes credenciales para iniciar sesión y probar la funcionalidad de gestión de usuarios.

* **Nombre:** Admin Tech
* **Correo:** `admin@admin.com`
* **Contraseña:** `admin1234`

*(Nota: Asegúrate de que este usuario exista. Puedes crearlo usando el formulario de registro de la aplicación o mediante seeders si se implementaran)*.

## Despliegue

La aplicación está desplegada en Railway y accesible en la siguiente URL:

[https://prueba-fullstack.up.railway.app/](https://prueba-fullstack.up.railway.app/)

## Autor

* **Patricio Melo**

---

*Desarrollado como parte de la Prueba Full Stack para UNBC.*
