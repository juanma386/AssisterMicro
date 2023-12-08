<?php

/**
 * AssisterMicro
 *
 * A microservices framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2023, Hexome, Inc. (https://hexome.com.ar/)
 * Villalba Juan Manuel Pedro             
 * 
 * @package	AssisterMicro
 * @author	Hexome Dev Team
 * @copyright	Copyright (c) 2023, Hexome, Inc. 
 * @license	https://opensource.org/licenses/BSD-3-Clause	BSD 3-Clause "New" or "Revised" License
 * @link	https://hexome.cloud
 * @since	Version 1.0.0
 * @filesource
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
define('ENVIRONMENT', isset($_SERVER['AM_ENV']) ? $_SERVER['AM_ENV'] : 'development');


$minPhpVersion = '7.4';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run AssisterMicro. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );
    exit($message);
}

// Definir la ruta al front controller (este archivo)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Asegurar que el directorio actual apunte al directorio del front controller
chdir(FCPATH);

// Cargar el archivo de configuración de rutas
require FCPATH . 'app/Config/Paths.php';

$paths = new App\Config\Paths();

// Ubicación del archivo de arranque del framework
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

// Cargar la configuración del entorno desde archivos .env en $_SERVER y $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new AssisterMicro\Config\DotEnv(ROOTPATH))->load();

/*
// Obtener la instancia de AssisterMicro
$app = Config\Services::assistermicro();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

// Lanzar la aplicación
$app->run();
*/