<?php

namespace App\Enums;

/**
 * Semester Status Enum
 * 
 * Represents the status of an academic semester.
 * 
 * @package App\Enums
 */
enum SemesterStatusEnum: string
{
    case CURRENT = 'current';
    case COMPLETED = 'completed';
    case UPCOMING = 'upcoming';
}
