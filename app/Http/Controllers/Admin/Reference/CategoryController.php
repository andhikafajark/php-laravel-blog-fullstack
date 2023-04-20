<?php

namespace App\Http\Controllers\Admin\Reference;

use App\Helpers\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Reference\Category\CreateRequest;
use App\Http\Requests\Admin\Reference\Category\UpdateRequest;
use App\Models\Category;
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

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly string $_route = 'admin.reference.category.',
        private readonly string $_routeView = 'admin.reference.category.',
        private readonly string $_title = 'Category',
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
            $data = Category::query();

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
                'Category' => null
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
                'Category' => $this->_route . 'index',
                'Create' => null
            ],
            'types' => ['post']
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
            $data['slug'] = str($data['title'])->slug();

            Category::create($data);

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
     * @param Category $category
     * @return Application|Factory|View
     */
    public function edit(Category $category): View|Factory|Application
    {
        $data = [
            'title' => 'Edit ' . $this->_title,
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Category' => $this->_route . 'index',
                'Edit' => null
            ],
            'category' => $category,
            'types' => ['post']
        ];

        return view($this->_routeView . __FUNCTION__, $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Category $category
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateRequest $request, Category $category): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['slug'] = str($data['title'])->slug();

            $category->update($data);

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
     * @param Category $category
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        try {
            DB::beginTransaction();

            $category->delete();

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
