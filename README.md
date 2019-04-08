API-client-php
==============

A very simple/naive JSON/REST API client on top of cURL.

## Prerequisites
* Must have username, password and url for the REST API
* Must be running PHP >= 7.0

## Example

```PHP
<?php

require_once './vendor/autoload.php';

use VectorFace\Client\API;

/* Initialize the API client */
$api = new API([
    'username' => 'example',
    'password' => '3x4mpl3',
    'url' => 'http://domain.tld'
]);

/*
 * This will:
 *  - Make a request to
 *    http://domain.tld/users?filters=fname:Jon&fields=user_id,fname,lname
 *  - JSON-decode the response
 *  - return the result
 */
$response = $api->request('/users', [
    'filters' => 'fname:Jon',
    'fields' => 'user_id,fname,lname'
]);

// $response should contain a decoded JSON response from the API
```
