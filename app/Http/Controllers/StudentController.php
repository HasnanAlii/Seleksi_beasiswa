<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $data = Student::query()->with(['applications', 'applications.scholarship'])->latest()->get();
        if (request()->ajax()) {
            return response()->json(['data' => $data]);
        }

        return view('students.index', ['data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $student->load(['applications', 'applications.scholarship']),
        ], 201);
    }

    public function show(Student $student): JsonResponse
    {
        return response()->json([
            'data' => $student->load(['applications', 'applications.scholarship']),
        ]);
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $student->update($validated);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'data' => $student->load(['applications', 'applications.scholarship']),
        ]);
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'student_number' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
        ];
    }
}
