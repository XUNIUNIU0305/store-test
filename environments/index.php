<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'api/runtime',
            'api/web/assets',
            'custom/runtime',
            'custom/web/assets',
            'supply/runtime',
            'supply/web/assets',
            'business/runtime',
            'business/web/assets',
            'wechat/runtime',
            'wechat/web/assets',
            'mobile/runtime',
            'mobile/web/assets'
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'admin/config/main-local.php',
            'api/config/main-local.php',
            'custom/config/main-local.php',
            'supply/config/main-local.php',
            'business/config/main-local.php',
            'wechat/config/main-local.php',
            'mobile/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
        ],
        'setExecutable' => [
        ],
        'setCookieValidationKey' => [
        ],
    ],
];
