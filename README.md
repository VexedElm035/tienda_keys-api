# Requisitos: instalar nodejs, npm, php y composer, comprobar que este instalado con:

```sh
node --version
npm --version
php --version
composer --version
```

# Verificar que esten habilitadas las extensiones de php
El siguiente comando mostrara la ruta del archivo de configuracion de php

```sh
php --ini
```

Hay que editar el archivo de php.ini en la seccion de Dynamic extensions para habilitar las siguientes extensiones:
- extension=pdo_mysql

- extension=mysqli

- extension=gd

- extension=curl

- extension=iconv

- extension=intl

Para habilitarlas solo se les quita el ";" para que no esten comentadas

# Clonar el repositorio e instalar las dependencias

```sh
git clone https://github.com/VexedElm035/tienda_keys-api.git 
cd tienda_keys-api
npm install
composer update
composer install
```
En caso de que marque este error: Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1

Hay que meterse a esta ruta:

```sh
cd vendor/nunomaduro/termwind/src
```
Modificar el: HtmlRenderer.php, en la linea 39 aprox y eliminar el LIBXML_NOXMLDECL al final de donde parece que se importan varias librerias  

Despues volvemos a la ruta principal de la la app en laravel y ejecutamos:

```sh
composer dump-autoload 
```

# Inicializar la app
(opcional con parametros: --host 0.0.0.0 --port 8080)

```sh
php artisan serve
```

# Para la conexion con la base nomas hay qeu modificar el .env y hacer las migraciones

# nose

extra:
composer require intervention/image

php artisan config:publish cors\

tiendakeys
tonoDRON123

fortify: paqueteria para el manejo de sesion en laravel
