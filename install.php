<?php

$baseDir = __DIR__;

$rootEnvContent = file_get_contents('.env');
$rootEnvLines = explode("\n", $rootEnvContent);

$envVars = array_reduce($rootEnvLines, static function (array $acc, string $line) {
    if (empty($line) || !str_contains($line, '=')) {
        return [];
    }

    [$key, $value] = explode('=', $line, 2);
    $acc[$key] = trim($value);
    return $acc;
}, []);


// Entrar a la carpeta /mandu-division-backend
$backendDir = "$baseDir/mandu-division-backend";
$backendEnvironmentFile = '.env';

chdir($backendDir);

// Verificar y copiar el archivo .env desde .env.example si no existe
copyEnvironmentFileIfNotExist($backendEnvironmentFile, from: '.env.example');

// Copiar las variables de entorno al archivo .env de mandu-division-backend
setEnvironment($backendEnvironmentFile, [
    'APP_URL' => $envVars['APP_URL'],
    'DB_HOST' => $envVars['DB_HOST'],
    'DB_PORT' => $envVars['DB_PORT'],
    'DB_DATABASE' => $envVars['DB_DATABASE'],
    'DB_USERNAME' => $envVars['DB_USERNAME'],
    'DB_PASSWORD' => $envVars['DB_PASSWORD'],
]);

echo "Variables de entorno copiadas correctamente en mandu-division-backend.\n";

// Instalar dependencias
run('composer install');

// Generar llave de aplicación
run('php artisan key:generate');

// Crear la base de datos si no existe
run('php artisan db:create');

// Ejecutar migraciones y poblar tablas
run('php artisan migrate --seed');

// Entrar a la carpeta /mandu-division-frontend
$frontendDir = "$baseDir/mandu-division-frontend";
$frontendEnvironmentFile = ".env.local";
chdir($frontendDir);

// Verificar y copiar el archivo .env desde .env.example si no existe
copyEnvironmentFileIfNotExist($frontendEnvironmentFile, from: '.env.example');

// Copiar la variable de entorno APP_PORT al archivo .env de mandu-division-frontend
setEnvironment($frontendEnvironmentFile, [
    'VITE_API_URL' => sprintf("%s:%s", $envVars['APP_URL'], $envVars['APP_PORT'])
]);
echo "Variables de entorno copiadas correctamente en mandu-division-frontend.\n";

// Ejecutar npm install
run('npm install');

echo "Proceso completado.\n";

chdir($baseDir);
run(sprintf('start "" /B cmd /c run.bat "%s"', $envVars['APP_PORT']));


function run(string $command): void
{
    echo "Ejecutando comando: $command\n";
    passthru($command);
    echo "\n";
}

function copyEnvironmentFileIfNotExist(string $environmentFile, string $from): void
{
    if (!file_exists($environmentFile)) {
        copy($from, $environmentFile);
        echo "Archivo $environmentFile copiado correctamente.\n";
    }
}

function setEnvironment(string $environmentFile, array $variables): void
{
    $envContent = file_get_contents($environmentFile);

    foreach ($variables as $key => $value) {
        $envContent = preg_replace("/^$key=.*$/m", "$key=$value", $envContent);
    }

    file_put_contents($environmentFile, $envContent);
}
