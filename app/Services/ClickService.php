<?php

namespace App\Services;

use App\Click;
use App\Contracts\Repositories\IBadDomainRepository;
use App\Contracts\Repositories\IClickRepository;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ClickService
{
    const UNIQUE_FIELDS = ['ip', 'ua', 'ref', 'param1'];

    const UPDATE_FIELDS = ['bad_domain', 'error'];

    /**
     * @var IClickRepository
     */
    private $clicks;

    /**
     * @var IBadDomainRepository
     */
    private $badDomains;

    /**
     * ClickService constructor.
     * @param IClickRepository $clicks
     * @param IBadDomainRepository $badDomains
     */
    public function __construct(IClickRepository $clicks, IBadDomainRepository $badDomains)
    {
        $this->clicks = $clicks;
        $this->badDomains = $badDomains;
    }

    /**
     * @param Request $request
     * @return Click
     */
    public function handleRequest(Request $request) : Click
    {
        $fields = $this->getDataFromRequest($request);

        try {
            $click = $this->find($fields);
            $fields->put('id', $click->id)->put('error', $click->error + 1);
            $this->update($fields);
        } catch (ModelNotFoundException $modelNotFoundException) {
            $fields->put('id', Uuid::uuid());
            $this->insert($fields);
        }

        return $this->clicks->findOrFail($fields->get('id'));
    }

    /**
     * @param string $referer
     * @return int
     */
    private function getBadDomainIdByReferer(string $referer) : int
    {
        try {
            $parsedUrl = parse_url($referer);

            $badDomain = $this->badDomains->findByAttributes([
                'name' => $parsedUrl['host']
            ]);
            $domainId = $badDomain->id;
        } catch (ModelNotFoundException $modelNotFoundException) {
            $domainId = 0;
        }

        return $domainId;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    private function getDataFromRequest(Request $request) : Collection
    {
        $referer = $request->headers->get('referer');
        $badDomain = $this->getBadDomainIdByReferer($referer);

        $collection = collect([
            'ip'         => $request->ip(),
            'ua'         => $request->userAgent(),
            'ref'        => $referer,
            'param1'     => $request->get('param1'),
            'param2'     => $request->get('param2'),
            'bad_domain' => $badDomain,
            'error'      => $badDomain ? 1 : 0,
        ]);

        return $collection;
    }

    /**
     * @param Collection $fieldsCollection
     */
    private function update(Collection $fieldsCollection)
    {
        $this->clicks->update(
            $fieldsCollection->get('id'),
            $fieldsCollection->only(static::UPDATE_FIELDS)->toArray()
        );
    }

    /**
     * @param Collection $fieldsCollection
     */
    private function insert(Collection $fieldsCollection)
    {
        $this->clicks->create($fieldsCollection->toArray());
    }

    /**
     * @param Collection $fieldsCollection
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function find(Collection $fieldsCollection)
    {
        return $this->clicks->findByAttributes(
            $fieldsCollection->only(static::UNIQUE_FIELDS)->toArray()
        );
    }
}