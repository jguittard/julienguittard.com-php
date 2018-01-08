<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */
return [
    'dependencies' => [
        'factories' => [
            'fileSystem' => WShafer\PSR11FlySystem\FlySystemFactory::class,
        ],
    ],
    'flysystem' => [
        'adaptors' => [
            'default' => [
                'type' => 'local',
                'options' => [
                    'root' => 'data/blog', // Required : Path on local filesystem
                    'writeFlags' => LOCK_EX,   // Optional : PHP flags.  See: file_get_contents for more info
                    'linkBehavior' => League\Flysystem\Adapter\Local::DISALLOW_LINKS, // Optional : Link behavior

                    // Optional:  Optional set of permissions to set for files
                    'permissions' => [
                        'file' => [
                            'public' => 0644,
                            'private' => 0600,
                        ],
                        'dir' => [
                            'public' => 0755,
                            'private' => 0700,
                        ]
                    ]
                ],
            ],
        ],
    ],
];