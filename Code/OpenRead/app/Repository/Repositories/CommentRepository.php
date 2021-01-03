<?php

namespace App\Repository\Repositories;

use App\Models\DB\Comment;
use App\Repository\Base\BaseRepository;
use App\Repository\Contracts\ICommentRepository;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function __construct() {
        parent::__construct(new Comment());
    }

    public function FindAllByChapter($chapter_id)
    {
        return Comment::where('chapter_id', '=', $chapter_id)->get();
    }
}
