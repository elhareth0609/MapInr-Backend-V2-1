<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Place;
use App\Models\Counter;
use App\Models\Place_Worker;
use App\Models\Municipality;
use App\Models\Worker_Counter;
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
      ->editColumn('counters', function($user) {
        return $user->counters->count();
      })
      ->editColumn('status', function($user) {
        if ($user->password === null) {
          return '<span class="badge rounded-pill bg-label-success me-1">' . __("ON") . '</span>';
        } else {
          return '<span class="badge rounded-pill bg-label-danger me-1">' . __("OFF") . '</span>';
        }
      })
      ->editColumn('created_at', function($user) {
          return $user->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($user) {
        return '
        <a href="' . url("/user/{$user->id}") . '" data-worker-id="1"><icon class="mdi mdi-pen"></icon></a>
        <a href="javascript:void(0);" class="download-btn-user-file" data-worker-id="' . $user->id . '"><icon class="mdi mdi-download"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#user-delete-modal-' . $user->id . '" data-worker-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

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
                '. __("Do You Really want to delete This User.") .'
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

      ->editColumn('place_id', function($place) {
          return $place->place_id;
      })
      ->editColumn('counters', function($place) {
        return $place->counters->count();
      })
      ->editColumn('workers', function($place) {
        return $place->workers->count();
      })
      ->editColumn('created_at', function($place) {
          return $place->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($place) {
          return '
          <a href="' . url("/place/{$place->id}") . '" data-place-id="' . $place->id . '"><icon class="mdi mdi-pen"></icon></a>
          <!-- <a href="javascript:void(0);" class="download-btn" data-place-id="' . $place->place_id . '"><icon class="mdi mdi-download"></icon></a> -->
          ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }
    return view('dashboard.places.list');
  }

  public function counters(Request $request) {

    $counters = Counter::where('status','0')->get();
    if ($request->ajax()) {
      return DataTables::of($counters)
      ->editColumn('worker_id', function($counter) {
        if ($counter->worker) {
          return $counter->worker->fullname;
      } else {
          return __("Unknown");
      }      })
      ->editColumn('longitude', function($counter) {
        return $counter->longitude;
      })
      ->editColumn('latitude', function($counter) {
          return $counter->latitude;
      })
      ->editColumn('phone', function($counter) {
        return $counter->phone;
      })
      ->editColumn('created_at', function($counter) {
          return $counter->created_at->format('Y-m-d');
      })
      // ->rawColumns(['status'])
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
      ->editColumn('counters', function($worker) {
        return $worker->counters->count();
      })
      ->editColumn('status', function($worker) {
        if ($worker->password === null) {
          return '<span class="badge rounded-pill bg-label-success me-1">' . __("ON") . '</span>';
        } else {
          return '<span class="badge rounded-pill bg-label-danger me-1">' . __("OFF") . '</span>';
        }
          // return $worker->status;
      })
      ->editColumn('created_at', function($worker) {
          return $worker->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($worker) use ($id) {
        return '
        <a href="' . url("/user/{$worker->id}") . '" data-worker-id="1"><icon class="mdi mdi-pen"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#worker-place-remove-modal-' . $worker->id . '" data-worker-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

      <!-- Modal -->
      <div class="modal fade" id="worker-place-remove-modal-' . $worker->id . '" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalCenterTitle">' .  __("Worker Remove") . '</h4>
            </div>
            <div class="modal-body text-center">
              <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
              <div class="row justify-content-center text-wrap">
                '. __("Do You Really Want To Remove This Wokrker From Place.") .'
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >'. __("Close") .'</button>
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitRemovePlaceWorker(' . $id .',' . $worker->id . ')">'. __("Submit") .'</button>
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

      ->editColumn('counter_id', function($counter) {
          return $counter->counter_id;
      })
      // ->editColumn('place_id', function($counter) {
      //     return $counter->place->place_id;
      // })
      // ->editColumn('name', function($counter) {
      //   return $counter->name;
      // })
      ->editColumn('longitude', function($counter) {
          return $counter->longitude;
      })
      ->editColumn('latitude', function($counter) {
          return $counter->latitude;
      })
      // ->editColumn('status', function($counter) {
      //   if ($counter->status === '0') {
      //       return '<span class="badge rounded-pill bg-label-danger me-1">' . __("In Progress"). '</span>';
      //   } else {
      //       return '<span class="badge rounded-pill bg-label-info me-1">' . __("Active"). '</span>';
      //   }
      // })
      // ->editColumn('created_at', function($counter) {
      //     return $counter->created_at->format('Y-m-d');
      // })
      // ->rawColumns(['status'])
      ->addColumn('actions', function($counter) {
        $workers = Worker_Counter::where('counter_id', $counter->id)->pluck('worker_id');
        $allworkers = User::pluck('id', 'fullname');
        $html = '
        <a href="javascript:void(0);" data-counter-id="' . $counter->id . '" data-bs-toggle="modal" data-bs-target="#addWorkerCounter-' . $counter->id . '"><icon class="mdi mdi-plus-outline"></icon></a>
        <div class="modal fade" class="addWorkerCounterModale" id="addWorkerCounter-' . $counter->id . '" data-counter-id="' . $counter->id . '" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
          <form class="modal-content" dir="ltr" id="addCounterWorker-' . $counter->id . '" action="' . route("add.counter.worker") .  '" method="POST" enctype="multipart/form-data">';
          $html .= csrf_field();
          $html .= '  <div class="modal-header">
              <h4 class="modal-title" id="backDropModalTitle">' . __("Add Worker") . '</h4>
            </div>
            <div class="modal-body">
              <select class="select-mult" id="select-' . $counter->id . '" multiple="" data-placeholder="Choose Places ..." name="selectedWorkers[]">';

                foreach ($allworkers as $workerName => $workerId) {
                    $html .= '
                    <option
                    value="' .  $workerId . '" ' . (in_array($workerId, $workers->toArray()) ? 'selected' : '') . '>' . $workerName . '</option>

                    ';
                }

              $html .= '</select>
            <input type="hidden" id="counter_id" name="counter_id" value="' . $counter->id . '">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">' . __('Close') . '</button>
              <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" id="submitFormAddUser">' . __('Submit') . '</button>
            </div>
          </form>
        </div>
      </div>
        ';
        return $html;
    })

    ->rawColumns(['actions'])
    ->make(true);


    }
    return view('dashboard.places.counter');
  }

  public function worker_places($id,Request $request) {
    $places_ids = Place_Worker::where('worker_id', $id)->where('place_id','!=','0')->pluck('place_id')->toArray();

    $places = Place::whereIn('id', $places_ids)->get();

    if ($request->ajax()) {
      return DataTables::of($places)

      // ->editColumn('id', function($place) {
      //     return $place->id;
      // })
      ->editColumn('place_id', function($place) {
          return $place->place_id;
      })
      ->editColumn('counters', function($place) {
        return $place->counters->count();
      })
      ->editColumn('workers', function($place) {
        return $place->workers->count();
      })

      // ->editColumn('created_at', function($place) {
      //     return $place->created_at->format('Y-m-d');
      // })
      ->addColumn('actions', function($place) use ($id) {
          return '
            <a href="' . url("/place/{$place->id}") . '" data-place-id="1"><icon class="mdi mdi-pen"></icon></a>
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#worker-place-remove-modal-' . $place->id . '" data-place-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

            <!-- Modal -->
            <div class="modal fade" id="worker-place-remove-modal-' . $place->id . '" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="modalCenterTitle">' .  __("Place Remove") . '</h4>
                  </div>
                  <div class="modal-body text-center">
                    <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
                    <div class="row justify-content-center text-wrap">
                      '. __("Do You Really Want To Remove This Place From Worker.") .'
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >'. __("Close") .'</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitRemovePlaceWorker(' . $place->id . ','. $id .')">'. __("Submit") .'</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

          ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }

    return view('dashboard.users.places');
  }

  public function worker_counters($id,Request $request) {
    $counters = Counter::where('counters.worker_id', $id)
    ->orWhere('worker__counters.worker_id', $id)
    ->leftJoin('worker__counters', 'counters.id', '=', 'worker__counters.counter_id')
    ->select('counters.counter_id', 'counters.longitude', 'counters.latitude', 'counters.status', 'counters.created_at', 'counters.id', 'counters.phone','counters.name')
    ->get();



    if ($request->ajax()) {
      return DataTables::of($counters)

      ->editColumn('counter_id', function($counter) {
          return $counter->counter_id;
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
        if ($counter->status == '0') {
            return '<span class="badge rounded-pill bg-label-danger me-1">' . __("In Progress"). '</span>';
        } else {
            return '<span class="badge rounded-pill bg-label-info">' . __("Active"). '</span>';
        }
      })
      ->editColumn('created_at', function($counter) {
          return $counter->created_at->format('Y-m-d');
      })
      ->addColumn('actions', function($counter) use ($id){
        return '
        <a href="javascript:void(0);" class="download-btn" data-counter-id="' . $counter->id . '" data-bs-toggle="modal" data-bs-target="#worker-counter-remove-modal-' . $counter->id . '"><icon class="mdi mdi-trash-can-outline"></icon></a>
        <!-- Modal -->
        <div class="modal fade" id="worker-counter-remove-modal-' . $counter->id . '" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modalCenterTitle">' .  __("Counter Remove") . '</h4>
              </div>
              <div class="modal-body text-center">
                <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
                <div class="row justify-content-center text-wrap">
                  '. __("Do You Really Want To Remove This Counter From Worker.") .'
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >'. __("Close") .'</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitRemoveCounterWorker(' . $id .',' . $counter->id . ')">'. __("Submit") .'</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        ';
      })
      ->rawColumns(['status','actions'])
      ->make(true);

    }

    return view('dashboard.users.counters');
  }

  public function municipalitys(Request $request) {
    $municipalitys = Municipality::where('id','!=', 0)->get();

    if ($request->ajax()) {
      return DataTables::of($municipalitys)

      ->editColumn('id', function($municipality) {
          return $municipality->id;
      })
      ->editColumn('places', function($municipality) {
        return $municipality->places->count();
      })
      ->addColumn('actions', function($municipality) {
        return '
        <a href="' . url("/municipality/{$municipality->id}") . '" data-municipality-id="' . $municipality->id . '"><icon class="mdi mdi-pen"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#municipality-delete-modal-' . $municipality->id . '" data-municipality-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>
        <!-- <a href="javascript:void(0);" class="download-btn" data-municipality-id="' . $municipality->id . '" onclick="downloadOnesMunicipality(' . $municipality->id . ')"><icon class="mdi mdi-download"></icon></a> -->

          <!-- Modal -->
          <div class="modal fade" id="municipality-delete-modal-' . $municipality->id . '" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modalCenterTitle">' .  __("Municipality Delete") . '</h4>
                </div>
                <div class="modal-body text-center">
                  <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
                  <div class="row justify-content-center text-wrap">
                    '. __("Do You Really want to delete This Municipality.") .'
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyUser(' . $municipality->id . ')">'. __("Submit") .'</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
              ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }
    return view('dashboard.municipalitys.list');
  }
  public function municipality_places($id,Request $request) {
    $places = Place::where('municipality_id',$id)->get();
    $municipality = Municipality::find($id);
    if ($request->ajax()) {
      return DataTables::of($places)

      ->editColumn('place_id', function($place) {
          return $place->place_id;
      })
      ->editColumn('counters', function($place) {
        return $place->counters->count();
      })
      ->editColumn('workers', function($place) {
        return $place->workers->count();
      })
      ->addColumn('actions', function($place) {
          return '
          <a href="' . url("/place/{$place->id}") . '" data-place-id="' . $place->id . '"><icon class="mdi mdi-pen"></icon></a>
          <!-- <a href="javascript:void(0);" class="download-btn" data-place-id="' . $place->place_id . '"><icon class="mdi mdi-download"></icon></a> -->
          <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#place-delete-modal-' . $place->id . '" data-place-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

          <!-- Modal -->
          <div class="modal fade" id="place-delete-modal-' . $place->id . '" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="modalCenterTitle">' .  __("Place Delete") . '</h4>
                </div>
                <div class="modal-body text-center">
                  <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
                  <div class="row justify-content-center text-wrap">
                    '. __("Do You Really want to delete This Place.") .'
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyPlace(' . $place->id . ')">'. __("Submit") .'</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

          ';
        })
      ->rawColumns(['actions'])
      ->make(true);

    }

    return view('dashboard.municipalitys.places')
    ->with('municipality',$municipality);
  }
}
