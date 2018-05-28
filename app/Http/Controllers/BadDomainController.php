<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Contracts\Repositories\IBadDomainRepository;
use Illuminate\Validation\ValidationException;

class BadDomainController extends Controller
{
    /**
     * @param IBadDomainRepository $badDomains
     * @return \Illuminate\View\View
     */
    public function index(IBadDomainRepository $badDomains)
    {
        return view('bad_domains.index', [
            'domains' => $badDomains->all(),
        ]);
    }

    /**
     * @param Request $request
     * @param IBadDomainRepository $badDomains
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, IBadDomainRepository $badDomains)
    {
        $redirect = redirect()->back();

        try {
            $this->validator($request);

            $badDomains->create([
                'name' => $this->getHostByURL($request->bad_domain)
            ]);
        } catch (ValidationException $validationException) {
            $redirect = $redirect->withInput(['bad_domain' => $request->bad_domain])
                ->withErrors($validationException->validator);
        }

        return $redirect;
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    private function validator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bad_domain' => 'required|url'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validator = Validator::make(
            ['bad_domain' => $this->getHostByURL($request->bad_domain)],
            ['bad_domain' => 'required|unique:bad_domains,name']
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function getHostByURL(string $url)
    {
        $url = parse_url($url);

        return $url['host'];
    }
}
