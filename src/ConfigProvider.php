<?php

declare(strict_types=1);
/**
 * This file is part of Tianmiao.
 *
 * @link     https://tianmiao.com
 * @document https://docs.tianmiao.com
 * @contact  tianmiao.com@gmail.com
 * @license  https://tianmiao.com/LICENSE
 */
namespace Tianmiao\AccessCheck;

class ConfigProvider
{
    public function __invoke(): array
    {
        $configSourcePath = __DIR__ . '/../config/access_check.php';
        $configDestinationPath = BASE_PATH . '/config/autoload/access_check.php';

        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of Access Check',
                    'source' => $configSourcePath,
                    'destination' => $configDestinationPath,
                ],
            ],
//            'access_check' => file_exists($configDestinationPath) ? [] : require $configSourcePath,
        ];
    }
}
