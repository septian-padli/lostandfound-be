<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $comment = Comment::create([
            'id_item'   => $data['itemId'],
            'id_user'   => $data['userId'],
            'content'   => $data['content'],
            'id_parent' => $data['parentId'] ?? null,
        ]);

        return response()->json(['data' => new CommentResource($comment->load('user', 'replies'))], 201);
    }

    public function getByItem(Request $request, string $idItem): JsonResponse
    {
        $limit  = (int) $request->query('limit', 20);
        $cursor = $request->query('cursor');

        $query = Comment::with(['user', 'replies'])
            ->where('id_item', $idItem)
            ->whereNull('id_parent')
            ->orderByDesc('created_at');

        if (!$query->exists()) {
            return response()->json(['errors' => ['message' => 'Item not found']], 404);
        }

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $comments = $query->limit($limit + 1)->get();
        $nextCursor = $comments->count() > $limit ? $comments->pop()->id : null;

        return response()->json([
            'data'       => CommentResource::collection($comments),
            'nextCursor' => $nextCursor,
        ]);
    }

    public function update(UpdateCommentRequest $request, string $idComment): JsonResponse
    {
        $comment = Comment::findOrFail($idComment);
        $comment->update(['content' => $request->validated('content')]);

        return response()->json(['data' => new CommentResource($comment->load('user', 'replies'))]);
    }

    public function destroy(string $idComment): JsonResponse
    {
        $comment = Comment::findOrFail($idComment);
        $comment->deleted_at = now();
        $comment->save();

        return response()->json(['data' => true]);
    }
}
