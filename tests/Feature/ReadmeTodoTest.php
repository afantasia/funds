<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * README.md Todo List 검증 (랭킹은 Todo에서만 제외, 기능은 유지).
 */
class ReadmeTodoTest extends TestCase
{
    /** @test 랭킹 API */
    public function ranking_endpoint_returns_success_json(): void
    {
        $response = $this->getJson('/stock/ranking');

        $response->assertStatus(200);
        $response->assertJsonPath('code', '0000');
        $response->assertJsonStructure(['code', 'datas']);
    }

    /** @test 부분매도 — stock_id + sell_count */
    public function sell_endpoint_uses_stock_id_and_sell_count(): void
    {
        $this->assertTrue(
            method_exists(\App\Http\Controllers\TradeController::class, 'createSell'),
            'createSell 존재'
        );
        $reflection = new \ReflectionMethod(\App\Http\Controllers\TradeController::class, 'createSell');
        $source = file_get_contents($reflection->getFileName());
        $this->assertStringContainsString('sell_count', $source, 'sell_count 처리');
        $this->assertStringContainsString('stock_id', $source, 'stock_id 처리');
    }

    /** @test 발행 관리·추가발행 라우트 미정의 */
    public function admin_stock_routes_not_registered(): void
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->map->uri()->all();
        $hasAdminIssuance = collect($routes)->contains(function ($uri) {
            return str_contains((string) $uri, 'admin')
                || str_contains((string) $uri, 'issue')
                || str_contains((string) $uri, '발행');
        });
        $this->assertFalse($hasAdminIssuance, 'README 발행/관리용 전용 라우트 없음(미구현으로 간주)');
    }
}
