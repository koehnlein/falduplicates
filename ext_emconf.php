<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Manage FAL duplicates',
    'description' => 'Tool to identifiy and handle FAL duplications',
    'category' => 'plugin',
    'author' => 'Albrecht Köhnlein',
    'author_email' => 'ak@koehnlein.eu',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
