<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $container): void {
    $env = $container->env();
    $username = $_SERVER['ADMIN_IN_MEMORY_USERNAME'] ?? $_ENV['ADMIN_IN_MEMORY_USERNAME'] ?? 'admin';
    $passwordHash = $_SERVER['ADMIN_IN_MEMORY_PASSWORD_HASH'] ?? $_ENV['ADMIN_IN_MEMORY_PASSWORD_HASH'] ?? '';

    $container->extension('security', [
        'password_hashers' => [
            PasswordAuthenticatedUserInterface::class => 'auto',
        ],
        'providers' => [
            'in_memory' => [
                'memory' => [
                    'users' => [
                        $username => [
                            'password' => $passwordHash,
                            'roles' => ['ROLE_ADMIN'],
                        ],
                    ],
                ],
            ],
            'app_user_provider' => [
                'entity' => [
                    'class' => App\Entity\User::class,
                    'property' => 'email',
                ],
            ],
            'chain_provider' => [
                'chain' => [
                    'providers' => ['in_memory', 'app_user_provider'],
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'chain_provider',
                'form_login' => [
                    'login_path' => 'app_login',
                    'check_path' => 'app_login',
                ],
                'logout' => [
                    'path' => 'app_logout',
                    'target' => 'app_home',
                ],
            ],
        ],
        'access_control' => [
            ['path' => '^/admin', 'roles' => 'ROLE_ADMIN'],
        ],
    ]);

    if ('test' === $env) {
        $container->extension('security', [
            'password_hashers' => [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4,
                    'time_cost' => 3,
                    'memory_cost' => 10,
                ],
            ],
        ]);
    }
};
