<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClickControllerTest extends TestCase
{
    /**
     * @return array
     */
    private function getFakeRequestData() : array
    {
        $faker = \Faker\Factory::create();

        return [
            'server' => [
                'REMOTE_ADDR' => $faker->ipv4
            ],
            'headers' => [
                'User-Agent' => $faker->userAgent,
                'referer' => $faker->url,
            ],
            'request' => [
                'param1' => $faker->word,
                'param2' => $faker->word,
            ]
        ];
    }

    /**
     * Test index page
     */
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test redirect after processing tracking link
     */
    public function testRedirect()
    {
        $fakeData = $this->getFakeRequestData();

        $uri = route('click', $fakeData['request']);

        $response = $this->withHeaders($fakeData['headers'])
            ->withServerVariables($fakeData['headers'])
            ->get($uri);

        $response->assertRedirect();
    }

    /**
     * Test invalid tracking link
     */
    public function testInvalidTrackingLink()
    {
        $fakeData = $this->getFakeRequestData();

        // Removing one of required fields
        unset($fakeData['headers']['referer']);

        $uri = route('click', $fakeData['request']);

        $response = $this->withHeaders($fakeData['headers'])
            ->withServerVariables($fakeData['headers'])
            ->get($uri);

        $response->assertStatus(404);
    }
}
