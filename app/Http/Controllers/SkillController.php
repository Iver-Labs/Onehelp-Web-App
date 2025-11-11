<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{

    public function index() {
        // Public endpoint - anyone can view skills list
        return response()->json([
            'success' => true,
            'data' => Skill::all()
        ]);
    }

    public function store(Request $request) {
        // Only admins can create skills
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $validated = $request->validate([
            'skill_name' => 'required|string|max:255|unique:skills,skill_name',
            'description' => 'nullable|string|max:500',
        ]);

        $skill = Skill::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill created successfully',
            'data' => $skill
        ], 201);
    }

    public function show($id) {
        // Public endpoint - anyone can view skill details
        $skill = Skill::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $skill
        ]);
    }

    public function update(Request $request, $id) {
        // Only admins can update skills
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'skill_name' => 'sometimes|string|max:255|unique:skills,skill_name,' . $id . ',skill_id',
            'description' => 'nullable|string|max:500',
        ]);

        $skill->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully',
            'data' => $skill
        ]);
    }

    public function destroy($id) {
        // Only admins can delete skills
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        Skill::destroy($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Skill deleted successfully'
        ], 204);
    }
}
