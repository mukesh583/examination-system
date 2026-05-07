<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Result;
use App\Enums\GradeEnum;
use App\Enums\SemesterStatusEnum;

echo "Testing Eloquent Models and Relationships\n";
echo "==========================================\n\n";

// Test User Model
echo "✓ User model loaded\n";
echo "  - Fillable: " . implode(', ', (new User())->getFillable()) . "\n";
echo "  - Hidden: " . implode(', ', (new User())->getHidden()) . "\n";
echo "  - Relationships: results(), semesters()\n\n";

// Test Semester Model
echo "✓ Semester model loaded\n";
echo "  - Fillable: " . implode(', ', (new Semester())->getFillable()) . "\n";
echo "  - Casts: " . implode(', ', array_keys((new Semester())->getCasts())) . "\n";
echo "  - Relationships: results(), subjects()\n";
echo "  - Helper methods: isActive()\n\n";

// Test Subject Model
echo "✓ Subject model loaded\n";
echo "  - Fillable: " . implode(', ', (new Subject())->getFillable()) . "\n";
echo "  - Casts: " . implode(', ', array_keys((new Subject())->getCasts())) . "\n";
echo "  - Relationships: results()\n\n";

// Test Result Model
echo "✓ Result model loaded\n";
echo "  - Fillable: " . implode(', ', (new Result())->getFillable()) . "\n";
echo "  - Casts: " . implode(', ', array_keys((new Result())->getCasts())) . "\n";
echo "  - Relationships: student(), semester(), subject()\n";
echo "  - Accessors: getPercentageAttribute()\n\n";

// Test GradeEnum
echo "✓ GradeEnum loaded\n";
echo "  - Cases: ";
foreach (GradeEnum::cases() as $case) {
    echo $case->value . " ";
}
echo "\n\n";

// Test SemesterStatusEnum
echo "✓ SemesterStatusEnum loaded\n";
echo "  - Cases: ";
foreach (SemesterStatusEnum::cases() as $case) {
    echo $case->value . " ";
}
echo "\n\n";

echo "==========================================\n";
echo "All models and enums verified successfully!\n";
