# Mandu Division

Clone el repositorio base junto con los submódulos.

```shell
git clone --recurse-submodules https://github.com/vaened/mandu-division.git
```

```shell
cd mandu-division
```

Para levantar los proyectos, inicialmente, deberá configurar las variables de entorno en el archivo `.env` en la raíz del
proyecto.

```dotenv
APP_URL=http://localhost
APP_PORT=8005
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=mandu_division_vaened
DB_USERNAME=root
DB_PASSWORD=
```

Es necesario configurar cada variable, ya que con base en esto se creará la base de datos `${DB_DATABASE}` y se realizará
el vínculo desde `React` a `Laravel`

## Instalación

```sh
php install.php
```

Inmediatamente después ejecutar el instalador, se descargarán las dependencias y se configurara el proyecto, además que
se levantaran las aplicaciones del backend y frontend.