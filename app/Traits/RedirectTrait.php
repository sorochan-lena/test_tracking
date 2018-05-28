<?php

namespace App\Traits;

use App\Click;
use Illuminate\Http\RedirectResponse;

trait RedirectTrait
{
    /**
     * @param Click $click
     * @return RedirectResponse
     */
    private function redirectAfterClick(Click $click) : RedirectResponse
    {
        $redirect = redirect(route('success', $click->id));

        if ($click->bad_domain) {
            $redirect = redirect(route('error', $click->id))->with(static::BAD_DOMAIN_FLAG, true);
        } else if (!$click->bad_domain && $click->error) {
            $redirect = redirect(route('error', $click->id));
        }

        return $redirect;
    }
}