<?php

use App\Models\Application;
use App\Models\Interview;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);

    $this->scholarship = Scholarship::create([
        'scholarship_name' => 'Beasiswa Test',
        'scholarship_type' => 'internal',
        'quota' => 10,
        'validity_period' => now()->addYear()->toDateString(),
    ]);

    $this->student1 = Student::create(['name' => 'Mahasiswa Satu', 'student_number' => 'NIM001', 'study_program' => 'Informatika']);
    $this->student2 = Student::create(['name' => 'Mahasiswa Dua', 'student_number' => 'NIM002', 'study_program' => 'Informatika']);

    $this->app1 = Application::create(['student_id' => $this->student1->id, 'scholarship_id' => $this->scholarship->id, 'status' => 'diproses']);
    $this->app2 = Application::create(['student_id' => $this->student2->id, 'scholarship_id' => $this->scholarship->id, 'status' => 'menunggu']);
});

test('create page loads with scholarship options', function () {
    $response = $this->get(route('interviews.create'));

    $response->assertOk();
    $response->assertViewIs('interviews.create');
    $response->assertViewHas('scholarships');
});

test('store creates interviews for all schedulable applicants', function () {
    $this->post(route('interviews.store'), [
        'scholarship_id' => $this->scholarship->id,
        'date_from' => now()->addDay()->format('Y-m-d'),
        'date_to' => now()->addDays(3)->format('Y-m-d'),
        'time_start' => '08:00',
        'time_end' => '17:00',
        'duration_minutes' => 120,
    ])->assertRedirect(route('interviews.index'));

    expect(Interview::count())->toBe(2);
});

test('store assigns unique time slots to each applicant', function () {
    $this->post(route('interviews.store'), [
        'date_from' => now()->addDay()->format('Y-m-d'),
        'date_to' => now()->addDay()->format('Y-m-d'),
        'time_start' => '08:00',
        'time_end' => '12:00',
        'duration_minutes' => 120,
    ])->assertRedirect(route('interviews.index'));

    $schedules = Interview::pluck('schedule');
    expect($schedules)->toHaveCount(2);
    expect($schedules->unique()->count())->toBe(2);
});

test('store skips applicants already having an interview', function () {
    Interview::create(['application_id' => $this->app1->id, 'schedule' => now()->addDay()]);

    $this->post(route('interviews.store'), [
        'date_from' => now()->addDay()->format('Y-m-d'),
        'date_to' => now()->addDays(3)->format('Y-m-d'),
        'time_start' => '08:00',
        'time_end' => '17:00',
        'duration_minutes' => 120,
    ])->assertRedirect(route('interviews.index'));

    expect(Interview::count())->toBe(2); // 1 pre-existing + 1 new for app2
});

test('store returns error when no eligible applicants exist', function () {
    Application::query()->update(['status' => 'diterima']);

    $this->post(route('interviews.store'), [
        'date_from' => now()->addDay()->format('Y-m-d'),
        'date_to' => now()->addDays(3)->format('Y-m-d'),
        'time_start' => '08:00',
        'time_end' => '17:00',
        'duration_minutes' => 120,
    ])->assertRedirect()->assertSessionHas('error');

    expect(Interview::count())->toBe(0);
});

test('store validates required fields', function () {
    $this->post(route('interviews.store'), [])
        ->assertSessionHasErrors(['date_from', 'date_to', 'time_start', 'time_end', 'duration_minutes']);
});

