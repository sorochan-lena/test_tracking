<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\IClickRepository;
use App\Services\ClickService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\TrackingTrait;
use App\Traits\RedirectTrait;
use Illuminate\Support\Facades\Session;

class ClickController extends Controller
{
    use TrackingTrait, RedirectTrait;

    const TRACKING_FLAG = 'track';

    const BAD_DOMAIN_FLAG = 'bad_domain';

    const REDIRECT_ERROR_URL = 'http://www.google.com';

    const REDIRECT_TIMEOUT = 5;

    /**
     * @param Request $request
     * @param ClickService $clickService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function click(Request $request, ClickService $clickService)
    {
        try {
            $this->validator($request);
        } catch (ValidationException $validationException) {
            abort(404);
        }

        $click = $clickService->handleRequest($request);
        $this->registerVisitTrackingLink();

        return $this->redirectAfterClick($click);
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    private function validator(Request $request)
    {
        $fieldsToValidate = $request->all();
        $fieldsToValidate['referer'] = $request->headers->get('referer');

        $validator = Validator::make($fieldsToValidate, [
            'param1'  => 'required',
            'param2'  => 'required',
            'referer' => 'required|url',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param $id
     * @param IClickRepository $clicks
     * @return \Illuminate\View\View
     */
    public function success($id, IClickRepository $clicks)
    {
        return $this->showResponseView($clicks, $id, 'success');
    }

    /**
     * @param $id
     * @param IClickRepository $clicks
     * @return \Illuminate\View\View
     */
    public function error($id, IClickRepository $clicks)
    {
        if (Session::get(static::BAD_DOMAIN_FLAG)) {
            header('Refresh: '. static::REDIRECT_TIMEOUT .'; URL=' . static::REDIRECT_ERROR_URL);
        }

        return $this->showResponseView($clicks, $id, 'error');
    }

    /**
     * @param IClickRepository $clicks
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(IClickRepository $clicks)
    {
        return view('clicks.index', [
            'clicks' => $clicks->all()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link()
    {
        $linkTemplate = route('click', [
            'param1' => 'param1_value',
            'param2' => 'param1_value',
        ]);

        return view('clicks.link', [
            'link' => $linkTemplate
        ]);
    }
}
