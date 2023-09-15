<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BasePaginatedResourceResponse;
use Illuminate\Support\Arr;

/**
 * Override PaginatedResourceResponse
 */
class PaginatedResourceResponse extends BasePaginatedResourceResponse
{
    /**
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function meta($paginated)
    {
        return Arr::except($paginated, [
            'data',
            'links',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url'
        ]);
    }
}
