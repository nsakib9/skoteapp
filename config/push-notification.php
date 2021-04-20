<?php

return [
    'Rider' => [
        'environment' => 'development',
        'certificate' => resource_path() . '/credentials/GoferRiderDev.pem',
        'passPhrase' => 'password',
        'service' => 'apns'
    ],
    'Driver' => [
        'environment' =>'development',
        'certificate' => resource_path() . '/credentials/GoferDriverDev.pem',
        'passPhrase' =>'password',
        'service' =>'apns'
    ],
];

?>
