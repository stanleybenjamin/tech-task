<?php

namespace Tests\Feature\Shared;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    public function test_retrieve_country_list(): void
    {
        $this->getJson(route('countries.index'))
            ->assertOk()
            ->assertJsonCount(250, 'data');
    }
}
