<?php

namespace App\Service\Contracts;

interface IReaderService
{
    public function GetStoryByID($story_id);
    public function GetChapterByID($chapter_id);
    public function GetCommentsByChapterID($chapter_id);
    public function GetCommentByID($comment_id);
    public function SaveComment($data);
}