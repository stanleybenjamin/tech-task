<?php

namespace App\Domain\Shared\Repositories;

use Illuminate\Support\Collection;

interface CountryRepositoryInterface
{
    /**
     *
     * @return Collection
     */
    public function all(): Collection;
}
