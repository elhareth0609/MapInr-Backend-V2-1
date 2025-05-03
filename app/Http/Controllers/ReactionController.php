<?php

namespace App\Http\Controllers;

use App\Models\AudioTransactions;
use App\Models\PhotoTransactions;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Stovrage;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller {

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'counter_id' => 'required|numeric',
            'action' => 'required|string|in:c,r,d,p',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => 'Validation failed : ' . $validator->errors()->first(),
            'error' => $validator->errors()->first(),
        ], 422);
        }

        try {

        $wallet = new Reaction();
        $wallet->user_id = $request->user()->id;
        $wallet->counter_id = $request->counter_id;
        $wallet->action = $request->action;
        $wallet->notes = $request->notes;
        $wallet->save();

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'message' => $e->getMessage(),
        ]);
        }
    }

    public function create_lot(Request $request) {

        $validator = Validator::make($request->all(), [
            '*.counter_id' => 'required|numeric',
            '*.action' => 'required|string|in:c,r,d,p',
            '*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed : ' . $validator->errors()->first(),
                'error' => $validator->errors()->first(),
            ], 422);
        }

        try {

            foreach ($request->all() as $data) {
                $wallet = new Reaction();
                $wallet->user_id = $request->user()->id;
                $wallet->counter_id = $data['counter_id'];
                $wallet->action = $data['action'];
                $wallet->notes = $data['notes'];
                $wallet->save();
            }

        return response()->json([
            'status' => 1,
            'message' => 'Created successfully',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'message' => $e->getMessage(),
        ]);
        }
    }

    public function get(Request $request) {
        $reactions = Reaction::select('id', 'counter_id', 'action', 'notes', 'created_at')
        ->where('user_id', $request->user()->id)
        ->latest('created_at')
        ->get();

        $c = Reaction::where('user_id', $request->user()->id)
        ->where('action', 'c')
        ->count();

        $r = Reaction::where('user_id', $request->user()->id)
        ->where('action', 'r')
        ->count();

        $d = Reaction::where('user_id', $request->user()->id)
        ->where('action', 'd')
        ->count();

        $p = Reaction::where('user_id', $request->user()->id)
        ->where('action', 'p')
        ->count();

        $totalActions = new \stdClass();
        $totalActions->c = $c;
        $totalActions->r = $r;
        $totalActions->d = $d;
        $totalActions->p = $p;
        return response()->json([
        'status' => 1,
        'totalActions' => $totalActions,
        'reactions' => $reactions,
        ]);
    }

    public function delete(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|in:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => __('Validation failed'),
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        try {
            Reaction::findOrFail($id)->delete();

            return response()->json([
                'state' => __("Success"),
                'message' => __("Deleted Successfully"),
            ], 200);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'status' => 1,
                'message' => __('Error'),
                'errors' => $e->getMessage()
            ], 422);
        }

    }

}
