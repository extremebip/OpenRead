<?php

namespace App\Service\Modules;

use App\Models\DB\Story;
use App\Models\DB\Chapter;
use Illuminate\Support\Facades\DB;
use App\Service\Contracts\IWriterService;
use App\Repository\Contracts\IUserRepository;
use App\Repository\Contracts\IGenreRepository;
use App\Repository\Contracts\IStoryRepository;
use App\Repository\Contracts\IRatingRepository;
use App\Repository\Contracts\IChapterRepository;
use App\Repository\Contracts\IStoryGenreRepository;

class WriterService implements IWriterService
{
    private $chapterRepository;
    private $genreRepository;
    private $ratingRepository;
    private $storyGenreRepository;
    private $storyRepository;
    private $userRepository;

    public function __construct(
        IChapterRepository $chapterRepository,
        IGenreRepository $genreRepository,
        IRatingRepository $ratingRepository,
        IStoryGenreRepository $storyGenreRepository,
        IStoryRepository $storyRepository,
        IUserRepository $userRepository
    ) {
        $this->chapterRepository = $chapterRepository;
        $this->genreRepository = $genreRepository;
        $this->ratingRepository = $ratingRepository;
        $this->storyGenreRepository = storyGenreRepository;
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetGenres()
    {
        return $this->genreRepository->FindAll();
    }

    public function CreateStory($data)
    {
        $result = [
            'Success' => true,
            'Error Message' => ''
        ];
        DB::beginTransaction();
        try {
            $story = new Story();

            $story->username = $data['username'];
            $story->story_title = $data['story_title'];
            // $story->cover = 
            $story->status = 'Ongoing';
            $story->sinopsis = $data['sinopsis'];
            $story->views = 0;

            $result['Story'] = $this->storyRepository->InsertUpdate($story);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result['Success'] = false;
            $result['Error Message'] = $e->getMessage();
        }
        return $result;
    }

    public function GetStoryByID($story_id)
    {
        return $this->storyRepository->Find($story_id);
    }

    public function UpdateStory($data)
    {
        $story = $this->storyRepository->Find($data['story_id']);

        $story->story_title = $data['story_title'];
        $story->sinopsis = $data['sinopsis'];

        return $this->storyRepository->InsertUpdate($story);
    }

    public function GetStoryDetail($story_id)
    {
        $story = $this->storyRepository->Find($story_id);
        $chapters = $this->chapterRepository->FindAllByStory($story_id);
        $author = $this->userRepository->Find($story->username);
        $ratings = $this->ratingRepository->FindAllByStories([$story_id]);
        return [
            'story' => [
                'story_id' => $story_id,
                'author' => $author->name,
                'cover' => $story->cover,
                'views' => $story->views,
                'rating' => $ratings->avg('rate')
            ],
            'chapters' => $chapters
        ];
    }

    public function CreateChapter($data)
    {
        $chapter = new Chapter();

        $chapter->story_id = $data['story_id'];
        $chapter->chapter_title = $data['chapter_title'];
        $chapter->content = $data['content'];
        
        return $this->chapterRepository->InsertUpdate($chapter);
    }

    public function GetChapterByID($chapter_id)
    {
        return $this->chapterRepository->Find($chapter_id);
    }

    public function UpdateChapter($data)
    {
        $chapter = $this->chapterRepository->Find($data['chapter_id']);

        $chapter->chapter_title = $data['chapter_title'];
        $chapter->content = $data['content'];

        return $this->chapterRepository->InsertUpdate($chapter);
    }
}