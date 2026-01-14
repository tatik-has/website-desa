<?php

namespace App\LogicTier\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LogicTier\Services\AdminDashboardService; 

class AdminDashboardController extends Controller
{

    protected $dashboardService;

    public function __construct(AdminDashboardService $service) {
        $this->dashboardService = $service;
    }

    public function index() {
        // Memanggil logika dari AdminDashboardService
        $summary = $this->dashboardService->getDashboardSummary();
        $additionalData = $this->dashboardService->getDashboardAdditionalData();
        
        // Mengirim data ke Presentation Tier (View)
        return view('presentation_tier.admin.dashboard', array_merge($summary, $additionalData));
    }
}