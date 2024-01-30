@extends('layouts/contentNavbarLayout')

@section('title', 'Settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">{{__('Settings')}} /</span> {{__('Application')}}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('settings/application')}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{__('Account')}}</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{__('Application')}}</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i class="mdi mdi-link mdi-20px me-1"></i>Connections</a></li> --}}
    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{__('Application Details')}}</h4>
      <!-- Account -->

      <div class="card-body pt-2 mt-1">
        <form id="formAccountSettings" method="POST" onsubmit="return false">
          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="firstName" name="firstName" value="{{ $admin->fullname }}" autofocus />
                <label for="firstName">{{__('Full Name')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="email" name="email" value="{{ $admin->email }}" placeholder="zzzzz@example.com" />
                <label for="email">{{__('Email')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="phone" id="phoneNumber" name="phone" class="form-control" placeholder="07 7777 7777" value="{{ $admin->phone }}"/>
                  <label for="phoneNumber">{{__('Phone Number')}}</label>
                </div>
                <span class="input-group-text">DZ (+213)</span>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{__('Save changes')}}</button>
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
    <div class="card">
      <h5 class="card-header fw-normal">{{__('Change Password')}}</h5>
      <div class="card-body">
        <form id="formAccountSettings" method="POST" onsubmit="return false">
          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="pasword" id="passord" name="passord" autofocus />
                <label for="firstName">{{__('Past Password')}}</label>
              </div>
            </div>
          </div>
        </form>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary me-2">{{__('Save changes')}}</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
