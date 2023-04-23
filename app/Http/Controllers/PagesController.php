<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly string $_route = 'pages.',
        private readonly string $_routeView = 'pages.',
        private readonly string $_title = '',
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
        $data = [
            'title' => 'Home',
            'posts' => Post::with('categories')->simplePaginate(5)
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Display post page.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function post(Request $request): View|Factory|Application
    {
        $data = [
            'title' => 'Contact'
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Display contact page.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function contact(Request $request): View|Factory|Application
    {
        $data = [
            'title' => 'Contact'
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }
}
