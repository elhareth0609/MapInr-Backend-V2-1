@extends('layouts/contentNavbarLayout')

@section('title', ' Municipality - Information')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir' : '' }}>{{ __('Municipality Settings') }} /</span> {{ $municipality->name }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('municipality/'. $municipality->id . '/places')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('municipality/'. $municipality->id . '/workers')}}"><i class="mdi mdi-link mdi-20px me-1"></i>{{ __('Workers')}}</a></li> --}}
    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{ __('Municipality Details')}}</h4>
      <!-- Account -->
      <div class="card-body pt-2 mt-1">
        <form id="updateInformtion" method="POST" action="{{ route('municipality.update')}}">
            <input class="form-control" type="hidden" name="id" value="{{ $municipality->id }}" required>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" name="name" value="{{ $municipality->name }}" placeholder="{{ __('Enter Your Municipality Name')}}" required>
                  <label for="name">{{ __('Municipality Name')}}</label>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-floating form-floating-outline">
                  <input class="form-control" type="text" name="code" value="{{ $municipality->code }}" placeholder="{{ __('Enter Code')}}">
                  <label for="code">{{ __('Code')}}</label>
                </div>
              </div>
            </div>
              
          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{ __('Save Changes')}}</button>
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>



    <div class="card">
      <h5 class="card-header fw-normal">{{ __('Municipality Delete') }}</h5>
      <div class="card-body">
        <div class="mb-3 col-12 mb-0">
          <div class="alert alert-warning">
            <h6 class="alert-heading mb-1">{{ __('Are you sure you want to delete this municipality?') }}</h6>
            <p class="mb-0">{{ __('Once you delete this municipality, there is no going back. Please be certain.') }}</p>
          </div>
        </div>
        <div >
          <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#municipality-delete-modal">{{ __('Municipality Delete') }}</button>
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
<div class="modal fade" id="municipality-delete-modal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <form class="modal-content" id="createNewMunicipality">
      <div class="modal-header">
        <h4 class="modal-title" id="modalCenterTitle">{{  __('Municipality Delete') }}</h4>
      </div>
      <div class="modal-body text-center">
        <span class="mdi mdi-alert-circle-outline delete-alert-span text-danger"></span>
        <div class="row justify-content-center text-wrap">
          {{  __('Do You Really want to delete This Municipality.') }}
        </div>
        <div class="row">
          <div class="col mb-4 mt-2">
            <div class="input-group" dir="ltr">
              <input type="password" class="form-control" id="basic-default-password42" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="basic-default-password42" name="password" required />
              <span class="input-group-text cursor-pointer show-password" ><i class="mdi mdi-lock-outline"></i></span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitDistroyMunicipality({{  $municipality->id }})">{{ __('Submit') }}</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{  __('Close') }}</button>
      </div>
    </form>
  </div>
</div>
</div>



<script type="text/javascript">

function submitDistroyMunicipality(id) {
        var password = $('input[name="password"]').val(); // Dynamically select the password input based on the modal ID

          $.ajax({
              type: 'DELETE',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                password: password
              },
              url: '/municipality/destroy/' + id,
              success: function (response) {
                  Swal.fire({
                      icon: 'success',
                      title: response.state,
                      text: response.message,
                  }).then(() => {
                      window.location.href = '/municipalitys';
                  });
              },
              error: function (error) {
                  Swal.fire({
                      icon: 'error',
                      title: error.responseJSON.message,
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

  $('#updateUser').submit(function (e) {
          e.preventDefault();

          $.ajax({
              type: 'POST',
              headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: '/municipality/update',
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

        $('#updateInformtion').on('submit', function(e) {
          e.preventDefault();

          // Make AJAX request
          $.ajax({
              url: $(this).attr('action'),
              method: $(this).attr('method'),
              data: new FormData(this),
              processData: false,
              contentType: false,
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
