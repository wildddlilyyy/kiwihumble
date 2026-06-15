<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_trip_countdown_shell(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('KIWI GROUP Humble Graduation Trip')
            ->assertSee('2027 / 5 / 29')
            ->assertSee('data-countdown', false);
    }
}
