<?php

namespace App\Traits;

use App\Contracts\Repositories\IClickRepository;
use Illuminate\Support\Facades\Session;

trait TrackingTrait
{
    private function registerVisitTrackingLink()
    {
        Session::put(static::TRACKING_FLAG, true);
    }

    private function abortIfNotTrackingLink()
    {
        if (!Session::exists(static::TRACKING_FLAG)) {
            abort(404);
        }

        Session::forget(static::TRACKING_FLAG);
    }

    /**
     * @param IClickRepository $clicks
     * @param string $id
     * @param string $view
     * @return \Illuminate\View\View
     */
    private function showResponseView(IClickRepository $clicks, string $id, string $view)
    {
        $this->abortIfNotTrackingLink();

        return view('clicks.' . $view, [
            'click' => $clicks->findOrFail($id),
        ]);
    }
}