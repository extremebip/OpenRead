<?php

namespace App\Repository\Contracts;

interface ICommentRepository
{
    public function FindAllByChapter($chapter_id);
}