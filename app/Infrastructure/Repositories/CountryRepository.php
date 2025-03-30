<?php

namespace App\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use App\Domain\Shared\Repositories\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    /**
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return collect(countries());
    }
}
