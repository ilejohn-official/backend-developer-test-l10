<?php

namespace App\Enums;

enum AchievementType: string
{
    case CommentsWritten = 'comments_written';
    case LessonsWatched = 'lessons_watched';

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
