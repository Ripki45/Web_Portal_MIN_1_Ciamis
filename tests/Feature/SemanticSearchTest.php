<?php

test('semantic search endpoint returns valid structure for empty query', function () {
    $response = $this->getJson('/api/semantic-search');

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'query',
            'count',
            'results',
        ]);
});

test('semantic search endpoint returns results for a search keyword', function () {
    $response = $this->getJson('/api/semantic-search?q=madrasah');

    $response->assertOk()
        ->assertJson([
            'status' => 'success',
        ]);

    expect($response->json('results'))->toBeArray();
});
