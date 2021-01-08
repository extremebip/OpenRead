<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Contracts\IReaderService;
use App\Models\Requests\Reader\SaveCommentPostRequest;

class ReaderController extends Controller
{
    private $readerService;

    public function __construct(IReaderService $readerService) {
        $this->readerService = $readerService;
    }

    public function index($story_id = null)
    {
        if (is_null($story_id))
            return redirect()->route('home');
        
        $result = $this->readerService->GetStoryByID($story_id);
        if (is_null($result))
            abort(404);

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
