@extends('layouts/contentNavbarLayout')

@section('title', 'settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir' : '' }}>{{__('Settings')}} / </span> {{__('Account')}}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{__('Account')}}</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('settings/application')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{__('Application')}}</a></li> --}}
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i class="mdi mdi-link mdi-20px me-1"></i>Connections</a></li> --}}
    </ul>
    <div class="card mb-4">
      <h4 class="card-header">{{__('Profile Details')}}</h4>
      <!-- Account -->

      <div class="card-body pt-2 mt-1">
        <form id="updateInformtion" method="POST" action="{{ route('settings.update.information')}}">
          @csrf
          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="firstName" name="fullname" value="{{ $admin->fullname }}" placeholder="{{__('Enter Full Name')}}"/>
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
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#updateAccountModel">{{__('Save Changes')}}</button>
          </div>

            <!-- Modal -->
            <div class="modal fade" id="updateAccountModel" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header" @if(app()->getLocale() === 'ar')  dir="rtl" @else  @endif>
                    <h4 class="modal-title" id="modalCenterTitle">{{  __("Update Information") }}</h4>
                  </div>
                  <div class="modal-body text-center">
                    <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
                    <div class="row justify-content-center text-wrap">
                      {{ __("Do Your Really Want To Update Your Account Information.") }}
                    </div>
                  </div>
                  <div class="modal-footer" @if(app()->getLocale() === 'ar')  dir="rtl" @else  @endif>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >{{ __("Close") }}</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" >{{ __("Submit") }}</button>
                  </div>
                </div>
              </div>
            </div>
          {{-- </div>
        </div> --}}

        </form>
      </div>
      <!-- /Account -->
    </div>
    <div class="card">
      <h5 class="card-header fw-normal">{{__('Change Password')}}</h5>
      <div class="card-body">
        <form id="changePassword" method="POST" action="{{ route('settings.change.password')}}">
          @csrf
          <div class="row mt-2 gy-4">
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="pasword" id="passord" name="passord" placeholder="{{__('Enter Past Password')}}"/>
                <label for="firstName">{{__('Past Password')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="pasword" id="newpassord" name="newpassord" placeholder="{{__('Enter New Password')}}"/>
                <label for="firstName">{{__('New Password')}}</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input class="form-control" type="pasword" id="confirmnewpassord" name="confirmnewpassord" placeholder="{{__('Confirm New Password')}}"/>
                <label for="firstName">{{__('Confirm New Password')}}</label>
              </div>
            </div>
          </div>
        <div class="mt-4">
          <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModel">{{__('Save Changes')}}</button>
        </div>

          <!-- Modal -->
          <div class="modal fade" id="changePasswordModel" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header" @if(app()->getLocale() === 'ar')  dir="rtl" @else  @endif>
                  <h4 class="modal-title" id="modalCenterTitle" >{{  __("Change Password") }}</h4>
                </div>
                <div class="modal-body text-center">
                  <span class="mdi mdi-alert-circle-outline delete-alert-span"></span>
                  <div class="row justify-content-center text-wrap">
                    {{ __("Do Your Really Want To Change Your Password.") }}
                  </div>
                </div>
                <div class="modal-footer" @if(app()->getLocale() === 'ar')  dir="rtl" @else  @endif>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" >{{ __("Close") }}</button>
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" >{{ __("Submit") }}</button>
                </div>
              </div>
            </div>
          </div>
            {{-- </form>
          </div> --}}

        </form>
      </div>
    </div>
  </div>
</div>

<style>
    .delete-alert-span::before {
      font-size: 110px;
    }
</style>
<script type="text/javascript">
    $(document).ready( function () {

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
                  icon: 'success',
                  title: 'Success',
                  text: 'Updated successfully!',
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

        $('#changePassword').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Make AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Changed successfully!',
                });
                userPlacesdataTable.ajax.reload();
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
