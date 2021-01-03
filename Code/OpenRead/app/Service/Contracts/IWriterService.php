<?php

namespace App\Service\Contracts;

interface IWriterService
{
    public function GetGenres();
    public function CreateStory($data);
    public function GetStoryByID($story_id);
    public function UpdateStory($data);
    public function GetStoryDetail($story_id);
    public function CreateChapter($data);
    public function GetChapterByID($chapter_id);
    public function UpdateChapter($data);
}