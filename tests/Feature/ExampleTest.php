<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_los_invitados_son_redirigidos_al_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}