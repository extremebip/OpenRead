<?php

namespace App\Repository\Repositories;

use App\Models\DB\Story;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\IStoryRepository;

class StoryRepository extends BaseRepository implements IStoryRepository
{
    public function __construct() {
        parent::__construct(new Story());
    }

    public function FindAllByUser($username)
    {
        return Story::where('username', '=', $username)->get();
    }

    public function FindAllPaginate($search, $offset, $limit)
    {
        return Story::where('story_title', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
    }

    public function FindAllWhereAboveViewsAverageOrderBy($order_by = null, $dir = 'asc')
    {
        $avg = Story::avg('views') ?? 0;
        return Story::where('views', '>', $avg)
                    ->when($order_by, function ($query, $order_by) use($dir)
                    {
                        return $query->orderBy($order_by, $dir);
                    })
                    ->get();
    }

    public function FindAllOffsetByLimitByOrderBy($offset, $limit, $order_by = null, $dir = 'asc')
    {
        return Story::when($order_by, function ($query, $order_by) use($dir)
        {
            return $query->orderBy($order_by, $dir);
        })
        ->offset($offset)
        ->limit($limit)
        ->get();
    }

    public function FindAllByIDsLimitByOrderBy($story_ids, $limit, $order_by = 'story_id', $dir = 'asc')
    {
        return Story::whereIn('story_id', $story_ids)
                    ->orderBy($order_by, $dir)
                    ->offset(0)
                    ->limit($limit)
                    ->get();
    }

    public function FindAllByUserLimitByOrderBy($username, $limit, $order_by = 'story_id', $dir = 'asc')
    {
        return Story::where('username', '=', $username)
                    ->orderBy($order_by, $dir)
                    ->offset(0)
                    ->limit($limit)
                    ->get();
    }

    public function FindAllStoryCountBySearch($search)
    {
        return Story::where('story_title', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->count();
    }
}
