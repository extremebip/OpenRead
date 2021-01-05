<?php

namespace App\Service\Modules;

use App\Models\DB\Comment;
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
        $chapters = $this->chapterRepository->FindAllByStory($story_id)->all();
        $ratings = $this->ratingRepository->FindAllByStories([$story_id]);
        return [
            'story' => [
                'story_id' => $story_id,
                'author' => $author->name,
                'cover' => $story->cover,
                'status' => $story->status,
                'views' => $story->views,
                'rating' => $ratings->avg('rate'),
                'genres' => $genres->pluck('chapter_title'),
                'synopsis' => $story->synopsis
            ],
            'chapters' => collect($chapters)->except('content')
        ];
    }

    public function GetChapterByID($chapter_id)
    {
        return $this->chapterRepository->Find($chapter_id);
    }

    public function GetCommentsByChapterID($chapter_id)
    {
        return $this->commentRepository->FindAllByChapter($chapter_id);
    }

    public function GetCommentByID($comment_id)
    {
        return $this->commentRepository->Find($comment_id);
    }

    public function SaveComment($data)
    {
        $comment = null;
        if (isset($data['comment_id']))
            $comment = new Comment();
        else
            $comment = $this->commentRepository->Find($data['comment_id']);
        
        $comment->chapter_id = $data['chapter_id'];
        $comment->username = $data['username'];
        $comment->content = $data['content'];
        return $this->commentRepository->InsertUpdate($comment);
    }
}
