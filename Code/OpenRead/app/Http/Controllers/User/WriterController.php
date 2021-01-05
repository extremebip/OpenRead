<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Service\Contracts\IWriterService;
use App\Models\Requests\Writer\SaveStoryPostRequest;
use App\Models\Requests\Writer\SaveChapterPostRequest;

class WriterController extends Controller
{
    private $writerService;

    public function __construct(IWriterService $writerService) {
        $this->writerService = $writerService;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.writer.index');
    }

    public function create()
    {
        $genre_selects = $this->writerService->GetGenres()->toDropdown('genre_id', 'genre_type');
        return view('user.writer.story', [
            'genre_selects' => $genre_selects,
            'create' => true,
        ]);
    }

    public function edit($story_id = null)
    {
        if (is_null($story_id))
            return redirect()->route('write-menu');

        $story = $this->writerService->GetStoryByID($story_id);
        if (is_null($story))
            return redirect()->route('write-menu');

        $chapters = $this->writerService->GetChaptersByStoryID($story_id);
        $genre_selects = $this->writerService->GetGenres()->toDropdown('genre_id', 'genre_type');
        return view('user.writer.story', [
            'genre_selects' => $genre_selects,
            'create' => false,
            'story' => $story,
            'chapters' => $chapters
        ]);
    }

    public function save(SaveStoryPostRequest $request)
    {
        $data = $request->validated();
        $result = null;
        if (isset($data['story_id']))
        {
            $result = $this->writerService->UpdateStory($data);
        }
        else 
        {
            $result = $this->writerService->CreateStory($data);
        }
        return redirect()->route('edit-story', ['story_id' => $result['Story']->story_id]);
    }

    public function choose(Request $request)
    {
        $for = $request->query('for');
        $forArray = ['chapter', 'edit'];
        if (is_null($for) || !in_array($for, $forArray))
            return redirect()->route('write-menu');

        $stories = $this->writerService->GetStoriesByUsername(Auth::id());
        if ($for === 'chapter'){
            return view('user.writer.choose', ['for' => 'chapter', 'stories' => $stories]);
        }

        if ($for === 'edit'){
            return view('user.writer.choose', ['for' => 'edit', 'stories' => $stories]);
        }

        return redirect()->route('write-menu');
    }

    public function addChapter(Request $request)
    {
        $story_id = $request->query('s');
        if (is_null($story_id))
            return redirect()->route('choose-story', ['for' => 'chapter']);

        $story = $this->writerService->GetStoryByID($story_id);
        if (is_null($story) || $story['username'] != Auth::id())
            return redirect()->route('choose-story', ['for' => 'chapter']);
        return view('user.writer.chapter', [
            'story' => $story,
            'create' => true,
        ]);
    }

    public function editChapter($chapter_id = null)
    {
        if (is_null($chapter_id))
            return redirect()->route('write-menu');

        $story = $this->writerService->GetStoryByChapterID($chapter_id);
        if (is_null($story) || $story['username'] != Auth::id())
            return redirect()->route('write-menu');

        $chapter = $this->writerService->GetChapterByID($chapter_id);
        return view('user.writer.chapter', [
            'create' => false,
            'story' => $story,
            'chapter' => $chapter
        ]);
    }

    public function saveChapter(SaveChapterPostRequest $request)
    {
        $data = $request->validated();
        $result = null;
        if (isset($data['chapter_id']))
        {
            $result = $this->writerService->UpdateChapter($data);
        }
        else 
        {
            $result = $this->writerService->CreateChapter($data);
        }
        return redirect()->route('edit-story', ['story_id' => $result['Chapter']->story_id]);
    }

    public function delete(Request $request)
    {
        $story = $this->writerService->GetStoryByID($request->story_id);
        if (is_null($story) || $story['username'] != Auth::id())
            return redirect()->route('write-menu');

        $this->writerService->DeleteStory($request->story_id);
        return redirect()->route('choose-story', ['for' => 'edit']);
    }

    public function deleteChapter(Request $request)
    {
        $story = $this->writerService->GetStoryByChapterID($request->chapter_id);
        if (is_null($story) || $story['username'] != Auth::id())
            return redirect()->route('write-menu');
        
        $this->writerService->DeleteChapter($request->chapter_id);
        return redirect()->route('edit-story', ['story_id' => $story['story_id']]);
    }
}
