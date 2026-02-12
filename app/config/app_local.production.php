<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * This version reads from environment variables for production deployment.
 * For local development, copy app_local.example.php to app_local.php and edit.
 */
return [
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'DebugKit' => [
        'ignoreAuthorization' => true,
    ],
    'Security' => [
        'salt' => env('SECURITY_SALT', 'd0facb705608b52c959c16344ec549cfa1b5348b197f8474099776885a912fe5'),
    ],

    'Datasources' => [
        'default' => [
            'host' => env('DB_HOST', 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com'),
            'port' => env('DB_PORT', '4000'),
            'username' => env('DB_USERNAME', 'PvgAxx6s1yzgWa5.root'),
            'password' => env('DB_PASSWORD', 'LWd1fMpS7KMIIvsh'),
            'database' => env('DB_DATABASE', 'test'),
            'driver' => \Cake\Database\Driver\Mysql::class,
            'ssl_ca' => '/etc/ssl/certs/ca-certificates.crt',
            'flags' => [
                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ],
            'url' => env('DATABASE_URL', null),
        ],
        'test' => [
            'host' => 'localhost',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tmp/tests.sqlite'),
        ],
    ],

    'EmailTransport' => [
        'default' => [
            'className' => 'Debug',
        ],
        'debug' => [
            'className' => 'Debug',
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'debug',
            'from' => ['no-reply@example.com' => 'Koalala Finds'],
            'charset' => 'utf-8',
            'headerCharset' => 'utf-8',
        ],
    ],

    'MinIO' => [
        'endpoint' => env('MINIO_ENDPOINT', 'http://minio:9000'),
        'key' => env('MINIO_KEY', 'AKIAEXAMPLEEXAMPLEEX'),
        'secret' => env('MINIO_SECRET', 'EXAMPLESECRETKEY/EXAMPLESECRETKEY/EXAMPL'),
        'bucket' => env('MINIO_BUCKET', 'public'),
        'region' => env('MINIO_REGION', 'us-east-1'),
    ],

    'GoogleOAuth' => [
        'clientId' => env('GOOGLE_OAUTH_CLIENT_ID', ''),
        'clientSecret' => env('GOOGLE_OAUTH_CLIENT_SECRET', ''),
        'redirectUri' => env('GOOGLE_OAUTH_REDIRECT_URI', 'http://localhost:8080/users/google-callback'),
    ],
];
