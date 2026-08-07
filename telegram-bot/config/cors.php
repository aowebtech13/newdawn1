<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This file configures the CORS middleware that Laravel applies to
    | incoming HTTP requests. It controls which origins, methods, and
    | headers are permitted to access your API from a browser.
    |
    | The frontend is deployed on Render (https://newdawn19.onrender.com)
    | while the Laravel backend runs locally on https://tekdvsgg.nexxora-ai.com
    | during development. Both origins must be allowed here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | The paths within your application that should receive CORS headers.
    | The default configuration covers the API routes and Sanctum's
    | CSRF cookie endpoint.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf_cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | The HTTP methods that are permitted for CORS requests. Using ['*']
    | allows all standard HTTP methods.
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | The origins that are permitted to make cross-origin requests.
    |
    | - https://newdawn19.onrender.com  — production frontend on Render
    | - http://localhost:3000           — Vite dev server (local)
    | - https://tekdvsgg.nexxora-ai.com           — Laravel dev server (local)
    | - http://127.0.0.1:8000           — Laravel dev server (local, IP)
    | - http://127.0.0.1:3000           — Vite dev server (local, IP)
    |
    | In production, the frontend and backend should both be served over
    | HTTPS. The loopback address space blocking in browsers prevents
    | HTTPS pages from making requests to HTTP on localhost.
    |
    */

    'allowed_origins' => [
        'https://newdawn19.onrender.com',
        'http://localhost:3000',
        'https://tekdvsgg.nexxora-ai.com',
        'http://127.0.0.1:8000',
        'http://127.0.0.1:3000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | The headers that are permitted in the actual CORS request.
    | Using ['*'] allows all headers.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | The headers that are exposed to the browser in the response.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | The number of seconds the browser is allowed to cache the CORS
    | preflight response. Set to 0 to disable caching.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Whether the response to the request can be exposed to the page
    | when the request's credentials mode is "include". This is
    | disabled here because the demo API does not use cookies.
    |
    */

    'supports_credentials' => false,

];
