<?php

namespace App\Service\Modules;

use App\Service\Contracts\IHomeService;
use App\Repository\Contracts\IUserRepository;
use App\Repository\Contracts\IGenreRepository;
use App\Repository\Contracts\IStoryRepository;
use App\Repository\Contracts\IRatingRepository;
use App\Repository\Contracts\IStoryGenreRepository;

class HomeService implements IHomeService
{
    private $genreRepository;
    private $ratingRepository;
    private $storyGenreRepository;
    private $storyRepository;
    private $userRepository;

    public function __construct(
        IGenreRepository $genreRepository,
        IRatingRepository $ratingRepository,
        IStoryGenreRepository $storyGenreRepository,
        IStoryRepository $storyRepository,
        IUserRepository $userRepository
    ) {
        $this->genreRepository = $genreRepository;
        $this->ratingRepository = $ratingRepository;
        $this->storyGenreRepository = $storyGenreRepository;
        $this->storyRepository = $storyRepository;
        $this->userRepository = $userRepository;
    }

    public function GetTopPicks()
    {
        $getLimit = 4;
        $aboveAverageResult = $this->storyRepository->FindAllWhereAboveViewsAverageOrderBy('views', 'desc');
        $aboveAverageResultCount = $aboveAverageResult->count();
        $belowAverageResult = $this->storyRepository->FindAllOffsetByLimitByOrderBy($aboveAverageResultCount, $getLimit - $aboveAverageResultCount, 'views', 'desc');
        if ($aboveAverageResultCount > $getLimit)
        {
            $story_ids = $aboveAverageResult->pluck('story_id');
            $story_ratings = $this->ratingRepository->FindAllByStories($story_ids);
            $groupRatingsByStory = $story_ratings->groupBy('story_id');
            $mapStoryRatings = $groupRatingsByStory->mapWithKeys(function ($item, $key)
            {
                $temp = collect($item);
                return [$key => $temp->avg('rate')];
            })->sort();
            return $aboveAverageResultCount->whereIn('story_id', $mapStoryRatings->take(4)->keys());
        }
        else if ($aboveAverageResultCount == $getLimit){
            return $aboveAverageResult;
        }
        else {
            return $aboveAverageResult->merge($belowAverageResult);
        }
    }

    public function Search($search, $page = 1)
    {
        $takeLimit = 5;
        $result = [
            'stories' => [],
            'users' => []
        ];
        $stories = $this->storyRepository->FindAllPaginate($search, $page * $takeLimit, $takeLimit);
        if (!is_null($stories) && $stories->count() > 0){
            $story_ids = $stories->pluck('story_id');
            $ratings = $this->ratingRepository->FindAllByStories($story_ids);
            $result['stories'] = $this->MapStoryWithRating($stories, $ratings);
        }
        
        $result['users'] = $this->userRepository->FindAllPaginate($search, 0, $takeLimit)->toArray();
        return $result;
    }

    public function GetStoryByGenre($genre_id)
    {
        $genre = $this->genreRepository->Find($genre_id);
        if (is_null($genre))
            return null;
        
        $result = [
            'recents' => [],
            'most viewed' => []
        ];
        $genre_story = $this->storyGenreRepository->FindAllByGenre($genre_id);
        if ($genre_story->count() == 0)
            return $result;

        $recentLimit = 2;
        $mostViewedLimit = 5;
        $story_ids = $genre_story->pluck('story_id');
        $ratings = $this->ratingRepository->FindAllByStories($story_ids);

        $recentStories = $this->storyRepository->FindAllByIDsLimitByOrderBy($story_ids, $recentLimit, 'story_id', 'desc');
        if ($recentStories->count() > 0){
            $result['recents'] = $this->MapStoryWithRating($recentStories, $ratings);
        }

        $mostViewedStories = $this->storyRepository->FindAllByIDsLimitByOrderBy($story_ids, $recentLimit, 'views', 'desc');
        if ($mostViewedStories->count() > 0){
            $result['most viewed'] = $this->MapStoryWithRating($mostViewedStories, $ratings);
        }
        return $result;
    }

    private function MapStoryWithRating($stories, $ratings)
    {
        if ($stories->count() == 0)
            return [];
        $stories->map(function ($story, $key) use ($ratings)
        {
            $rating = 0;
            $selected_ratings = $ratings->where('story_id', $story->story_id);
            if ($selected_ratings->count() > 0){
                $rating = $selected_ratings->avg('rate');
            }
            return [
                'story_id' => $story->story_id,
                'title' => $story->story_title,
                'author' => $story->username,
                'views' => $story->views,
                'synopsis' => $story->synopsis,
                'rating' => $rating
            ];
        });
    }
}
