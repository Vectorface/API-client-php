<?php

namespace VectorFace\Client;

class API
{

    private $username, $password, $url;
    public $debug, $contents;

    /**
     * Initializes the API
     *
     * @param  array  $config  An array of configuration
     */
    public function __construct($config = array()) {

        if(empty($config['username'])) {
            throw new \Exception('Missing API username');
        }

        if(empty($config['password'])) {
            throw new \Exception('Missing API password');
        }

        if(empty($config['url'])) {
            throw new \Exception('Missing API url');
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
    public function request($resource, $args=array())
    {

        $url = $this->buildUrl($resource, $args);
        $curl = curl_init($url);

        $options = array(
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERPWD        => $this->username.':'.$this->password
        );
        curl_setopt_array($curl, $options);
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

        $url = $this->url;
        $url .= $resource.'?'.$query;

        return $url;
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