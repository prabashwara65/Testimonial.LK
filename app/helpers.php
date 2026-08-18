<?php

use GuzzleHttp\Client;

/**
 * @param $name
 * @return array
 */
function resourceNames($name)
{
    return [
        'names' => [
            'index' => $name . '.index', // Base page
            'create' => $name . '.create', // get: used to show the create page
            'store' => $name . '.store', // Post : used to pass data to backend and store
            'edit' => $name . '.edit', // get : used to show the edit page for a record
            'update' => $name . '.update', // post : used to pass data to back-end to store update data
            'show' => $name . '.show', // get:  show a record for a given ID
            'destroy' => $name . '.destroy',  // Delete a record for a given ID
        ]
    ];
}

function sendOtpCode($customer)
{
    $oauth = new Client();
    $res = $oauth->request('POST', 'https://bsms.hutch.lk/api/login', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'X-API-VERSION' => 'v1'
        ],
        'json' => [
            'username' => env('SMS_GATEWAY_EMAIL'),
            'password' => env('SMS_GATEWAY_PASSWORD')
        ]
    ]);

    $tokens = json_decode($res->getBody()->getContents());

    $message = "Dear Customer, Please use this OTP : " . $customer->otp_code . " to continue your process.";
    $number = $customer->mobile;
    $number = ltrim($number, '0');

    $client = new Client();
    $client->request('POST', 'https://bsms.hutch.lk/api/sendsms', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
            'X-API-VERSION' => 'v1',
            'Authorization' => 'Bearer ' . $tokens->accessToken
        ],
        'json' => [
            'campaignName' => 'Testimonial',
            'mask' => 'Testimonial',
            'numbers' => $number,
            'content' => $message,
        ]
    ]);
}
