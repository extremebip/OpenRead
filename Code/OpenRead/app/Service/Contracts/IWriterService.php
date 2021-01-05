<?php

namespace App\Service\Contracts;

interface IWriterService
{
    public function GetGenres();
    public function GetStoriesByUsername($username);
    public function CreateStory($data);
    public function GetStoryByID($story_id);
    public function UpdateStory($data);
    public function GetChaptersByStoryID($story_id);
    public function CreateChapter($data);
    public function GetChapterByID($chapter_id);
    public function GetStoryByChapterID($chapter_id);
    public function UpdateChapter($data);
    public function DeleteStory($story_id);
    public function DeleteChapter($chapter_id);
}