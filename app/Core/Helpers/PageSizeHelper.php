<?php

namespace App\Core\Helpers;

use Illuminate\Http\Request;

class PageSizeHelper
{
    /**
     * @param Request $request
     * @return int|mixed
     */
    public static function getPageSize(Request $request): mixed
    {
        return $request->has('pageSize')
            ? ($request->get('pageSize', 500) > 500
                ? 500
                : (
                ($request->get('pageSize') <= 0)
                    ? 500
                    : $request->get('pageSize')
                )
            )
            : 500;
    }
}
