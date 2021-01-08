<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Service\Contracts\IReaderService;
use App\Models\Requests\Reader\SaveCommentPostRequest;

class ReaderController extends Controller
{
    private $readerService;

    public function __construct(IReaderService $readerService) {
        $this->readerService = $readerService;
        $this->middleware(['auth', 'throttle:5,1'])->only('rate', 'postComment');
    }

    public function index($story_id = null)
    {
        if (is_null($story_id))
            return redirect()->route('home');
        
        $result = $this->readerService->GetStoryByID($story_id);
        if (is_null($result))
            abort(404);

        if (Auth::check()){
            $result['userRating'] = $this->readerService->GetRatingByStoryAndUser($story_id, Auth::id());
        }
        
        return view('user.reader.story', $result);
    }

    public function chapter($chapter_id = null)
    {
        if (is_null($chapter_id))
            abort(404);

        $result = $this->readerService->GetChapterByID($chapter_id);
        if (is_null($result))
            abort(404);

        $comments = $this->readerService->GetCommentsByChapterID($chapter_id);
        return view('user.reader.chapter', [
            'result' => $result,
            'comments' => $comments
        ]);
    }

    public function viewCover($name = null)
    {
        try {
            if (is_null($name))
                throw new \Exception();

            $result = $this->readerService->CheckCoverExist($name);
            if (!$result['found'])
                throw new \Exception();

            return response()->file($result['path'], ['Content-Type' => 'image/'.$result['ext']]);
        } catch (\Exception $e) {
            return response()->file(public_path('assets/homepage.png'), ['Content-Type' => 'image/png']);
        }
    }

    public function rate(Request $request, $story_id = null)
    {
        $result = [
            'success' => false,
            'message' => ''
        ];
        try {
            if (is_null($story_id))
                throw new \Exception('Invalid story id');

            $data = [
                'story_id' => $story_id,
                'username' => Auth::id(),
                'rate' => $request->rate
            ];
            $result = $this->readerService->RateStory($data);
            if (is_null($result))
                throw new \Exception('Invalid rate input');

            $result['success'] = true;
            return response()->json([
                'result' => $result,
            ], 200);
        } catch (\Exception $e) {
            dd($e);
            $result['message'] = $e->getMessage();
            return response()->json([
                'result' => $result,
            ], 200);
        }
    }

    public function postComment(SaveCommentPostRequest $request)
    {
        $data = $request->validated();
        $comment = $this->readerService->SaveComment($data);
        $comment['photo_url'] = route('preview-image-profile', ['name' => $comment['profile_picture']]);
        return response()->json([
            'comment' => $comment
        ], 200);
    }
}
