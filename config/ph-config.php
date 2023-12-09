<?php

return [
    'PH_PROJECT_TOKEN'      => env('PH_PROJECT_TOKEN', 'project_63c68770399bd1673955184'),
    'PH_GATEWAY_TOKEN'      => env('PH_GATEWAY_TOKEN', 'payment_gateway_63d7485e618ec1675053150'), // this is for checkout
    'PH_GATEWAY_TOKEN_STRIPE'=> env('PH_GATEWAY_TOKEN_STRIPE', 'payment_gateway_63f8763333f341677227571'),
    'COMMUNICATION_KEY'     => env('COMMUNICATION_KEY', '$2a$12$xL8HkvD08hAainJFn1e5wuehyYZsgRXAQRs2deGpvE2Bk9xk1YzzO'),
    'GATEWAY_TOKEN_OUTSIDE' => env('GATEWAY_TOKEN_OUTSIDE', 'payment_gateway_63d7485e618ec1675053150'),
    'PH_URL'                => env('PH_URL', 'https://staging-phb.fundednext.com/api/v1')
];
