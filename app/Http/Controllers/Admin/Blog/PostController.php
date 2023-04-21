<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Helpers\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\Post\CreateRequest;
use App\Http\Requests\Admin\Blog\Post\UpdateRequest;
use App\Models\Post;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly string $_route = 'admin.blog.post.',
        private readonly string $_routeView = 'admin.blog.post.',
        private readonly string $_title = 'Post',
    )
    {
        view()->share([
            'route' => $this->_route,
            'title' => $this->_title
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View|Factory|JsonResponse|Application
    {
        if ($request->ajax()) {
            $data = Post::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view($this->_routeView . 'components.action-datatables', compact('data'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $data = [
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Post' => null
            ]
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $data = [
            'title' => 'Create ' . $this->_title,
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Post' => $this->_route . 'index',
                'Create' => null
            ]
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            Post::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Create Data Success',
                'data' => null
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Application|Factory|View
     */
    public function edit(Post $post): View|Factory|Application
    {
        $data = [
            'title' => 'Edit ' . $this->_title,
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Post' => $this->_route . 'index',
                'Edit' => null
            ],
            'post' => $post
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, Post $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $post->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Update Data Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $post->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delete Data Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }
}