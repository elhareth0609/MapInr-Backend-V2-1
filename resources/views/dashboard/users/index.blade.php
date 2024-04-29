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
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/counters')}}"><i class="mdi mdi-map-marker-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/transitions')}}"><i class="mdi mdi-transition  mdi-20px me-1"></i>{{ __('Transitions') }}</a></li>

    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{ __('User Details')}}</h4>
      <!-- Account -->
      <div class="card-body pt-2 mt-1">
        <form id="updateUser" >
          <input type="hidden" id="id" name="id" value="{{ $user->id }}" />

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

            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <div class="input-group">
                  <input type="text" class="form-control" id="passwordInput" placeholder="{{__('Password')}}" name="password" value="{{ $user->password }}" readonly required>
                  <button class="btn btn-outline-primary" type="button" id="copyPassword" ><span class="mdi mdi-content-copy"></span></button>
                  <button class="btn btn-outline-primary" type="button" id="generatePassword">{{__('Generate')}}</button>
                </div>
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
    <div class="card">
      <h5 class="card-header fw-normal">{{ __('Delete Account') }}</h5>
      <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading mb-1">{{ __('Are you sure you want to delete your account?') }}</h6>
            <p class="mb-0">{{ __('Once you delete your account, there is no going back. Please be certain.') }}</p>
          </div>
        </div>
        <div >
          <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#user-delete-modal">{{ __('Delete Account') }}</button>
        </div>
      </div>
    </div>
  </div>
</div>



<style>
    .delete-alert-span::before {
    font-size: 110px;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="user-delete-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form class="modal-content" id="createNewMunicipality">
        <div class="modal-header">
          <h4 class="modal-title" id="modalCenterTitle">{{  __('User Delete') }}</h4>
        </div>
        <div class="modal-body text-center">
          <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
          <div class="row justify-content-center text-wrap">
            {{  __('Do You Really want to delete This User.') }}
          </div>
          <div class="row">
            <div class="col mb-4 mt-2">
              <div class="input-group" dir="ltr">
                {{-- <input type="password" class="form-control" id="show-password-municipality-' . $user->id . '" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="show-password-municipality-' . $user->id . '" name="password-' . $user->id . '" required /> --}}
                <input type="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" name="confirm_password" required />
                <span class="input-group-text cursor-pointer show-password" ><i class="mdi mdi-lock-outline"></i></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyUser({{  $user->id }})">{{ __('Submit') }}</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{  __('Close') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript">


function submitDistroyUser(id) {
          var password = $('input[name="confirm_password"]').val(); // Dynamically select the password input based on the modal ID

            $.ajax({
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                  password: password
                },
                url: '/user/destroy/' + id,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.state,
                        text: response.message,
                      }).then(() => {
                          window.location.href = '/users';
                      });
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: error.responseJSON.status,
                        text: error.responseJSON.errors,
                    });
                }
            });
        }

$(document).ready( function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.show-password', function () {
        var inputField = $(this).closest('.modal-content').find('.form-control');
          if (inputField.attr('type') === 'password') {
              inputField.attr('type', 'text');
              $(this).find('i').removeClass('mdi-lock-outline').addClass('mdi-lock-open-variant-outline');
          } else {
              inputField.attr('type', 'password');
              $(this).find('i').removeClass('mdi-lock-open-variant-outline').addClass('mdi-lock-outline');
          }
      });

    $('#copyPassword').on('click', function() {
        var passwordInput = $('#passwordInput');
        passwordInput.select();
        document.execCommand('copy');
        // You can add a visual indication or alert here
    });

    // Function to generate a new password
    $('#generatePassword').on('click', function() {
        // Make an AJAX request to generate a new password
        $.ajax({
            url: '/generate-password',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              $('#passwordInput').val(response.password);
            },
            error: function(error) {
                console.error('Error generating password:', error);
            }
        });
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
                icon: response.icon,
                title: response.state,
                text: response.message,
            });

            },
            error: function (error) {
              Swal.fire({
                    icon: 'error',
                    title: error.responseJSON.message,
                    text: error.responseJSON.error,
                });
            }
        });
      });
    });
</script>
@endsection
