@extends('layouts/contentNavbarLayout')

@section('title', ' User - Information')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">{{ __('User Settings') }} /</span> {{ $user->fullname }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/places')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{ __('User Details')}}</h4>
      <!-- Account -->
      <div class="card-body pt-2 mt-1">
        <form id="updateUser" >
          <input type="hidden" id="id" name="id" value="{{ $user->di }}" />

          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="fullname" name="fullname" value="{{ $user->fullname }}" placeholder="{{ __('Enter Your Full Name')}}" />
                <label for="firstName">{{ __('Full Name')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" name="email" id="email" value="{{ $user->email }}" placeholder="{{ __('Enter Your Email')}}"/>
                <label for="email">{{ __('Email')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="phone" name="phone" value="{{ $user->phone }}" placeholder="{{ __('Enter Your Phone')}}" />
                <label for="phone">{{ __('Phone')}}</label>
              </div>
            </div>

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

<script type="text/javascript">
$(document).ready( function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
$('#updateUser').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/update',
            data: $(this).serialize(),
            success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'User updated successfully!',
            });

            },
            error: function (error) {
              Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: error.responseJSON.error,
                });
            }
        });
      });
    });
</script>
@endsection
