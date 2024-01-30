@extends('layouts/contentNavbarLayout')

@section('title', ' Place - Information')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">{{ __('Place Settings') }} /</span> {{ $place->name }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id . '/counters')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id . '/workers')}}"><i class="mdi mdi-link mdi-20px me-1"></i>{{ __('Workers')}}</a></li>
    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{ __('Place Details')}}</h4>
      <!-- Account -->
      <div class="card-body pt-2 mt-1">
        <form id="formAccountSettings" method="POST" onsubmit="return false">
          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="number" name="number" value="{{ $place->place_id }}" placeholder="{{ __('Enter Your Place Number')}}" />
                <label for="firstName">{{ __('Place Number')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" name="name" id="name" value="{{ $place->name }}" placeholder="{{ __('Enter Your Place Name')}}"/>
                <label for="lastName">{{ __('Place Name')}}</label>
              </div>
            </div>
            {{-- <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="longitude" name="longitude" value="{{ $place->longitude }}" placeholder="{{ __('Enter Your longitude')}}" />
                <label for="email">{{ __('Longitude')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $place->latitude }}" placeholder="{{ __('Enter Your langitude')}}" />
                <label for="organization">{{ __('Latitude')}}</label>
              </div>
            </div>
 --}}
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{ __('Save Changes')}}</button>
            {{-- <button type="reset" class="btn btn-outline-secondary">Reset</button> --}}
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>
@endsection
