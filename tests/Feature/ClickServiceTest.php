<?php

namespace Tests\Feature;

use App\Contracts\Repositories\IBadDomainRepository;
use App\Contracts\Repositories\IClickRepository;
use App\Services\ClickService;
use Illuminate\Http\Request;
use Tests\TestCase;

class ClickServiceTest extends TestCase
{
    /**
     * @return Request
     */
    private function getFakeRequest() : Request
    {
        $faker = \Faker\Factory::create();

        $fakeData = [
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

        $request = new Request();
        $request->server->add($fakeData['server']);
        $request->request->add($fakeData['request']);
        $request->headers->add($fakeData['headers']);

        return $request;
    }

    /**
     * Check basic saving of click
     */
    public function testHandleRequestSavedClick()
    {
        $request = $this->getFakeRequest();

        $clickService = $this->app->make('App\Services\ClickService');
        $click = $clickService->handleRequest($request);

        $this->assertDatabaseHas('clicks', [
            'id' => $click->id,
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
            'ref' => $request->headers->get('referer'),
            'param1' => $request->get('param1'),
            'param2' => $request->get('param2'),
        ]);
    }

    /**
     * Check saving clicks from bad domains
     */
    public function testHandleBadDomainRequest()
    {
        $faker = \Faker\Factory::create();

        // Adding fake domain in database
        $fakeUrl = $faker->url;
        $fakeBadDomain = parse_url($fakeUrl)['host'];

        /** @var IBadDomainRepository $badDomainsRepository */
        $badDomainsRepository = $this->app->make('App\Repositories\BadDomainRepository');
        $badDomainId = $badDomainsRepository->create([
            'name' => $fakeBadDomain
        ]);

        $this->assertDatabaseHas('bad_domains', [
            'name' => $fakeBadDomain
        ]);

        $request = $this->getFakeRequest();
        $request->headers->add(['referer' => $fakeUrl]);

        $clickService = $this->app->make('App\Services\ClickService');
        $click = $clickService->handleRequest($request);

        $this->assertDatabaseHas('clicks', [
            'id' => $click->id,
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
            'ref' => $request->headers->get('referer'),
            'param1' => $request->get('param1'),
            'param2' => $request->get('param2'),
            'bad_domain' => $badDomainId,
            'error' => 1,
        ]);
    }

    /**
     * Check saving of duplicate clicks
     */
    public function testHandleDuplicateRequest()
    {
        $request = $this->getFakeRequest();

        /** @var ClickService $clickService */
        $clickService = $this->app->make('App\Services\ClickService');

        // Saving first click
        $click = $clickService->handleRequest($request);

        // Check error field of first click
        /** @var IClickRepository $clickRepository */
        $clickRepository = $this->app->make('App\Repositories\ClickRepository');
        $clickModel = $clickRepository->findOrFail($click->id);

        // Ensure that error counter is equal to 0
        $this->assertEquals($clickModel->error, 0);

        // Saving duplicate
        $click = $clickService->handleRequest($request);

        // Retrieving duplicate from database
        $clickDuplicateModel = $clickRepository->findOrFail($click->id);

        $this->assertEquals($clickModel->id, $clickDuplicateModel->id);

        // Check that error field incremented
        $this->assertEquals($clickDuplicateModel->error, 1);
    }
}
