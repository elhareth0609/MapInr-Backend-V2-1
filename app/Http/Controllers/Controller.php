<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function getCode($counter_id) {
        $counter = Counter::find($counter_id);
        if ($counter) {
            $counter_code = $counter->counter_id;
            $place_code = $counter->place->place_id;
            $municipality_code = $counter->place->municipality->code;
            return $municipality_code . $place_code . $counter_code;
        } else {
            return $counter_id;
        }
    }
}
