<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RuleBreachFeatureTest extends TestCase
{


    public function is_method_working()
    {

        $user = User::first();

        $response = $this->actingAs($user)
            ->get('/api/test/ruleBreachRedo');

        $response->assertStatus(200);
    }
}
