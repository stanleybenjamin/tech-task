<?php

namespace App\Domain\Shared\Services;

use App\Domain\Shared\Repositories\CountryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CountryService
{
    const CACHE_KEY = 'countries';

    public function __construct(
        public CountryRepositoryInterface $repository
    ) {
    }

    public function all(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return collect($this->repository->all())
                ->values()
                ->sortBy('name');
        });
    }

    /**
     *
     * @return array
     */
    public function namesOnly(): array
    {
        return $this->all()
            ->transform(fn($country) => $country['name'])
            ->values()
            ->toArray();
    }
}
