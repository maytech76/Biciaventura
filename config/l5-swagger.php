<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'MAYDEV API',
                'description' => 'API REST Ecomerce',
                'version' => '1.0.0',
                'termsOfService' => '',
                'contact' => [
                    'email' => 'soporte@maydev.tech', // Corregido: soport → soporte
                ],
                'license' => [
                    'name' => 'MIT',
                    'url' => 'https://opensource.org/licenses/MIT',
                ],
            ],

            'routes' => [
                'api' => 'api/documentation',
                'docs' => 'docs',
                'oauth2_callback' => 'api/oauth2-callback',
            ],

            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app/Http/Controllers'),
                    base_path('app/Models'),
                ],
            ],

            'servers' => [
                [
                    'url' => env('APP_URL') . '/api',
                    'description' => 'Servidor principal',
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'api' => 'api/documentation',
            'oauth2_callback' => 'api/oauth2-callback',
        ],
        
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
        
        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
            'docs_json' => 'api-docs.json',
            'docs_yaml' => 'api-docs.yaml',
        ],

        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => env('L5_SWAGGER_PROXY', null),
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'additional_config_url' => env('L5_SWAGGER_ADDITIONAL_CONFIG_URL', null),
        'validator_url' => env('L5_SWAGGER_VALIDATOR_URL', null),
        
        // ELIMINA esta sección duplicada:
        // 'securityDefinitions' => [
        //     'securitySchemes' => [], // Comentado porque está duplicado
        //     'security' => []
        // ]
    ],

    // === NUEVA SECCIÓN PARA CONFIGURACIÓN DE UI ===
    'swagger_ui' => [
        'display' => [
            'doc_expansion' => 'none',
            'filter' => true,
            'operations_sorter' => 'method',
            'tags_sorter' => 'alpha',
            'default_models_expand_depth' => 3,
            'default_model_expand_depth' => 3,
            'show_extensions' => true,
            'show_common_extensions' => true,
        ],
        
        'persist_authorization' => true, // IMPORTANTE: Guarda el token entre sesiones
    ],

    // === NUEVA SECCIÓN PARA SECURITY SCHEMES ===
    'security' => [
        'sanctum' => [
            'type' => 'http',
            'description' => 'Autenticación con Sanctum',
            'scheme' => 'bearer',
            'bearerFormat' => 'sanctum',
        ],
    ],
    
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('APP_URL'),
    ],
];