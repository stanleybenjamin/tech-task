<?php

namespace App\Application\User\DTOs;

use Spatie\LaravelData\Data;
use App\Domain\Shared\Services\CountryService;
use App\Infrastructure\Repositories\CountryRepository;
use Spatie\LaravelData\Optional;

class UpdateUserDTO extends Data
{
    public function __construct(
        public Optional|string $name,
        public Optional|string $surname,
        public Optional|string $email,
        public Optional|string $phone,
        public Optional|string $country,
        public Optional|string $gender
    ) {}


    /**
     * Summary of rules
     * @param mixed $context
     * @return array{country: string, email: string, gender: string, name: string, phone: string, selfie: string, surname: string}
     */
    public static function rules($context): array
    {
        $countries = (new CountryService(new CountryRepository))
            ->namesOnly();

        return [
            'name' => 'sometimes|required|string|max:64',
            'surname' => 'sometimes|required|string|max:64',
            'email' => 'sometimes|required|email|max:255|unique:users',
            'phone' => 'sometimes|required|string|min:8|max:20',
            'country' => 'sometimes|required|string|in:' . implode(',', $countries),
            'gender' => 'sometimes|required|string|in:male,female'
        ];
    }
}
