<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Place;
use App\Models\Counter;
use App\Models\Place_Worker;
use DataTables;

class DataTablesController extends Controller
{
  public function users(Request $request) {
    $users = User::where('role', '!=', 'admin')->get();

    if ($request->ajax()) {
      return DataTables::of($users)
      ->editColumn('id', function($user) {
          return $user->id;
      })
      ->editColumn('fullname', function($user) {
          return $user->fullname;
      })
      ->editColumn('email', function($user) {
          return $user->email;
      })
      ->editColumn('phone', function($user) {
          return $user->phone;
      })
      ->editColumn('status', function($user) {
        if ($user->password === null) {
            return '<span class="badge rounded-pill bg-label-danger me-1">OFF</span>';
        } else {
            return '<span class="badge rounded-pill bg-label-success me-1">ON</span>';
        }
          // return $user->status;
      })
      ->editColumn('created_at', function($user) {
          return $user->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($user) {
        return '
        <a href="' . url("/user/{$user->id}") . '" data-place-id="1"><icon class="mdi mdi-magnify"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#user-delete-modal-' . $user->id . '" data-place-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

      <!-- Modal -->
      <div class="modal fade" id="user-delete-modal-' . $user->id . '" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalCenterTitle">' .  __("User Delete") . '</h4>
            </div>
            <div class="modal-body text-center">
              <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
              <div class="row justify-content-center text-wrap">
                '. __("Do Your Really want to delete This User.") .'
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyUser(' . $user->id . ')">'. __("Submit") .'</button>
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        ';
    })
      ->rawColumns(['status', 'actions'])
      ->make(true);

    }
    return view('dashboard.users.list');
  }

  public function places(Request $request) {
    $places = Place::all();

    if ($request->ajax()) {
      return DataTables::of($places)

      ->editColumn('id', function($place) {
          return $place->id;
      })
      ->editColumn('place_id', function($place) {
          return $place->place_id;
      })
      ->editColumn('longitude', function($place) {
          return $place->longitude;
      })
      ->editColumn('latitude', function($place) {
          return $place->latitude;
      })

      ->editColumn('created_at', function($place) {
          return $place->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($place) {
          return '
            <a href="' . url("/place/{$place->id}") . '" data-place-id="1"><icon class="mdi mdi-magnify"></icon></a>
          ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }
    return view('dashboard.places.list');
  }

  public function counters(Request $request) {
    $counters = Counter::all();
    if ($request->ajax()) {
      return DataTables::of($counters)

      ->editColumn('id', function($counter) {
          return $counter->id;
      })
      ->editColumn('place_id', function($counter) {
          return $counter->place->place_id;
      })
      ->editColumn('name', function($counter) {
        return $counter->name;
    })
      ->editColumn('longitude', function($counter) {
          return $counter->longitude;
      })
      ->editColumn('latitude', function($counter) {
          return $counter->latitude;
      })
      ->editColumn('status', function($counter) {
        if ($counter->status === '0') {
            return '<span class="badge rounded-pill bg-label-danger me-1">In Progress</span>';
        } else {
            return '<span class="badge rounded-pill bg-label-info">Active</span>';
        }
      })
      ->editColumn('created_at', function($counter) {
          return $counter->created_at->format('Y-m-d');
      })
      ->rawColumns(['status'])
      ->make(true);

    }
      return view('dashboard.counters.list');

  }

  public function place_workers($id,Request $request) {
    $workers_ids = Place_Worker::where('place_id', $id)->pluck('worker_id')->toArray();

    $workers = User::whereIn('id', $workers_ids)->get();

    if ($request->ajax()) {
      return DataTables::of($workers)
      ->editColumn('id', function($worker) {
          return $worker->id;
      })
      ->editColumn('fullname', function($worker) {
          return $worker->fullname;
      })
      ->editColumn('email', function($worker) {
          return $worker->email;
      })
      ->editColumn('phone', function($worker) {
          return $worker->phone;
      })
      ->editColumn('status', function($worker) {
        if ($worker->password === null) {
          return '<span class="badge rounded-pill bg-label-danger me-1">OFF</span>';
        } else {
            return '<span class="badge rounded-pill bg-label-success me-1">ON</span>';
        }
          // return $worker->status;
      })
      ->editColumn('created_at', function($worker) {
          return $worker->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($worker) {
        return '
        <a href="' . url("/user/{$worker->id}") . '" data-place-id="1"><icon class="mdi mdi-magnify"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#worker-delete-modal-' . $worker->id . '" data-place-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

      <!-- Modal -->
      <div class="modal fade" id="user-delete-modal-' . $worker->id . '" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalCenterTitle">' .  __("Place Delete") . '</h4>
            </div>
            <div class="modal-body text-center">
              <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
              <div class="row justify-content-center text-wrap">
                '. __("Do Your Really want to delete This Place.") .'
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">'. __("Save changes") .'</button>
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        ';
    })
      ->rawColumns(['actions','status'])
      ->make(true);

    }
    return view('dashboard.places.workers');
  }

  public function place_counters($id,Request $request) {
    $counters = Counter::where('place_id',$id)->get();
    if ($request->ajax()) {
      return DataTables::of($counters)

      ->editColumn('id', function($counter) {
          return $counter->id;
      })
      ->editColumn('place_id', function($counter) {
          return $counter->place->place_id;
      })
      ->editColumn('name', function($counter) {
        return $counter->name;
    })
      ->editColumn('longitude', function($counter) {
          return $counter->longitude;
      })
      ->editColumn('latitude', function($counter) {
          return $counter->latitude;
      })
      ->editColumn('status', function($counter) {
        if ($counter->status === '0') {
            return '<span class="badge rounded-pill bg-label-warning me-1">In Progress</span>';
        } else {
            return '<span class="badge rounded-pill bg-label-info me-1">Active</span>';
        }
      })
      ->editColumn('created_at', function($counter) {
          return $counter->created_at->format('Y-m-d');
      })
      ->rawColumns(['status'])
      ->make(true);

    }
    return view('dashboard.places.counter');
  }

  public function worker_places($id,Request $request) {
    $places_ids = Place_Worker::where('worker_id', $id)->pluck('place_id')->toArray();

    $places = Place::whereIn('id', $places_ids)->get();

    if ($request->ajax()) {
      return DataTables::of($places)

      ->editColumn('id', function($place) {
          return $place->id;
      })
      ->editColumn('place_id', function($place) {
          return $place->place_id;
      })
      ->editColumn('longitude', function($place) {
          return $place->longitude;
      })
      ->editColumn('latitude', function($place) {
          return $place->latitude;
      })
      ->editColumn('created_at', function($place) {
          return $place->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($place) {
          return '
            <a href="' . url("/place/{$place->id}") . '" data-place-id="1"><icon class="mdi mdi-magnify"></icon></a>
          ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }

    return view('dashboard.users.places');
  }
}
