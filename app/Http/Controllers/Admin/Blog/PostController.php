<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Helpers\File;
use App\Helpers\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\Post\CommentCreateRequest;
use App\Http\Requests\Admin\Blog\Post\CommentUpdateRequest;
use App\Http\Requests\Admin\Blog\Post\CreateRequest;
use App\Http\Requests\Admin\Blog\Post\ReportApproveRequest;
use App\Http\Requests\Admin\Blog\Post\ReportRequest;
use App\Http\Requests\Admin\Blog\Post\UpdateRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
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
        private readonly string $_pathFileHeadlineImage = 'public/files/blogs/headlines/',
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
            $data = Post::with('headlineImage')->newQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('headline_image', function ($data) {
                    return view($this->_routeView . 'components.headline-image-datatables', compact('data'));
                })
                ->addColumn('category', function ($data) {
                    return $data->categories->pluck('title')->join(', ');
                })
                ->addColumn('action', function ($data) {
                    return view($this->_routeView . 'components.action-datatables', compact('data'));
                })
                ->rawColumns(['headline_image', 'action'])
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
            ],
            'categories' => Category::getAllWithType('post')
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

            $file = File::save([
                'origin' => 'public',
                'file' => $data['headline_image'],
                'path' => $this->_pathFileHeadlineImage
            ]);

            unset($data['headline_image']);

            $data['headline_image_id'] = $file->id;

            Post::create($data)
                ->categories()
                ->sync(array_fill_keys($data['categories'], [
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Create Data Success',
                'data' => null
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            if ($file) File::delete($file->path . $file->hash_name);

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
            'post' => $post->with('headlineImage')->find($post->id),
            'categories' => Category::getAllWithType('post')
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

            if (!empty($data['headline_image'])) {
                $file = File::save([
                    'id' => $post->headline_image_id,
                    'origin' => 'public',
                    'file' => $data['headline_image'],
                    'path' => $this->_pathFileHeadlineImage
                ]);

                unset($data['headline_image']);

                $data['headline_image_id'] = $file->id;
            }

            $post->update($data);
            $post->categories()
                ->sync(array_fill_keys($data['categories'], [
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Update Data Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            if ($file && !empty($data['headline_image'])) File::delete($file->path . $file->hash_name);

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

            if ($post->headline_image_id) File::delete($post->headline_image_id);

            $post->categories()->sync([]);
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

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function getAllComment(Request $request, Post $post): JsonResponse
    {
        try {
            $data = Comment::with(['children', 'creator'])->where([
                'parent_id' => null,
                'commentable_id' => $post->id,
                'commentable_type' => Post::class
            ])->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Get All Comment Success',
                'data' => $this->convertCommentTohierarchy($data)
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Store a new comment resource in storage.
     *
     * @param CommentCreateRequest $request
     * @param Post $post
     * @return JsonResponse
     * @throws Exception
     */
    public function storeComment(CommentCreateRequest $request, Post $post): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $post->comments()->create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comment Success',
                'data' => null
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Update the comment resource in storage.
     *
     * @param CommentUpdateRequest $request
     * @param Post $post
     * @param $commentUUID
     * @return JsonResponse
     * @throws Exception
     */
    public function updateComment(CommentUpdateRequest $request, Post $post, $commentUUID): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $comment = Comment::where('uuid', $commentUUID)->firstOrFail();
            $comment->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Update Comment Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Remove the comment resource from storage.
     *
     * @param Request $request
     * @param Post $post
     * @param $commentUUID
     * @return JsonResponse
     * @throws Exception
     */
    public function destroyComment(Request $request, Post $post, $commentUUID): JsonResponse
    {
        try {
            DB::beginTransaction();

            $comment = Comment::where('uuid', $commentUUID)->firstOrFail();
            $this->deleteCommentRecursive($comment->id);
            $comment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Remove Comment Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Report the comment resource from storage.
     *
     * @param ReportRequest $request
     * @param Post $post
     * @param $commentUUID
     * @return JsonResponse
     * @throws Exception
     */
    public function reportComment(ReportRequest $request, Post $post, $commentUUID): JsonResponse
    {
        try {
            DB::beginTransaction();

            $comment = Comment::where('uuid', $commentUUID)->firstOrFail();

            $data = $request->validated();

            $comment->reports()->create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Report Comment Success',
                'data' => null
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Display a listing of the comment report.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function commentReportIndex(Request $request): View|Factory|JsonResponse|Application
    {
        view()->share([
            'route' => 'admin.blog.post.comment.report.',
            'title' => 'Comment Report'
        ]);

        if ($request->ajax()) {
            $data = Report::with('creator')
                ->where(['reportable_type' => Comment::class])
                ->newQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('report', function ($data) {
                    return str($data->report ?? '')->limit(50);
                })
                ->addColumn('creator', function ($data) {
                    return $data->creator->name ?? 'Anonymous';
                })
                ->addColumn('status', function ($data) {
                    return match ($data->is_approved) {
                        1 => 'Approved',
                        0 => 'Rejected',
                        default => 'Waiting for Approve'
                    };
                })
                ->addColumn('action', function ($data) {
                    return view($this->_routeView . 'components.comment-report-action-datatables', compact('data'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $data = [
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Comment Report' => null
            ]
        ];

        return view($this->_routeView . str(__FUNCTION__)->snake('-'), $data);
    }

    /**
     * Show the form for approval the specified comment report.
     *
     * @param Report $report
     * @return Application|Factory|View
     */
    public function commentReportShow(Report $report): View|Factory|Application
    {
        view()->share([
            'route' => 'admin.blog.post.comment.report.',
            'title' => 'Comment Report'
        ]);

        $data = [
            'title' => 'Show Comment Report',
            'breadcrumbs' => [
                'Dashboard' => RouteServiceProvider::HOME,
                'Comment Report' => 'admin.blog.post.comment.report.index',
                'Show' => null
            ],
            'report' => $report
        ];

        return view($this->_routeView . str(__FUNCTION__)->snake('-'), $data);
    }

    /**
     * Update the specified comment report in storage.
     *
     * @param ReportApproveRequest $request
     * @param Report $report
     * @return JsonResponse
     * @throws Exception
     */
    public function commentReportApprove(ReportApproveRequest $request, Report $report): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            if (!($report->reportable instanceof Comment)) throw new Exception('Invalid Type of Report Data');

            if ($data['is_approved']) {
                $this->deleteCommentRecursive($report->reportable->id);
                $report->reportable->delete();
            }

            $report->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Approve Data Success',
                'data' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            Log::exception($e, __METHOD__);

            throw $e;
        }
    }

    /**
     * Convert comment to hierarchy.
     *
     * @param $data
     * @return mixed
     */
    private function convertCommentToHierarchy($data): mixed
    {
        foreach ($data as $datum) {
            $datum = Comment::with('children')->find($datum->id);

            if ($datum->children->isNotEmpty()) {
                $datum->children = $this->convertCommentToHierarchy($datum->children);
            }
        }

        return $data;
    }

    /**
     * Delete comment with recursive.
     *
     * @param $id
     * @return void
     */
    private function deleteCommentRecursive($id): void
    {
        $data = Comment::with('children')->where('parent_id', $id)->get();

        foreach ($data as $datum) {
            $datum = Comment::with('children')->find($datum->id);

            if ($datum->children->isNotEmpty()) {
                $this->deleteCommentRecursive($datum->children);
            }

            $datum->delete();
        }
    }
}
