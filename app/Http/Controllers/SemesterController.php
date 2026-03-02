<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    // GET /api/semesters
    public function index()
    {
        return response()->json(
            Semester::with('course')->latest()->get()
        );
    }

    // POST /api/semesters
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'semester_number' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $semester = Semester::create($validated);

        return response()->json([
            'message' => 'Semester created successfully',
            'data' => $semester
        ], 201);
    }

    // GET /api/semesters/{id}
    public function show($id)
    {
        $semester = Semester::with('course')->findOrFail($id);

        return response()->json($semester);
    }

    // PUT /api/semesters/{id}
    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $validated = $request->validate([
            'course_id' => 'sometimes|exists:courses,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'semester_number' => 'sometimes|required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $semester->update($validated);

        return response()->json([
            'message' => 'Semester updated successfully',
            'data' => $semester
        ]);
    }

    // DELETE /api/semesters/{id}
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

        return response()->json([
            'message' => 'Semester deleted successfully'
        ]);
    }
}