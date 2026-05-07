<?php

namespace Tests\Feature\Admin;

use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationAndFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
        ]);
    }

    /** @test */
    public function semesters_index_has_status_filter()
    {
        $this->actingAs($this->admin);

        // Create semesters with different statuses
        Semester::factory()->create(['status' => 'upcoming', 'name' => 'Spring 2025']);
        Semester::factory()->create(['status' => 'current', 'name' => 'Fall 2024']);
        Semester::factory()->create(['status' => 'completed', 'name' => 'Summer 2024']);

        // Test filtering by status
        $response = $this->get(route('admin.semesters.index', ['status' => 'current']));
        
        $response->assertStatus(200);
        $response->assertSee('Fall 2024');
        $response->assertDontSee('Spring 2025');
        $response->assertDontSee('Summer 2024');
    }

    /** @test */
    public function semesters_filter_preserves_search_parameter()
    {
        $this->actingAs($this->admin);

        Semester::factory()->create(['status' => 'current', 'name' => 'Fall 2024']);

        $response = $this->get(route('admin.semesters.index', [
            'search' => 'Fall',
            'status' => 'current'
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Fall 2024');
        // Verify search parameter is preserved in the form
        $response->assertSee('value="Fall"', false);
    }

    /** @test */
    public function subjects_index_has_department_filter()
    {
        $this->actingAs($this->admin);

        // Create subjects with different departments
        Subject::factory()->create(['department' => 'Computer Science', 'name' => 'Data Structures']);
        Subject::factory()->create(['department' => 'Mathematics', 'name' => 'Calculus']);
        Subject::factory()->create(['department' => 'Physics', 'name' => 'Mechanics']);

        // Test filtering by department
        $response = $this->get(route('admin.subjects.index', ['department' => 'Computer Science']));
        
        $response->assertStatus(200);
        $response->assertSee('Data Structures');
        $response->assertDontSee('Calculus');
        $response->assertDontSee('Mechanics');
    }

    /** @test */
    public function subjects_filter_preserves_search_parameter()
    {
        $this->actingAs($this->admin);

        Subject::factory()->create([
            'department' => 'Computer Science',
            'name' => 'Data Structures'
        ]);

        $response = $this->get(route('admin.subjects.index', [
            'search' => 'Data',
            'department' => 'Computer Science'
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Data Structures');
        // Verify search parameter is preserved in the form
        $response->assertSee('value="Data"', false);
    }

    /** @test */
    public function all_index_pages_use_pagination()
    {
        $this->actingAs($this->admin);

        // Create more than 15 records to trigger pagination
        Semester::factory()->count(20)->create();
        Subject::factory()->count(20)->create();
        User::factory()->count(20)->create(['role' => 'student']);

        // Test semesters pagination
        $response = $this->get(route('admin.semesters.index'));
        $response->assertStatus(200);
        $response->assertSee('pagination', false); // Check for pagination HTML

        // Test subjects pagination
        $response = $this->get(route('admin.subjects.index'));
        $response->assertStatus(200);
        $response->assertSee('pagination', false);

        // Test students pagination
        $response = $this->get(route('admin.students.index'));
        $response->assertStatus(200);
        $response->assertSee('pagination', false);
    }

    /** @test */
    public function pagination_preserves_search_parameters()
    {
        $this->actingAs($this->admin);

        // Create 20 semesters with searchable names
        Semester::factory()->count(20)->create(['name' => 'Fall Semester']);

        // Search and go to page 2
        $response = $this->get(route('admin.semesters.index', [
            'search' => 'Fall',
            'page' => 2
        ]));
        
        $response->assertStatus(200);
        // Verify search parameter is in pagination links
        $response->assertSee('search=Fall', false);
    }

    /** @test */
    public function pagination_preserves_filter_parameters()
    {
        $this->actingAs($this->admin);

        // Create 20 semesters with same status
        Semester::factory()->count(20)->create(['status' => 'current']);

        // Filter and go to page 2
        $response = $this->get(route('admin.semesters.index', [
            'status' => 'current',
            'page' => 2
        ]));
        
        $response->assertStatus(200);
        // Verify filter parameter is in pagination links
        $response->assertSee('status=current', false);
    }

    /** @test */
    public function empty_state_shows_no_results_message()
    {
        $this->actingAs($this->admin);

        // Test with no records
        $response = $this->get(route('admin.semesters.index'));
        $response->assertStatus(200);
        $response->assertSee('No semesters found');

        // Test with search that returns no results
        Semester::factory()->create(['name' => 'Fall 2024']);
        $response = $this->get(route('admin.semesters.index', ['search' => 'Spring']));
        $response->assertStatus(200);
        $response->assertSee('No semesters found matching "Spring"');
    }

    /** @test */
    public function empty_state_shows_contextual_messages_for_subjects()
    {
        $this->actingAs($this->admin);

        // Test with no records
        $response = $this->get(route('admin.subjects.index'));
        $response->assertStatus(200);
        $response->assertSee('No subjects found');

        // Test with search that returns no results
        Subject::factory()->create(['name' => 'Mathematics']);
        $response = $this->get(route('admin.subjects.index', ['search' => 'Physics']));
        $response->assertStatus(200);
        $response->assertSee('No subjects found matching "Physics"');
    }

    /** @test */
    public function empty_state_shows_contextual_messages_for_students()
    {
        $this->actingAs($this->admin);

        // Test with no students
        $response = $this->get(route('admin.students.index'));
        $response->assertStatus(200);
        $response->assertSee('No students found');

        // Test with search that returns no results
        User::factory()->create(['role' => 'student', 'name' => 'John Doe']);
        $response = $this->get(route('admin.students.index', ['search' => 'Jane']));
        $response->assertStatus(200);
        $response->assertSee('No students found matching "Jane"');
    }

    /** @test */
    public function clear_filters_link_appears_when_filters_active()
    {
        $this->actingAs($this->admin);

        Semester::factory()->create(['status' => 'current']);

        // Test with filter active
        $response = $this->get(route('admin.semesters.index', ['status' => 'current']));
        $response->assertStatus(200);
        $response->assertSee('Clear Filters');

        // Test without filter
        $response = $this->get(route('admin.semesters.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Clear Filters');
    }

    /** @test */
    public function subjects_department_dropdown_shows_distinct_departments()
    {
        $this->actingAs($this->admin);

        // Create subjects with different departments
        Subject::factory()->create(['department' => 'Computer Science']);
        Subject::factory()->create(['department' => 'Computer Science']); // Duplicate
        Subject::factory()->create(['department' => 'Mathematics']);

        $response = $this->get(route('admin.subjects.index'));
        $response->assertStatus(200);
        
        // Should see each department only once in dropdown
        $content = $response->getContent();
        $this->assertEquals(1, substr_count($content, '<option value="Computer Science"'));
        $this->assertEquals(1, substr_count($content, '<option value="Mathematics"'));
    }
}
