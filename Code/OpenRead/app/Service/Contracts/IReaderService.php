<?php

namespace App\Service\Contracts;

interface IReaderService
{
    public function GetStoryByID($story_id);
    public function GetRatingByStoryAndUser($story_id, $username);
    public function RateStory($data);
    public function GetChapterByID($chapter_id);
    public function GetCommentsByChapterID($chapter_id, $offset = 0, $limit = -1);
    public function GetCommentByID($comment_id);
    public function SaveComment($data);
    public function CheckCoverExist($name);
}