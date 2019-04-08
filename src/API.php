<?php

namespace Vectorface\Client;

use InvalidArgumentException;

class API
{
    /**
     * API username
     * @var string
     */
    private $username;

    /**
     * API password
     * @var string
     */
    private $password;

    /**
     * Base URL to REST API
     * @var string
     */
    private $url;

    /**
     * A dump of debug information
     * @var mixed
     */
    public $debug;

    /**
     * Last request contents, for debugging
     * @var string
     */
    public $contents;

    /**
     * Initializes the API client
     *
     * @param  array  $config  An array of configuration
     */
    public function __construct(array $config = [])
    {
        if (empty($config['username'])) {
            throw new InvalidArgumentException('Missing API username');
        }

        if (empty($config['password'])) {
            throw new InvalidArgumentException('Missing API password');
        }

        if (empty($config['url'])) {
            throw new InvalidArgumentException('Missing API url');
        }

        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->url = $config['url'];
    }

    /**
     * Makes curl call to the url & returns output
     *
     * @param  string  $resource  The resource of the api
     * @param  array  $args  Array of options for the query query
     * @return  array  Finalized Url
     */
    public function request($resource, $args = [])
    {
        $url = $this->buildUrl($resource, $args);
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD        => $this->username . ':' . $this->password
        ]);

        $this->contents = curl_exec($curl);
        $this->setDebug($curl);

        return json_decode($this->contents, true);
    }

    /**
     * Builds the url for the curl request
     *
     * @param  string  $resource  The resource of the api
     * @param  array  $args  Array of options for the query query
     * @return  string  Finalized Url
     */
    private function buildUrl($resource, $args)
    {
        $query = http_build_query($args);

        return $this->url . $resource . '?' . $query;
    }

    /**
     * Sets debug information from curl handle
     *
     * @param  resource  &$curl  Curl handle
     */
    private function setDebug(&$curl)
    {
        $this->debug = [
            'errorNum' => curl_errno($curl),
            'error' => curl_error($curl),
            'info' => curl_getinfo($curl),
            'raw' => $this->contents,
        ];
    }
}
