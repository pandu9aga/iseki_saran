<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExportExcelMonthTest extends TestCase
{
    private string $exportPath = '/leader/suggestion/export-excel-month';

    /**
     * Test that the export route is registered.
     */
    public function test_route_is_registered(): void
    {
        $this->assertTrue(
            Route::has('leader.suggestion.exportExcelMonth')
        );
    }

    /**
     * Test that unauthenticated request is redirected to login.
     */
    public function test_export_requires_leader_session(): void
    {
        $response = $this->get($this->exportPath);

        $response->assertRedirect('/login');
    }

    /**
     * Test that authenticated leader gets an Excel download response.
     */
    public function test_export_excel_month_streams_xlsx_for_authenticated_leader(): void
    {
        $response = $this
            ->withSession(['Id_User' => 1])
            ->get($this->exportPath . '?month=2026-05');

        $response->assertStatus(200);
        $response->assertHeader(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        $this->assertStringContainsString(
            'Saran_Bulan_2026-05',
            $response->headers->get('Content-Disposition') ?? ''
        );
    }
}
