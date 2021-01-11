<?php

namespace App\Service\Modules;

use App\Models\DB\Story;
use App\Models\DB\Chapter;
use Illuminate\Support\Str;
use App\Models\DB\StoryGenre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $this->storyGenreRepository = $storyGenreRepository;
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetGenres()
    {
        return $this->genreRepository->FindAll();
    }

    public function GetStoriesByUsername($username)
    {
        $stories = $this->storyRepository->FindAllByUser($username);
        if ($stories->count() == 0){
            return collect();
        }
        $story_ids = $stories->pluck('story_id');
            $story_ratings = $this->ratingRepository->FindAllByStories($story_ids);
            $groupRatingsByStory = $story_ratings->groupBy('story_id');
            $mapStoryRatings = $groupRatingsByStory->mapWithKeys(function ($item, $key)
            {
                $temp = collect($item);
                return [$key => $temp->avg('rate')];
            })->sort();
            return $stories->map(function ($item, $key) use($mapStoryRatings)
            {
                $rate = 0;
                if (isset($mapStoryRatings[$item->story_id]))
                    $rate = $mapStoryRatings[$item->story_id];
                return [
                    'story_id' => $item->story_id,
                    'title' => $item->story_title,
                    'rate' => $rate,
                    'views' => $item->views,
                    'cover' => $item->cover,
                    'synopsis' => $item->sinopsis
                ];
            });
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

            $story->story_id = $this->storyRepository->GetLastInsertID();
            $story->username = $data['username'];
            $story->story_title = $data['story_title'];
            $story->status = 'Ongoing';
            $story->sinopsis = $data['sinopsis'];
            $story->views = 0;

            if (isset($data['cover'])){
                $coverFile = $data['cover'];
                $newFileName = (string) Str::uuid().'.'.$coverFile->extension();
                Storage::putFileAs('cover/', $coverFile, $newFileName);
                $story->cover = $newFileName;
            }

            $result['Story'] = $this->storyRepository->InsertUpdate($story);

            foreach ($data['genres'] as $genre_id) {
                $story_genre = new StoryGenre();
                $story_genre->story_id = $story->story_id;
                $story_genre->genre_id = $genre_id;
                $this->storyGenreRepository->InsertUpdate($story_genre);
            }

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
        $story = $this->storyRepository->Find($story_id);
        if (is_null($story))
            return null;
        
        $story_genres = $this->storyGenreRepository->FindAllByStory($story_id);
        return [
            'story_id' => $story->story_id,
            'username' => $story->username,
            'cover' => $story->cover,
            'story_title' => $story->story_title,
            'sinopsis' => $story->sinopsis,
            'genres' => $story_genres->pluck('genre_id')
        ];
    }

    public function UpdateStory($data)
    {
        $result = [
            'Success' => true,
            'Error Message' => ''
        ];
        DB::beginTransaction();
        try {
            $story = $this->storyRepository->Find($data['story_id']);
            $story->story_title = $data['story_title'];
            $story->sinopsis = $data['sinopsis'];

            if (isset($data['cover'])){
                if (!is_null($story->cover)){
                    $filePath = 'cover/'.$story->cover;
                    if (Storage::exists($filePath))
                        Storage::delete($filePath);
                }

                $coverFile = $data['cover'];
                $newFileName = (string) Str::uuid().'.'.$coverFile->extension();
                Storage::putFileAs('cover/', $coverFile, $newFileName);
                $story->cover = $newFileName;
            }

            $result['Story'] = $this->storyRepository->InsertUpdate($story);

            $this->storyGenreRepository->DeleteAllByStory($data['story_id']);

            foreach ($data['genres'] as $genre_id) {
                $story_genre = new StoryGenre();
                $story_genre->story_id = $story->story_id;
                $story_genre->genre_id = $genre_id;
                $this->storyGenreRepository->InsertUpdate($story_genre);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result['Success'] = false;
            $result['Error Message'] = $e->getMessage();
        }

        return $result;
    }

    public function GetChaptersByStoryID($story_id)
    {
        $chapters = $this->chapterRepository->FindAllByStory($story_id);
        return $chapters->map(function ($item, $key)
        {
            return [
                'index' => intval($key) + 1,
                'chapter_id' => $item->chapter_id,
                'title' => $item->chapter_title
            ];
        });
    }

    public function CreateChapter($data)
    {
        $result = [
            'Success' => true,
            'Error Message' => ''
        ];

        DB::beginTransaction();
        try {
            $chapter = new Chapter();
            $chapter->chapter_id = $this->chapterRepository->GetLastInsertID();
            $chapter->story_id = $data['story_id'];
            $chapter->chapter_title = $data['chapter_title'];
            $chapter->content = $data['content'];

            $result['Chapter'] = $this->chapterRepository->InsertUpdate($chapter);
            if ($data['last_chapter']){
                $story = $this->storyRepository->Find($data['story_id']);
                $story->status = 'Complete';
                $this->storyRepository->InsertUpdate($story);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result['Success'] = false;
            $result['Error Message'] = $e->getMessage();
        }
        return $result;
    }

    public function GetChapterByID($chapter_id)
    {
        $chapter = $this->chapterRepository->Find($chapter_id);
        if (is_null($chapter))
            return null;

        return [
            'chapter_id' => $chapter->chapter_id,
            'story_id' => $chapter->story_id,
            'chapter_title' => $chapter->chapter_title,
            'content' => $chapter->content
        ];
    }

    public function GetStoryByChapterID($chapter_id)
    {
        $chapter = $this->chapterRepository->Find($chapter_id);
        if (is_null($chapter))
            return null;

        return $this->GetStoryByID($chapter->story_id);
    }

    public function UpdateChapter($data)
    {
        $result = [
            'Success' => true,
            'Error Message' => ''
        ];

        DB::beginTransaction();
        try {
            $chapter = $this->chapterRepository->Find($data['chapter_id']);
            $chapter->chapter_title = $data['chapter_title'];
            $chapter->content = $data['content'];

            $result['Chapter'] = $this->chapterRepository->InsertUpdate($chapter);

            if ($data['last_chapter']){
                $story = $this->storyRepository->Find($data['story_id']);
                $story->status = 'Complete';
                $this->storyRepository->InsertUpdate($story);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result['Success'] = false;
            $result['Error Message'] = $e->getMessage();
        }
        return $result;
    }

    public function DeleteStory($story_id)
    {
        return $this->storyRepository->Delete($story_id);
    }

    public function DeleteChapter($chapter_id)
    {
        return $this->chapterRepository->Delete($chapter_id);
    }
}
