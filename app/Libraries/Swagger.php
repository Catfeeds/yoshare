<?php

namespace App\Libraries;


class Swagger
{
    public static function make()
    {
        $cmd = env('PHP_PATH', '') . 'php ' . base_path('vendor/zircote/swagger-php/bin/swagger') . ' ' . base_path('app/Api') . ' -o ' . public_path('api-docs/swagger.json');
        exec($cmd, $output);
        \Log::debug('swagger cmd: ' . $cmd);
        \Log::debug('swagger output: ', $output);
    }
}