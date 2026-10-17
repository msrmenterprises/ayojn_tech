<?php


$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$url = $scheme . '://' . $host;


$chatApiBase = '';

if ($host === '127.0.0.1:8000' || $host === 'localhost') {
    $chatApiBase = 'https://dev-chat.ayojn.com/api/support/';
} elseif (stripos($host, 'dev.ayojn.com') !== false) {
    $chatApiBase = 'https://dev-chat.ayojn.com/api/support/';
} elseif (stripos($host, 'ayojn.com') !== false) {
    $chatApiBase = 'https://chat.ayojn.com/api/support/';
}

$config['agent'] = [
    'all_tickets' => $chatApiBase . 'tickets/{id}/tickets',
    'ticket_details' => $chatApiBase . 'tickets/{id}/messages',
    'ticket_reply' => $chatApiBase . 'tickets/{id}/reply',
];

$config = array(

     'agent' => array(
        'all_tickets'    => $chatApiBase . 'agents/{agent_id}/tickets',
        'ticket_details' => $chatApiBase . 'tickets/{id}/messages',
        'ticket_reply'   => $chatApiBase . 'tickets/{id}/reply',
     ), 

    'data' => array(
        'currency' => 'USD ',
        'ayojn_reward' => 'Ayojn Credits',
        'ayojn_marketplace' => 'Ayojn Rewards',
        'service_charge' => 'Ayojn  Service Charge',
    ),
);

if ($host === '127.0.0.1:8000' || $host === 'localhost') {
    $config['xsquare_local'] = array(
        'API_KEY' => '',
        'ENDPOINT' => 'https://ayojn.com',
        'SECRET_KEY' => '',
        'x-customer-id' => 0,
    );
} else if (stripos($host, 'dev.ayojn.com') !== false) {
    $config['xsquare_local'] = array(
            'API_KEY' => '',
            'ENDPOINT' => 'https://ayojn.com',
            'SECRET_KEY' => '',
            'x-customer-id' =>0 ,
    );
} else if (
    stripos($host, 'ayojn.com') !== false
) {
    $config['xsquare_local'] = array(
        'API_KEY' => '',
        'ENDPOINT' => 'https://ayojn.com',
        'SECRET_KEY' => '',
        'x-customer-id' =>0 ,
    );
}

return $config;


// $url = isset($_SERVER['REQUEST_URI']) ? url('/') : '';


// $config = array(
//     'data' => array(
//         'currency' => 'USD ',
//         'ayojn_reward' => 'Ayojn Credits',
//         'ayojn_marketplace' => 'Ayojn Rewards',
//         'service_charge' => 'Service Charge',
//     ),
// );


// if ($url == 'http://127.0.0.1:8000') {
//        $config['xsquare_local'] = array(
//            'API_KEY' => 'b7f8ca40-130b-4a23-93e3-fa869ef87ee3',
//            'ENDPOINT' => 'https://dev-core-api.xsquare.biz/transaction/collection-add/v3',
//            'SECRET_KEY' => '3a~LvMGO)Wy2^_j',
//            'x-customer-id' => 119,
//        );
//    } else if ($url == 'https://dev.ayojn.com') {
//        $config['xsquare_local'] = array(
//            'API_KEY' => 'b7f8ca40-130b-4a23-93e3-fa869ef87ee3',
//            'ENDPOINT' => 'https://dev-core-api.xsquare.biz/transaction/collection-add/v3',
//            'SECRET_KEY' => '3a~LvMGO)Wy2^_j',
//            'x-customer-id' => 165,
//        );
//    } else if ($url == 'https://ayojn.com') {
//        $config['xsquare_local'] = array(
//            'API_KEY' => 'NgbuIXYCNTcDYVdHDzmdbbNyIddOPpN6',
//            'ENDPOINT' => 'https://core-api.xsquare.biz/transaction/collection-add/v3',
//            'SECRET_KEY' => '#h%eq!UAg<l!bgA',
//            'x-customer-id' => 127,
//        );
//    }

//    return $config;

//'API_KEY' => 'b7f8ca40-130b-4a23-93e3-fa869ef87ee3',
//'ENDPOINT' => 'https://qa-core-api.xsquare.biz/transaction/collection-add/v3',
//'SECRET_KEY' => '3a~LvMGO)Wy2^_j',
//'x-customer-id' => 143,

//call in blade file like this
//{{config('sitevars.data.ayojn_marketplace')}}