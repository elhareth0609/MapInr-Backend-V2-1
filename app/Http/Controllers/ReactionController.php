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
            // 'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
        return response()->json([
            'status' => 0,
            'message' => 'Validation failed : ' . $validator->errors()->first(),
            'error' => $validator->errors()->first(),
        ], 422);
        }

        try {

        $item = Reaction::where('counter_id', $request->counter_id)->where('action', $request->action)->first();
        if ($item) {
            if ($item->action == $request->action) {
                if ($item->user_id != $request->user()->id) {
                    $item->notes .= ', ' . $item->worker->fullname;
                    $item->user_id = $request->user()->id;
                    $item->save();
                }
            } else {
                $reaction = new Reaction();
                $reaction->user_id = $request->user()->id;
                $reaction->counter_id = $request->counter_id;
                $reaction->action = $request->action;
                $reaction->notes = $request->notes;
                $reaction->save();
            }
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

    public function create_lot(Request $request) {

        $validator = Validator::make($request->all(), [
            '*.counter_id' => 'required|numeric',
            '*.action' => 'required|string|in:c,r,d,p',
            // '*.notes' => 'nullable|string',
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
                $item = Reaction::where('counter_id', $data['counter_id'])->where('action', $data['action'])->first();
                if ($item) {
                    if ($item->user_id != $request->user()->id) {
                        $item->notes .= ', ' . $item->worker->fullname;
                        $item->user_id = $request->user()->id;
                        $item->save();
                    }
                } else {
                    $reaction = new Reaction();
                    $reaction->user_id = $request->user()->id;
                    $reaction->counter_id = $data['counter_id'];
                    $reaction->action = $data['action'];
                    $reaction->notes = $data['notes']?? null;
                    $reaction->save();
                }
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

    public function filter(Request $request) {
        $query = Reaction::with('counter') // Eager load counter relationship if needed
        ->where('user_id', $request->user()->id);
    

        if ($request->day) {
            $query->whereDate('created_at', $request->day);
        } 
        else if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date, 
                $request->end_date
            ]);
        }
        else if ($request->place_id) {
            $query->whereHas('counter', function($q) use ($request) {
                $q->where('place_id', $request->place_id);
            });
        }
        
        // Clone the query for getting counts to avoid modifying the original
        $countQuery = clone $query;
        
        $totalActions = new \stdClass();
        $totalActions->c = $countQuery->where('action', 'c')->count();
        $totalActions->r = $countQuery->where('action', 'r')->count();
        $totalActions->d = $countQuery->where('action', 'd')->count();
        $totalActions->p = $countQuery->where('action', 'p')->count();
        
        // Get the actual reactions data
        $reactions = $query->get(['id', 'counter_id', 'action', 'notes', 'created_at']);
        

        // Add place_name instead of place_id
        $reactions = $reactions->map(function ($reaction) {
            return [
                'id' => $reaction->id,
                'counter_id' => $reaction->counter_id,
                'place_id' => $reaction->counter->place->place_id,
                'action' => $reaction->action,
                'notes' => $reaction->notes,
                'created_at' => $reaction->created_at,
            ];
        });

        return response()->json([
            'status' => 1,
            'totalActions' => $totalActions,
            'reactions' => $reactions,
        ]);
    }

    public function destroy(Request $request){
        try {
        // $counter = Counter::where('counter_id', $request->id)->where('place_id','0')->first();
        $reaction = Reaction::find($request->id);
        if (!$reaction) {
            return response()->json([
            'status' => 0,
            'message' => 'There is no counter with this id. ',
            ],422);
        }


        $reaction->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'message' => $e->getMessage(),
        ]);
        }
    }

    public function destroy_lot(Request $request){
        try {
            foreach ($request->all() as $data) {
                $reaction = Reaction::find($data['id']);
                if ($reaction) {
                    $reaction->delete();
                }
            }
        return response()->json([
            'status' => 1,
            'message' => 'Deleted successfully.',
        ]);
        } catch (\Exception $e) {
        return response()->json([
            'status' => 0,
            'message' => $e->getMessage(),
        ]);
        }
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
