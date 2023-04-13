<?php

namespace App\Http\Controllers;

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
            'posts' => [
                [
                    'id' => 1,
                    'title' => 'Title 1',
                    'subtitle' => 'Quis hendrerit dolor magna eget est lorem ipsum dolor sit',
                    'created_at' => Carbon::parse('2020-07-19'),
                    'categories' => [],
                ],
                [
                    'id' => 2,
                    'title' => 'Title 2',
                    'subtitle' => 'Senectus et netus et malesuada fames ac turpis egestas integer',
                    'created_at' => Carbon::parse('2020-06-30'),
                    'categories' => [
                        'category 1'
                    ],
                ],
                [
                    'id' => 3,
                    'title' => 'Title 3',
                    'subtitle' => 'Vulputate ut pharetra sit amet aliquam id diam maecenas ultricies',
                    'created_at' => Carbon::parse('2020-06-26'),
                    'categories' => [
                        'category 1',
                        'category 2',
                    ],
                ]
            ]
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
