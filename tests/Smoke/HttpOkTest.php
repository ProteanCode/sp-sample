<?php

namespace Tests\Smoke;

use Tests\TestCase;

class HttpOkTest extends TestCase
{
    public function test_the_root_url_is_ok(): void
    {
        $response = $this->json('GET', '/');

        $response->assertStatus(200);
    }

    public function test_the_list_url_is_ok(): void
    {
        $response = $this->json('GET', '/api/images');

        $response->assertStatus(200);
    }
}
