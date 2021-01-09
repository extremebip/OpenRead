<?php

namespace App\Repository\Contracts;

interface ICommentRepository
{
    public function FindAllByChapter($chapter_id);
    public function FindAllByChapterOffsetByLimitBy($chapter_id, $offset = 0, $limit = 10);
    public function FindAllCommentCountByChapter($chapter_id);
}