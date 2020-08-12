<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeedsTest extends TestCase
{
    /** @test */
    public function displayFeedsList()
    {
        $response = $this->post(route('feeds'));

        $response->assertStatus(302);
    }
    
    /** @test */
    public function displaySingleFeed()
    {
        $response = $this->get(route('feed', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertViewIs('Feeds');
    }
}
