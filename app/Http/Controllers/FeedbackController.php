<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index() {
        return response()->json(Feedback::with('registration')->get());
    }

    public function store(Request $request) {
        $feedback = Feedback::create($request->all());
        return response()->json($feedback, 201);
    }

    public function show($id) {
        return response()->json(Feedback::with('registration')->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $feedback = Feedback::findOrFail($id);
        $feedback->update($request->all());
        return response()->json($feedback);
    }

    public function destroy($id) {
        Feedback::destroy($id);
        return response()->json(null, 204);
    }
}
