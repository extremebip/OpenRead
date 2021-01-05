<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function edit()
    {
        $genre_selects = $this->writerService->GetGenres()->toDropdown('genre_id', 'genre_type');
        return view('user.writer.story', [
            'genre_selects' => $genre_selects,
            'create' => false,
        ]);
    }

    public function save(SaveStoryPostRequest $request)
    {
        $data = $request->validated();
        if (isset($data['story_id']))
        {
            $result = $this->writerService->UpdateStory($data);
        }
        else 
        {
            $result = $this->writerService->CreateStory($data);
        }
        return redirect()->route('home');
    }

    public function chooseChapter()
    {
        
    }

    public function addChapter()
    {
        return view('user.writer.chapter', [
            'create' => true,
        ]);
    }

    public function editChapter()
    {
        return view('user.writer.chapter', [
            'create' => false,
        ]);
    }

    public function saveChapter(SaveChapterPostRequest $request)
    {
        $data = $request->validated();
        if (isset($data['story_id']))
        {
            $this->writerService->CreateChapter($data);
        }
        else 
        {
            $this->writerService->UpdateChapter($data);
        }
        return redirect()->route('');
    }
}
