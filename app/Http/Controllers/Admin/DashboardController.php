<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly string $_route = 'admin.dashboard.',
        private readonly string $_routeView = 'admin.dashboard.',
        private readonly string $_title = 'Dashboard',
    )
    {
        view()->share([
            'route' => $this->_route,
            'title' => $this->_title,
        ]);
    }

    /**
     * Display index page.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        return view($this->_routeView . __FUNCTION__);
    }
}
