<?php

namespace Tests;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_api_get_test(): void
    {
        $this->get('/blockchain/show');

        $this->assertResponseStatus(200);
    }

    public function test_api_create_test(): void
    {
        $this->post('/blockchain/create', ['data' => 'test block']);

        $this->assertResponseStatus(201);
    }
}
