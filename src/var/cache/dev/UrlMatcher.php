<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/customer/add' => [[['_route' => 'add_customer', '_controller' => 'App\\Controller\\CustomerController::addCustomer'], null, ['POST' => 0], null, false, false, null]],
        '/customer/get-all' => [[['_route' => 'get_all_customers', '_controller' => 'App\\Controller\\CustomerController::getAllCustomers'], null, ['GET' => 0], null, false, false, null]],
        '/customer/consumer' => [[['_route' => 'consumer', '_controller' => 'App\\Controller\\CustomerController::consumer'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/customer/(?'
                    .'|get/([^/]++)(*:67)'
                    .'|update/([^/]++)(*:89)'
                    .'|delete/([^/]++)(*:111)'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        67 => [[['_route' => 'get_one_customer', '_controller' => 'App\\Controller\\CustomerController::getOneCustomer'], ['id'], ['GET' => 0], null, false, true, null]],
        89 => [[['_route' => 'update_customer', '_controller' => 'App\\Controller\\CustomerController::updateCustomer'], ['id'], null, null, false, true, null]],
        111 => [
            [['_route' => 'delete_customer', '_controller' => 'App\\Controller\\CustomerController::deleteCustomer'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
