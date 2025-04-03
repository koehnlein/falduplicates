<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Manage FAL duplicates',
    'description' => 'Tool to identifiy and handle FAL duplications',
    'category' => 'plugin',
    'author' => 'Albrecht Köhnlein',
    'author_email' => 'ak@koehnlein.eu',
    'state' => 'alpha',
    'version' => '0.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
