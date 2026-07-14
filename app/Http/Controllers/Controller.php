<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="MAYDEV Servicio de ingenieria",
 *         version="1.0.0",
 *         description="Documentación de la API E-comerce 2025",
 *         @OA\Contact(
 *             email="soporte@maydev.tech"
 *         )
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="sanctum",
 *             type="http",
 *             description="Usa el token generado tras el login. Formato: Bearer {token}",
 *             name="Authorization",
 *             in="header",
 *             scheme="bearer",
 *             bearerFormat="sanctum"
 *         )
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}