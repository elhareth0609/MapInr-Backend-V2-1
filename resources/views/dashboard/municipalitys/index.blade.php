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

            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" name="name" value="{{ $municipality->name }}" placeholder="{{ __('Enter Your Municipality Name')}}" required>
                <label for="lastName">{{ __('Municipality Name')}}</label>
              </div>
            </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">{{ __('Save Changes')}}</button>
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
