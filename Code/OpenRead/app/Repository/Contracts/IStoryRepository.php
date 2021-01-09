<?php

namespace App\Repository\Contracts;

interface IStoryRepository
{
    public function FindAllByUser($username);
    public function FindAllPaginate($search, $offset, $limit);
    public function FindAllWhereAboveViewsAverageOrderBy($order_by = null, $dir = 'asc');
    public function FindAllOffsetByLimitByOrderBy($offset, $limit, $order_by = null, $dir = 'asc');
    public function FindAllByIDsLimitByOrderBy($story_ids, $limit, $order_by = 'story_id', $dir = 'asc');
    public function FindAllByUserLimitByOrderBy($username, $limit, $order_by = 'story_id', $dir = 'asc');
    public function FindAllStoryCountBySearch($search);
}