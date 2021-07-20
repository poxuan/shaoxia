<?php

return [
    'alias' => [
        'Media' => Shaoxia\Media\Media::class,
        'Algorithm'  => Shaoxia\Algorithm\Algorithm::class,
        'Decode' => Shaoxia\Decode\Decode::class,
    ],
    'default_uri' => 'Media@image',
    'qiniu' => [
        'domain'    => '',
        'accessKey' => '',
        'secretKey' => '',
        'bucket'    => ''
    ]
];