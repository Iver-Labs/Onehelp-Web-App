<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index() {
        return response()->json(Skill::all());
    }

    public function store(Request $request) {
        $skill = Skill::create($request->all());
        return response()->json($skill, 201);
    }

    public function show($id) {
        return response()->json(Skill::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $skill = Skill::findOrFail($id);
        $skill->update($request->all());
        return response()->json($skill);
    }

    public function destroy($id) {
        Skill::destroy($id);
        return response()->json(null, 204);
    }
}
