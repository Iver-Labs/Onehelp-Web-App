<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        return response()->json(Notification::all());
    }

    public function store(Request $request) {
        $notification = Notification::create($request->all());
        return response()->json($notification, 201);
    }

    public function show($id) {
        return response()->json(Notification::findOrFail($id));
    }

    public function update(Request $request, $id) {
        $notification = Notification::findOrFail($id);
        $notification->update($request->all());
        return response()->json($notification);
    }

    public function destroy($id) {
        Notification::destroy($id);
        return response()->json(null, 204);
    }
}
