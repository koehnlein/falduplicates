<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Manage FAL duplicates',
    'description' => 'Tool to identifiy and handle FAL duplications',
    'category' => 'plugin',
    'author' => 'Albrecht Köhnlein',
    'author_email' => 'ak@koehnlein.eu',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
