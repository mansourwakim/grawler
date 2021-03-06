<?php

namespace Bowtie\Grawler;

use Goutte\Client as BaseClient;

class Client extends BaseClient
{
    /** @var  http method */
    protected $method = 'GET';


    /** @var array */
    protected $headers = [];


    /**
     * @param $name
     * @return $this
     */
    public function agent($name)
    {
        $this->setHeader('user-agent', $name);

        return $this;
    }

    /**
     * @param $username
     * @param $password
     * @param string $type
     * @return $this
     */
    public function auth($username, $password, $type = 'basic')
    {
        $this->setAuth($username, $password, $type);

        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function method($type)
    {
        $this->method = ucwords($type);

        return $this;
    }

    /**
     * @param $uri
     * @return Grawler
     */
    public function download($uri)
    {
        $crawler = $this->request($this->method, $uri);

        return new Grawler($crawler, $uri);
    }
}