<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of comments
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['post', 'user', 'moderator']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by post
        if ($request->has('post_id') && $request->post_id) {
            $query->where('post_id', $request->post_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('content', 'like', "%{$searchTerm}%")
                  ->orWhere('guest_name', 'like', "%{$searchTerm}%")
                  ->orWhere('guest_email', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Per page
        $perPage = $request->get('per_page', 25);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 25;
        }

        $comments = $query->latest()->paginate($perPage);
        $posts = BlogPost::select('id', 'title')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $comments->items(),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                    'from' => $comments->firstItem(),
                    'to' => $comments->lastItem(),
                    'links' => $comments->links()->render()
                ]
            ]);
        }

        return view('admin.blog.comments.index', compact('comments', 'posts'));
    }

    /**
     * Show the specified comment
     */
    public function show(BlogComment $comment)
    {
        $comment->load(['post', 'user', 'moderator', 'replies.user']);
        
        return view('admin.blog.comments.show', compact('comment'));
    }

    /**
     * Approve a comment
     */
    public function approve(BlogComment $comment)
    {
        $comment->approve(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil disetujui'
        ]);
    }

    /**
     * Reject a comment
     */
    public function reject(Request $request, BlogComment $comment)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $comment->reject(auth()->id(), $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditolak'
        ]);
    }

    /**
     * Mark comment as spam
     */
    public function spam(BlogComment $comment)
    {
        $comment->markAsSpam(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditandai sebagai spam'
        ]);
    }

    /**
     * Delete a comment
     */
    public function destroy(BlogComment $comment)
    {
        // If comment has replies, soft delete
        if ($comment->replies()->count() > 0) {
            $comment->update([
                'content' => '[Komentar telah dihapus]',
                'status' => 'rejected'
            ]);
        } else {
            $comment->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }

    /**
     * Bulk actions for comments
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,spam,delete',
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id',
            'reason' => 'nullable|string|max:500'
        ]);

        $comments = BlogComment::whereIn('id', $request->comment_ids)->get();
        $count = 0;

        foreach ($comments as $comment) {
            switch ($request->action) {
                case 'approve':
                    $comment->approve(auth()->id());
                    $count++;
                    break;
                case 'reject':
                    $comment->reject(auth()->id(), $request->reason);
                    $count++;
                    break;
                case 'spam':
                    $comment->markAsSpam(auth()->id());
                    $count++;
                    break;
                case 'delete':
                    if ($comment->replies()->count() > 0) {
                        $comment->update([
                            'content' => '[Komentar telah dihapus]',
                            'status' => 'rejected'
                        ]);
                    } else {
                        $comment->delete();
                    }
                    $count++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} komentar berhasil diproses"
        ]);
    }

    /**
     * Get comments data for AJAX
     */
    public function getData(Request $request)
    {
        return $this->index($request);
    }
}
