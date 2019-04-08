<?php

namespace Vectorface\Tests\Client;

use Vectorface\Client\API;

class APITest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function synopsis()
    {
        $username = isset($_ENV['API_TEST_USERNAME']) ? $_ENV['API_TEST_USERNAME'] : null;
        $password = isset($_ENV['API_TEST_PASSWORD']) ? $_ENV['API_TEST_PASSWORD'] : null;
        $url = isset($_ENV['API_TEST_URL']) ? $_ENV['API_TEST_URL'] : null;
        $resource = isset($_ENV['API_TEST_RESOURCE']) ? $_ENV['API_TEST_RESOURCE'] : null;
        $expect = empty($_ENV['API_TEST_EXPECT']) ? [] : json_decode($_ENV['API_TEST_EXPECT'], true);

        if (empty($username) || empty($password) || empty($url) || empty($resource)) {
            $this->markTestSkipped("No test information available in environment");
        }

        $api = new API([
            'username' => $username,
            'password' => $password,
            'url' => $url,
        ]);

        $response = $api->request($resource, ['foo' => 'bar']);

        foreach ($expect as $field => $expected) {
            $this->assertEquals($expected, $response[$field]);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider missingArgsProvider
     */
    public function testMissingArgs($args)
    {
        new API($args);
    }

    public function missingArgsProvider()
    {
        return [
            [[]], // missing username
            [['username' => 'foo']], // missing pasword
            [['username' => 'foo', 'password' => 'bar']], // missing URL
        ];
    }
}
