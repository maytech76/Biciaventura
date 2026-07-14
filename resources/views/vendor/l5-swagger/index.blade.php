<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{config('l5-swagger.documentations.'.$documentation.'.api.title')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}" sizes="16x16"/>
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>

<body>
<div id="swagger-ui"></div>

<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
<script>
    window.onload = function() {
        // Solucion para securitySchemes null
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',
            url: "{!! $urlToDocs !!}",
            operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
            configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
            validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
            oauth2RedirectUrl: "{{ route('l5-swagger.'.$documentation.'.oauth2_callback', [], $useAbsolutePath) }}",
            persistAuthorization: {!! config('l5-swagger.defaults.ui.persist_authorization') ? 'true' : 'false' !!},

            requestInterceptor: function(request) {
                request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: "StandaloneLayout",
            docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion') !!}",
            deepLinking: true,
            filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
            tagsSorter: "{!! config('l5-swagger.defaults.ui.display.tags_sorter') !!}",

            // Configuracion manual de seguridad si es necesario
            onComplete: function() {
                // Si hay problemas con securitySchemes, configurar manualmente
                if (typeof ui.authActions !== 'undefined') {
                    // Configurar Sanctum manualmente si es necesario
                    ui.authActions.authorize({
                        sanctum: {
                            name: 'Authorization',
                            value: 'Bearer '
                        }
                    });
                }
            }
        });

        // Agregar boton para setear token manualmente
        const authorizeBtn = document.createElement('button');
        authorizeBtn.innerHTML = '🔑 Set Token';
        authorizeBtn.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; padding: 10px 20px; background: #49cc90; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold;';
        authorizeBtn.onclick = function() {
            const token = prompt('Enter your Sanctum token (Bearer token):', localStorage.getItem('swagger_token') || '');
            if (token) {
                localStorage.setItem('swagger_token', token);
                ui.authActions.authorize({
                    sanctum: {
                        name: 'Authorization',
                        value: 'Bearer ' + token
                    }
                });
                alert('Token set successfully!');
            }
        };
        document.body.appendChild(authorizeBtn);

        window.ui = ui;
    };
</script>
</body>
</html>