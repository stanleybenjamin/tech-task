<?php

namespace App\Application\User\DTOs;

use App\Domain\Shared\Services\CountryService;
use App\Infrastructure\Repositories\CountryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Data;

class CreateUserDTO extends Data
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $phone,
        public string $country,
        public string $gender,
        public string $password,
        public string $password_confirmation,
        public ?UploadedFile $selfie
    ) {}

    public static function rules($context): array
    {
        $countries = (new CountryService(new CountryRepository))
            ->namesOnly();

        return [
            'name' => 'required|string|max:64',
            'surname' => 'required|string|max:64',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|min:8|max:20',
            'country' => 'required|string|in:' . implode(',', $countries),
            'gender' => 'required|string|in:male,female',
            'selfie' => 'nullable|image|max:1024|mimes:jpeg,jpg,png,gif,webp',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::defaults()
            ]
        ];
    }
}
