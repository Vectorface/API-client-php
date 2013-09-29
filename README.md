API-client-php
==============

## Prerequisites
* Configure the $config username, password and url
* Must be running PHP >= 5.4

## Example

```PHP
<?php

require_once(__DIR__.'/src/Client/API.php');
use Client\API;

//Set configuration settings
$config = array(
    'username' => '',
    'password' => '',
    'url' => ''
);

//Set filters & fields for /users/
$args = array(
    'filters' => 'fname:Jon',
    'fields' => 'user_id,fname,lname'
);

try {

    //Initalizes API with config options, defaults to v1 of the API
    $api = new API($config);

    //Makes request with basic auth in headers
    $results = $api->request('/users', $args);

    //Output json_decoded contents
    var_dump($results);

    //Output various debug information
    // var_dump($api->debug);


} catch (Exception $e) {
    echo $e->getMessage();
}

```