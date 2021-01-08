<?php

namespace App\Service\Modules;

use App\Models\DB\Rating;
use App\Models\DB\Comment;
use Illuminate\Support\Facades\Storage;
use App\Service\Contracts\IReaderService;
use App\Repository\Contracts\IUserRepository;
use App\Repository\Contracts\IGenreRepository;
use App\Repository\Contracts\IStoryRepository;
use App\Repository\Contracts\IRatingRepository;
use App\Repository\Contracts\IChapterRepository;
use App\Repository\Contracts\ICommentRepository;
use App\Repository\Contracts\IStoryGenreRepository;

class ReaderService implements IReaderService
{
    private $chapterRepository;
    private $commentRepository;
    private $genreRepository;
    private $ratingRepository;
    private $storyGenreRepository;
    private $storyRepository;
    private $userRepository;

    public function __construct(
        IChapterRepository $chapterRepository,
        ICommentRepository $commentRepository,
        IGenreRepository $genreRepository,
        IRatingRepository $ratingRepository,
        IStoryGenreRepository $storyGenreRepository,
        IStoryRepository $storyRepository,
        IUserRepository $userRepository
    ) {
        $this->chapterRepository = $chapterRepository;
        $this->commentRepository = $commentRepository;
        $this->genreRepository = $genreRepository;
        $this->ratingRepository = $ratingRepository;
        $this->storyGenreRepository = $storyGenreRepository;
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetStoryByID($story_id)
    {
        $story = $this->storyRepository->Find($story_id);
        if (is_null($story))
            return null;
        
        $author = $this->userRepository->Find($story->username);
        $story_genres = $this->storyGenreRepository->FindAllByStory($story_id);
        $genres = $this->genreRepository->FindAllByIDs($story_genres->pluck('genre_id'));
        $chapters = $this->chapterRepository->FindAllByStory($story_id);
        $ratings = $this->ratingRepository->FindAllByStories([$story_id]);
        return [
            'story' => [
                'story_id' => $story_id,
                'title' => $story->story_title,
                'author' => $author->username,
                'cover' => $story->cover,
                'status' => $story->status,
                'views' => $story->views,
                'rate' => $ratings->avg('rate') ?? 0,
                'genres' => $genres->map(function ($item, $key)
                {
                    return ['genre_id' => $item->genre_id, 'genre_type' => $item->genre_type];
                }),
                'sinopsis' => $story->sinopsis
            ],
            'chapters' => $chapters->map(function ($item, $key)
            {
                return [
                    'index' => intval($key) + 1,
                    'chapter_id' => $item->chapter_id,
                    'title' => $item->chapter_title
                ];
            })
        ];
    }

    public function GetRatingByStoryAndUser($story_id, $username)
    {
        $rating = $this->ratingRepository->FindByStoryAndUser($story_id, $username);
        if (is_null($rating))
            return null;

        return [
            'story_id' => $rating->story_id,
            'username' => $rating->username,
            'rate' => $rating->rate,
        ];
    }

    public function RateStory($data)
    {
        $rateNum = intval($data['rate']);
        if ($rateNum < 1 || $rateNum > 5)
            return null;

        $story = $this->storyRepository->Find($data['story_id']);
        if (is_null($story))
            return null;

        $rating = $this->ratingRepository->FindByStoryAndUser($data['story_id'], $data['username']);
        if (is_null($rating)){
            $rating = new Rating();

            $rating->story_id = $data['story_id'];
            $rating->username = $data['username'];
            $rating->rate = $rateNum;
        }
        else {
            $rating->rate = $rateNum;
        }

        $savedRating = $this->ratingRepository->InsertUpdate($rating);
        if (is_null($savedRating))
            return null;

        $allRatings = $this->ratingRepository->FindAllByStories([$savedRating[0]->story_id]);
        $newRating = sprintf('%.2f', $allRatings->avg('rate') ?? 0); 
        return [
            'rating' => [
                'story_id' => $savedRating[0]->story_id,
                'username' => $savedRating[0]->username,
                'rate' => $savedRating[0]->rate,
            ],
            'new_rate' => $newRating,
        ];
    }

    public function GetChapterByID($chapter_id)
    {
        $chapter = $this->chapterRepository->Find($chapter_id);
        if (is_null($chapter))
            return null;

        $story = $this->storyRepository->Find($chapter->story_id);
        $chapters = $this->chapterRepository->FindAllByStory($story->story_id);
        $index = 0;
        $chapters->each(function ($item, $key) use ($chapter, &$index)
        {
            $index++;
            if ($item->chapter_id == $chapter->chapter_id)
                return false;
        });
        return [
            'story' => [
                'story_id' => $story->story_id,
                'title' => $story->story_title
            ],
            'chapter' => [
                'chapter_id' => $chapter->chapter_id,
                'index' => $index,
                'title' => $chapter->chapter_title,
                'content' => $chapter->content
            ]
        ];
    }

    public function GetCommentsByChapterID($chapter_id)
    {
        $comments = $this->commentRepository->FindAllByChapter($chapter_id);
        $usernames = $comments->pluck('username');
        $users = $this->userRepository->FindAllByUsernames($usernames);
        return $comments->map(function ($item, $key) use ($users)
        {
            $user = $users->firstWhere('username', $item->username);
            return [
                'comment_id' => $item->comment_id,
                'username' => $user->username,
                'author_name' => $user->name,
                'profile_picture' => $user->profile_picture,
                'chapter_id' => $item->chapter_id,
                'content' => $item->content,
            ];
        });
    }

    public function GetCommentByID($comment_id)
    {
        return $this->commentRepository->Find($comment_id);
    }

    public function SaveComment($data)
    {
        $comment = null;
        if (!isset($data['comment_id'])){
            $comment = new Comment();
            $comment->comment_id = $this->commentRepository->GetLastInsertID();
            $comment->chapter_id = $data['chapter_id'];
        }
        else
            $comment = $this->commentRepository->Find($data['comment_id']);
        
        $comment->username = $data['username'];
        $comment->content = $data['content'];
        $newComment = $this->commentRepository->InsertUpdate($comment);
        
        if (is_null($newComment))
            return null;

        $user = $this->userRepository->FindByUsername($newComment->username);
        return [
            'comment_id' => $newComment->comment_id,
            'username' => $user->username,
            'author_name' => $user->name,
            'profile_picture' => $user->profile_picture,
            'chapter_id' => $user->chapter_id,
            'content' => $comment->content,
        ];
    }

    public function CheckCoverExist($name)
    {
        $result = ['found' => false];
        $filePath = 'cover/'.$name;
        if (!Storage::exists($filePath))
            return $result;

        $result['found'] = true;
        $result['path'] = storage_path('app/'.$filePath);
        $result['ext'] = pathinfo($result['path'], PATHINFO_EXTENSION);
        return $result;
    }
}
