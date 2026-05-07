<?php

namespace App\Enums;

/**
 * Grade Enum
 * 
 * Represents the letter grades that can be assigned to examination results.
 * Grades range from A+ (highest) to F (fail).
 * 
 * @package App\Enums
 */
enum GradeEnum: string
{
    case A_PLUS = 'A+';
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case E = 'E';
    case F = 'F';
}
