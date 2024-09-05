<?php

namespace App\Http\Controllers;

use App\Models\Counter;

use App\Models\Municipality;
use App\Models\Phone;
use App\Models\Place;
use App\Models\Place_Worker;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Worker_Counter;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataTablesController extends Controller {
  public function users(Request $request) {
    $users = User::where('role', '!=',  'admin')->get();

    if ($request->ajax()) {
      return DataTables::of($users)
      ->editColumn('id', function($user) {
          return (string) $user->id;
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
        return (string) $user->counters->count();
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
          <a href="' . url("/user/{$user->id}") . '" data-worker-id="' . $user->id . '"><icon class="mdi mdi-pen"></icon></a>
          <a href="javascript:void(0);" class="download-btn-user-file" data-worker-id="' . $user->id . '"><icon class="mdi mdi-download"></icon></a>
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
          return (string) $place->place_id;
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
      ->editColumn('counter_id', function($counter) {
        return (string) $counter->counter_id;
      })
      ->editColumn('name', function($counter) {
        return $counter->name;
      })
      ->editColumn('worker_id', function($counter) {
        if ($counter->worker) {
          return $counter->worker->fullname;
        } else {
          return __("Unknown");
        }
      })
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
      ->editColumn('audio', function($counter) {
        if ($counter->audio) {
          return '<a type="button" onclick="togglePlay(' . $counter->id . ')">
            <i class="mdi mdi-play-circle-outline" id="play-icon-' . $counter->id . '"></i>
          </a>
          <audio id="audio-' . $counter->id . '">
            <source src="' . asset('storage/assets/audio/counters/' . $counter->audio) . '" type="audio/mpeg">
          </audio>';

          // return '<a href="#" type="button" data-bs-toggle="modal" data-bs-target="#audio-modal-' . $counter->id . '">
          // <i class="mdi mdi-play-circle-outline"></i>
          // </a>

          // <!-- Modal -->
          // <div class="modal fade" id="audio-modal-' . $counter->id . '" tabindex="-1" aria-hidden="true">
          //   <div class="modal-dialog modal-dialog-centered" role="document">
          //     <div class="modal-content">
          //       <div class="modal-header">
          //         <h4 class="modal-title" id="modalCenterTitle">' . __("Audio for Counter") . ' ' . $counter->name . '</h4>
          //       </div>
          //       <div class="modal-body text-center">
          //         <audio controls>
          //           <source src="' . asset('storage/assets/audio/counters/' . $counter->audio) . '" type="audio/mpeg">
          //         </audio>
          //         <div class="mt-3">
          //           <input type="number" class="form-control" id="audio-number-' . $counter->id . '" placeholder="' . __("Enter number") . '">
          //         </div>
          //       </div>
          //       <div class="modal-footer">
          //         <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">' . __("Close") . '</button>
          //         <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="saveAudioNumber(' . $counter->id . ')">' . __("Save") . '</button>
          //       </div>
          //     </div>
          //   </div>
          // </div>';
        }
      })
      ->rawColumns(['audio'])

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
          return (string) $worker->id;
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
        <a href="' . url("/user/{$worker->id}") . '" data-worker-id="' . $worker->id . '"><icon class="mdi mdi-pen"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#worker-place-remove-modal-' . $worker->id . '" data-worker-id="' . $worker->id . '"><icon class="mdi mdi-trash-can-outline"></icon></a>

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
    $counters = Counter::where('place_id', $id)->get();

        if ($request->ajax()) {
      return DataTables::of($counters)
      ->editColumn('counter_id', function($counter) {
        return (string) $counter->counter_id;
      })
      ->editColumn('longitude', function($counter) {
          return $counter->longitude;
      })
      ->editColumn('latitude', function($counter) {
          return $counter->latitude;
      })
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
          return (string) $place->place_id;
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
            <a href="' . url("/place/{$place->id}/counters") . '" data-place-id="1"><icon class="mdi mdi-pen"></icon></a>
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
    ->select('counters.counter_id', 'counters.longitude', 'counters.latitude', 'counters.status', 'counters.created_at', 'counters.id', 'counters.phone','counters.name','counters.audio')
    ->get();



    if ($request->ajax()) {
      return DataTables::of($counters)

      ->editColumn('counter_id', function($counter) {
          return (string) $counter->counter_id;
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
      ->editColumn('audio', function($counter) {
        if ($counter->audio) {
          return '<a type="button" onclick="togglePlay(' . $counter->id . ')">
          <i class="mdi mdi-play-circle-outline" id="play-icon-' . $counter->id . '"></i>
        </a>
        <audio id="audio-' . $counter->id . '">
          <source src="' . asset('storage/assets/audio/counters/' . $counter->audio) . '" type="audio/mpeg">
        </audio>';

          // return '<a href="#" type="button" data-bs-toggle="modal" data-bs-target="#audio-modal-' . $counter->id . '">
          //           <i class="mdi mdi-play-circle-outline"></i>
          //         </a>

          // <!-- Modal -->
          // <div class="modal fade" id="audio-modal-' . $counter->id . '" tabindex="-1" aria-hidden="true">
          //   <div class="modal-dialog modal-dialog-centered" role="document">
          //     <div class="modal-content">
          //       <div class="modal-header">
          //         <h4 class="modal-title" id="modalCenterTitle">' . __("Audio for Counter") . ' ' . $counter->name . '</h4>
          //       </div>
          //       <div class="modal-body text-center">
          //         <audio controls>
          //           <source src="' . asset('storage/assets/audio/counters/' . $counter->audio) . '" type="audio/mpeg">
          //         </audio>
          //         <div class="mt-3">
          //           <input type="number" class="form-control" id="audio-number-' . $counter->id . '" placeholder="' . __("Enter number") . '">
          //         </div>
          //       </div>
          //       <div class="modal-footer">
          //         <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">' . __("Close") . '</button>
          //         <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="saveAudioNumber(' . $counter->id . ')">' . __("Save") . '</button>
          //       </div>
          //     </div>
          //   </div>
          // </div>';
        }
      })

    //   ->addColumn('actions', function($counter) use ($id){
    //     return '
    //     <a href="javascript:void(0);" class="download-btn" data-counter-id="' . $counter->id . '" data-bs-toggle="modal" data-bs-target="#worker-counter-remove-modal-' . $counter->id . '"><icon class="mdi mdi-trash-can-outline"></icon></a>
    //     <!-- Modal -->
    //     <div class="modal fade" id="worker-counter-remove-modal-' . $counter->id . '" tabindex="-1" aria-hidden="true">
    //       <div class="modal-dialog modal-dialog-centered" role="document">
    //         <div class="modal-content">
    //           <div class="modal-header">
    //             <h4 class="modal-title" id="modalCenterTitle">' .  __("Counter Remove") . '</h4>
    //           </div>
    //           <div class="modal-body text-center">
    //             <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
    //             <div class="row justify-content-center text-wrap">
    //               '. __("Do You Really Want To Remove This Counter From Worker.") .'
    //             </div>
    //           </div>
    //           <div class="modal-footer">
    //             <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >'. __("Close") .'</button>
    //             <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitRemoveCounterWorker(' . $id .',' . $counter->id . ')">'. __("Submit") .'</button>
    //           </div>
    //         </div>
    //       </div>
    //     </div>
    //   </div>
    // </div>
    //     ';
    //   })
      ->rawColumns(['status','audio'])
      ->make(true);

    }

    return view('dashboard.users.counters');
  }

  public function worker_phones($id,Request $request) {
    $worker = User::find($id);

    $phones = $worker->phones;
    if ($request->ajax()) {
      return DataTables::of($phones)
      ->editColumn('counter_id', function($phone) {
        return (string) $phone->counter_id;
      })
      ->editColumn('longitude', function($phone) {
        return $phone->value;
      })
      ->editColumn('phone', function($phone) {
        return $phone->phone;
      })
      ->editColumn('created_at', function($phone) {
          return $phone->created_at->format('Y-m-d H:i');
      })
      ->editColumn('audio', function($phone) {
        if ($phone->audio) {
          return '<a type="button" onclick="togglePlay(' . $phone->id . ')">
            <i class="mdi mdi-play-circle-outline" id="play-icon-' . $phone->id . '"></i>
          </a>
          <audio id="audio-' . $phone->id . '">
            <source src="' . asset('storage/assets/audio/phones/' . $phone->audio) . '" type="audio/mpeg">
          </audio>';

        }
      })
      ->rawColumns(['audio'])
      ->make(true);

    }

    return view('dashboard.users.phones')
    ->with('user',$worker);
  }

  public function municipalitys(Request $request) {
    $municipalitys = Municipality::where('id','!=', 0)->get();

    if ($request->ajax()) {
      return DataTables::of($municipalitys)

      ->editColumn('id', function($municipality) {
          return (string) $municipality->id;
      })
      ->editColumn('code', function($municipality) {
        return $municipality->code;
      })
      ->editColumn('places', function($municipality) {
        return $municipality->places->count();
      })
      ->addColumn('actions', function($municipality) {
        return '
        <a href="' . url("/municipality/{$municipality->id}/places") . '" data-municipality-id="' . $municipality->id . '"><icon class="mdi mdi-pen"></icon></a>
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
          return (string) $place->place_id;
      })
      ->editColumn('counters', function($place) {
        return $place->counters->count();
      })
      ->editColumn('workers', function($place) {
        return $place->workers->count();
      })
      ->addColumn('actions', function($place) {
          return '
          <a href="' . url("/place/{$place->id}/counters") . '" data-place-id="' . $place->id . '"><icon class="mdi mdi-pen"></icon></a>
          <!-- <a href="javascript:void(0);" class="download-btn" data-place-id="' . $place->place_id . '"><icon class="mdi mdi-download"></icon></a> -->
          <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#place-delete-modal-' . $place->id . '" data-place-id="1"><icon class="mdi mdi-trash-can-outline"></icon></a>

          <!-- Modal -->
          <div class="modal fade" id="place-delete-modal-' . $place->id . '" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form class="modal-content" id="createNewMunicipality">
                  <div class="modal-header">
                    <h4 class="modal-title" id="modalCenterTitle">' .  __("Place Delete") . '</h4>
                  </div>
                  <div class="modal-body text-center">
                    <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
                    <div class="row justify-content-center text-wrap">
                      '. __("Do You Really want to delete This Place.") .'
                    </div>
                    <div class="row">
                      <div class="col mb-4 mt-2">
                        <div class="input-group" dir="ltr">
                          <input type="password" class="form-control" id="show-password-municipality-' . $place->id . '" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="show-password-municipality-' . $place->id . '" name="password-' . $place->id . '" required />
                          <span class="input-group-text cursor-pointer show-password" data-municipality-id="' . $place->id . '"><i class="mdi mdi-lock-outline"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyPlace(' . $place->id . ')">'. __("Submit") .'</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
                  </div>
                </form>
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

  public function wallets(Request $request) {
    $wallets = Wallet::query();
      $status = $request->input('status');
      $type = $request->input('type');

      if ($status) {
        $wallets->where('status', $status);
      }
      if ($type) {
        $wallets->where('transaction_type', $type);
      }
      $wallets->orderBy('created_at', 'desc');

      $data = new \stdClass();
      $data->pending = User::allProfit('pending');
      $data->completed = User::allProfit('completed');
      $data->rejected = User::allProfit('rejected');
      $data->hidden = User::allProfit('hidden');

    if ($request->ajax()) {

      return DataTables::of($wallets)
      ->editColumn('id', function($wallet) {
        return (string) $wallet->id;
      })
      ->editColumn('user_id', function($wallet) {
        return '
        <a href="' . url("/user/{$wallet->user->id}/transitions") . '" >' . $wallet->user->fullname . '</a>
        ';
      })
      ->editColumn('amount', function($wallet) {
        return $wallet->amount;
      })
      ->editColumn('transaction_type', function($wallet) {
        if ($wallet->transaction_type === 'credit') {
          return '<span class="badge rounded-pill bg-label-info me-1">' . __("Credit"). '</span>';
        } else if ($wallet->transaction_type === 'debit') {
          return '<span class="badge rounded-pill bg-label-success me-1">' . __("Debit"). '</span>';
        }
      })
      ->editColumn('status', function($wallet) {
        if ($wallet->status === 'pending') {
          return '<span class="badge rounded-pill bg-label-info me-1">' . __("Pending"). '</span>';
        } else if ($wallet->status === 'completed') {
          return '<span class="badge rounded-pill bg-label-success me-1">' . __("Completed"). '</span>';
        } else if ($wallet->status === 'rejected') {
          return '<span class="badge rounded-pill bg-label-danger me-1">' . __("Rejected"). '</span>';
        } else if ($wallet->status === 'hidden') {
          return '<span class="badge rounded-pill bg-label-warning me-1">' . __("Hidden"). '</span>';
        }
      })
      ->editColumn('description', function($wallet) {
          return $wallet->description;
      })
      ->addColumn('actions', function($wallet) {
        return '
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-detail-modal-' . $wallet->id . '" data-wallet-id="' . $wallet->id . '"><icon class="mdi mdi-file-multiple-outline"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-edit-modal-' . $wallet->id . '" data-wallet-id="' . $wallet->id . '"><icon class="mdi mdi-pencil-outline"></icon></a>
        <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-delete-modal-' . $wallet->id . '" ><icon class="mdi mdi-trash-can-outline"></icon></a>

        <!-- Detail Modal -->
        <div class="modal fade" id="transaction-detail-modal-' . $wallet->id . '" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <form class="modal-content" id="editTransaction-' . $wallet->id . '">
                <div class="modal-header">
                  <h4 class="modal-title" id="modalCenterTitle">' .  __("Detail Transaction") . '</h4>
                </div>
                <div class="modal-body text-center">
                  ' . $wallet->photoTransactions->map(function($photo) {
                      return '
                      <div class="image-container text-center my-w-fit-content mb-2 mx-auto position-relative">
                          <div class="image-overlay rounded h-100 w-100 d-flex justify-content-center align-items-center position-absolute top-0 start-0 opacity-0 transition-opacity">
                              <button type="button" class="btn btn-icon btn-primary m-2 overlay-content trash-button" data-photo-id="' . $photo->id . '" data-type="image"><span class="tf-icons mdi mdi-trash-can-outline"></span></button>
                          </div>
                          <img class="w-100" src="' . $photo->photoUrl() . '" alt="' . $photo->id . '" class="rounded publication-photo" />
                      </div>';
                  })->implode('') . '
                  ' . $wallet->audioTransactions->map(function($audio) {
                      return '
                      <div class="audio-container text-center my-w-fit-content mb-2 mx-auto position-relative d-flex justify-content-center align-items-center">
                          <div class="image-overlay rounded position-absolute top-0 start-0 opacity-0 transition-opacity ms-2">
                              <button type="button" class="btn btn-icon btn-primary m-2 overlay-content trash-button" data-audio-id="' . $audio->id . '" data-type="audio"><span class="tf-icons mdi mdi-trash-can-outline"></span></button>
                          </div>
                          <audio controls>
                              <source src="' . $audio->audioUrl() . '" type="audio/mpeg">
                              Your browser does not support the audio element.
                          </audio>
                      </div>';
                  })->implode('') . '
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="transaction-edit-modal-' . $wallet->id . '" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <form class="modal-content" id="editTransaction-' . $wallet->id . '">
                <div class="modal-header">
                  <h4 class="modal-title" id="modalCenterTitle">' .  __("Edit Transaction") . '</h4>
                </div>
                <div class="modal-body text-center">
                  <div class="row">
                    <div class="col mb-4 mt-2">
                    <div class="form-floating form-floating-outline mb-4">
                      <input class="form-control" type="number" placeholder="18" name="amount-' . $wallet->id . '" min="0" value="' . $wallet->amount . '" id="html5-number-input" />
                      <label for="html5-number-input">' .  __("Amount") . '</label>
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col mb-4 mt-2">
                      <div class="form-floating form-floating-outline mb-4">
                        <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" name="description-' . $wallet->id . '" placeholder="' .  __("Comments here...") . '">' . $wallet->description . '</textarea>
                        <label for="exampleFormControlTextarea1">' .  __("Description") . '</label>
                      </div>
                    </div>
                  </div>

                  <select id="largeSelect" class="form-select form-select-lg mb-4" name="type-' . $wallet->id . '">
                      <option value="credit" ' . ($wallet->transaction_type == 'credit'? 'selected' : '') . '>' .  __("Credit") . '</option>
                      <option value="debit" ' . ($wallet->transaction_type == 'debit'? 'selected' : '') . '>' .  __("Debit") . '</option>
                  </select>

                  <select id="largeSelect" class="form-select form-select-lg mb-4" name="status-' . $wallet->id . '">
                      <option value="completed" ' . ($wallet->status == 'completed' || $wallet->status == 'pending'? 'selected' : '') . '>' .  __("Accept") . '</option>
                      <option value="rejected" ' . ($wallet->status == 'rejected'? 'selected' : '') . '>' .  __("Reject") . '</option>
                      <option value="hidden" ' . ($wallet->status == 'hidden'? 'selected' : '') . '>' .  __("Hide") . '</option>
                    </select>



                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitTransaction(' . $wallet->id . ')">'. __("Submit") .'</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="transaction-delete-modal-' . $wallet->id . '" tabindex="-1" data-bs-backdrop="static" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" id="deleteTransaction' . $wallet->id . '">
              <div class="modal-header">
                <h4 class="modal-title">' .  __("Transaction Delete") . '</h4>
              </div>
              <div class="modal-body text-center">
                <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
                <div class="row justify-content-center text-wrap">
                  '. __("Do You Really want to delete This Transaction.") .'
                </div>
                <div class="row">
                  <div class="col mb-4 mt-2">
                    <div class="input-group" dir="ltr">
                      <input type="password" class="form-control" id="show-password-transaction-' . $wallet->id . '" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="show-password-municipality-' . $wallet->id . '" name="password-' . $wallet->id . '" required />
                      <span class="input-group-text cursor-pointer show-password" data-transition-id="' . $wallet->id . '"><i class="mdi mdi-lock-outline"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyTransaction(' . $wallet->id . ')">'. __("Submit") .'</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
              </div>
            </form>
          </div>
        </div>
        ';
      })
      ->editColumn('created_at', function($wallet) {
          return $wallet->created_at->format('Y-m-d');
      })
      ->rawColumns(['actions','transaction_type','user_id','status'])
      ->make(true);

    }
      return view('dashboard.wallets.list')
      ->with('data', $data);

  }

  public function user_transitions(Request $request,$id) {
    $wallets = Wallet::where('user_id', $request->id);
    $user = User::find($request->id);

    $status = $request->input('status');
    $type = $request->input('type');

    if ($status) {
      $wallets->where('status', $status);
    }
    if ($type) {
      $wallets->where('transaction_type', $type);
    }
    $wallets->orderBy('created_at', 'desc');


    $data = new \stdClass();
    $data->pending = User::userProfit('pending',$request->id);
    $data->completed = User::userProfit('completed',$request->id);
    $data->rejected = User::userProfit('rejected',$request->id);
    $data->hidden = User::userProfit('hidden',$request->id);

    if ($request->ajax()) {
      return DataTables::of($wallets)
      ->editColumn('id', function($wallet) {
        return (string) $wallet->id;
      })
      ->editColumn('amount', function($wallet) {
        return $wallet->amount;
      })
      ->editColumn('transaction_type', function($wallet) {
          if ($wallet->transaction_type === 'credit') {
            return '<span class="badge rounded-pill bg-label-info me-1">' . __("Credit"). '</span>';
          } else if ($wallet->transaction_type === 'debit') {
            return '<span class="badge rounded-pill bg-label-success me-1">' . __("Debit"). '</span>';
          }
      })
      ->editColumn('status', function($wallet) {
        if ($wallet->status === 'pending') {
          return '<span class="badge rounded-pill bg-label-info me-1">' . __("Pending"). '</span>';
        } else if ($wallet->status === 'completed') {
          return '<span class="badge rounded-pill bg-label-success me-1">' . __("Completed"). '</span>';
        } else if ($wallet->status === 'rejected') {
          return '<span class="badge rounded-pill bg-label-danger me-1">' . __("Rejected"). '</span>';
        } else if ($wallet->status === 'hidden') {
          return '<span class="badge rounded-pill bg-label-warning me-1">' . __("Hidden"). '</span>';
        }
      })
      ->editColumn('description', function($wallet) {
          return $wallet->description;
      })
      ->addColumn('actions', function($wallet) {
        return '
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-detail-modal-' . $wallet->id . '" data-wallet-id="' . $wallet->id . '"><icon class="mdi mdi-file-multiple-outline"></icon></a>
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-edit-modal-' . $wallet->id . '" data-wallet-id="' . $wallet->id . '"><icon class="mdi mdi-pencil-outline"></icon></a>
            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#transaction-delete-modal-' . $wallet->id . '" ><icon class="mdi mdi-trash-can-outline"></icon></a>

            <!-- Detail Modal -->
            <div class="modal fade" id="transaction-detail-modal-' . $wallet->id . '" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <form class="modal-content" id="editTransaction-' . $wallet->id . '">
                    <div class="modal-header">
                      <h4 class="modal-title" id="modalCenterTitle">' .  __("Detail Transaction") . '</h4>
                    </div>
                    <div class="modal-body text-center">
                      ' . $wallet->photoTransactions->map(function($photo) {
                          return '
                          <div class="image-container text-center my-w-fit-content mb-2 mx-auto position-relative">
                              <div class="image-overlay rounded h-100 w-100 d-flex justify-content-center align-items-center position-absolute top-0 start-0 opacity-0 transition-opacity">
                                  <button type="button" class="btn btn-icon btn-primary m-2 overlay-content trash-button" data-photo-id="' . $photo->id . '" data-type="image"><span class="tf-icons mdi mdi-trash-can-outline"></span></button>
                              </div>
                              <img class="w-100" src="' . $photo->photoUrl() . '" alt="' . $photo->id . '" class="rounded publication-photo" />
                          </div>';
                      })->implode('') . '
                      ' . $wallet->audioTransactions->map(function($audio) {
                          return '
                          <div class="audio-container text-center my-w-fit-content mb-2 mx-auto position-relative d-flex justify-content-center align-items-center">
                              <div class="image-overlay rounded position-absolute top-0 start-0 opacity-0 transition-opacity ms-2">
                                  <button type="button" class="btn btn-icon btn-primary m-2 overlay-content trash-button" data-audio-id="' . $audio->id . '" data-type="audio"><span class="tf-icons mdi mdi-trash-can-outline"></span></button>
                              </div>
                              <audio controls>
                                  <source src="' . $audio->audioUrl() . '" type="audio/mpeg">
                                  Your browser does not support the audio element.
                              </audio>
                          </div>';
                      })->implode('') . '
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- edit Modal -->
            <div class="modal fade" id="transaction-edit-modal-' . $wallet->id . '" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <form class="modal-content" id="editTransaction-' . $wallet->id . '">
                    <div class="modal-header">
                      <h4 class="modal-title" >' .  __("Edit Transaction") . '</h4>
                    </div>
                    <div class="modal-body text-center">
                      <div class="row">
                        <div class="col mb-4 mt-2">
                        <div class="form-floating form-floating-outline mb-4">
                          <input class="form-control" type="number" placeholder="18" name="amount-' . $wallet->id . '" min="0" value="' . $wallet->amount . '"  />
                          <label for="html5-number-input">' .  __("Amount") . '</label>
                        </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col mb-4 mt-2">
                          <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-100" id="exampleFormControlTextarea'. $wallet->id .'" name="description-' . $wallet->id . '" placeholder="' .  __("Comments here...") . '">' . $wallet->description . '</textarea>
                            <label for="exampleFormControlTextarea1">' .  __("Description") . '</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <select class="form-select form-select-lg" name="type-' . $wallet->id . '">
                          <option value="credit" ' . ($wallet->transaction_type == 'credit'? 'selected' : '') . '>' .  __("Credit") . '</option>
                          <option value="debit" ' . ($wallet->transaction_type == 'debit'? 'selected' : '') . '>' .  __("Debit") . '</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                    ' . ($wallet->status == 'hidden' ? '
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="acceptTransaction('. $wallet->id .')">'. __("Accept") .'</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="rejectTransaction('. $wallet->id .')">'. __("Reject") .'</button>
                    ' : ($wallet->status == 'rejected' ? '
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="acceptTransaction('. $wallet->id .')">'. __("Accept") .'</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="hideTransaction('. $wallet->id .')">'. __("Hide") .'</button>
                    ' : ($wallet->status == 'completed' ? '
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="rejectTransaction('. $wallet->id .')">'. __("Reject") .'</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="hideTransaction('. $wallet->id .')">'. __("Hide") .'</button>
                    ' : '
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="acceptTransaction('. $wallet->id .')">'. __("Accept") .'</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="rejectTransaction('. $wallet->id .')">'. __("Reject") .'</button>
                    '))) . '
                </div>
                  </form>
                </div>
              </div>
            </div>




            <!-- delete Modal -->

            <div class="modal fade" id="transaction-delete-modal-' . $wallet->id . '" tabindex="-1" data-bs-backdrop="static" >
              <div class="modal-dialog modal-dialog-centered" role="document">
                <form class="modal-content" id="deleteTransaction' . $wallet->id . '">
                  <div class="modal-header">
                    <h4 class="modal-title">' .  __("Transaction Delete") . '</h4>
                  </div>
                  <div class="modal-body text-center">
                    <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
                    <div class="row justify-content-center text-wrap">
                      '. __("Do You Really want to delete This Transaction.") .'
                    </div>
                    <div class="row">
                      <div class="col mb-4 mt-2">
                        <div class="input-group" dir="ltr">
                          <input type="password" class="form-control" id="show-password-transaction-' . $wallet->id . '" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="show-password-municipality-' . $wallet->id . '" name="password-' . $wallet->id . '" required />
                          <span class="input-group-text cursor-pointer show-password" data-transition-id="' . $wallet->id . '"><i class="mdi mdi-lock-outline"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyTransaction(' . $wallet->id . ')">'. __("Submit") .'</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">'. __("Close") .'</button>
                  </div>
                </form>
              </div>
            </div>
        ';
    })

      ->editColumn('created_at', function($wallet) {
          return $wallet->created_at->format('Y-m-d');
      })
      ->rawColumns(['actions','transaction_type','status'])
      ->make(true);

    }
      return view('dashboard.users.transitions')
      ->with('user',$user)
      ->with('data',$data);

  }


  public function phones(Request $request) {

    $phones = Phone::all();
    if ($request->ajax()) {
      return DataTables::of($phones)
      ->editColumn('counter_id', function($phone) {
        return (string) $phone->counter_id;
      })
      ->editColumn('longitude', function($phone) {
        return $phone->value;
      })
      ->editColumn('phone', function($phone) {
        return $phone->phone;
      })
      ->editColumn('created_at', function($phone) {
          return $phone->created_at->format('Y-m-d H:i');
      })
      ->editColumn('audio', function($phone) {
        if ($phone->audio) {
          return '<a type="button" onclick="togglePlay(' . $phone->id . ')">
            <i class="mdi mdi-play-circle-outline" id="play-icon-' . $phone->id . '"></i>
          </a>
          <audio id="audio-' . $phone->id . '">
            <source src="' . asset('storage/assets/audio/phones/' . $phone->audio) . '" type="audio/mpeg">
          </audio>';

          // return '<a href="#" type="button" data-bs-toggle="modal" data-bs-target="#audio-modal-' . $counter->id . '">
          // <i class="mdi mdi-play-circle-outline"></i>
          // </a>

          // <!-- Modal -->
          // <div class="modal fade" id="audio-modal-' . $counter->id . '" tabindex="-1" aria-hidden="true">
          //   <div class="modal-dialog modal-dialog-centered" role="document">
          //     <div class="modal-content">
          //       <div class="modal-header">
          //         <h4 class="modal-title" id="modalCenterTitle">' . __("Audio for Counter") . ' ' . $counter->name . '</h4>
          //       </div>
          //       <div class="modal-body text-center">
          //         <audio controls>
          //           <source src="' . asset('storage/assets/audio/counters/' . $counter->audio) . '" type="audio/mpeg">
          //         </audio>
          //         <div class="mt-3">
          //           <input type="number" class="form-control" id="audio-number-' . $counter->id . '" placeholder="' . __("Enter number") . '">
          //         </div>
          //       </div>
          //       <div class="modal-footer">
          //         <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">' . __("Close") . '</button>
          //         <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="saveAudioNumber(' . $counter->id . ')">' . __("Save") . '</button>
          //       </div>
          //     </div>
          //   </div>
          // </div>';
        }
      })
      ->rawColumns(['audio'])

      ->make(true);

    }
      return view('dashboard.phones.list');

  }
}
