@extends('layouts/contentNavbarLayout')

@section('title', 'All Users')

@section('content')
<div class="row w-100 d-flex align-items-baseline mb-2">
  <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
    <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Users')}}
  </h4>
  <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
    <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12" data-bs-toggle="modal" data-bs-target="#adduser">
      <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="adduser" data-bs-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content" id="createNewUser">
          <div class="modal-header">
            <h4 class="modal-title" id="backDropModalTitle">{{__('Add User')}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-2">
              <div class="col mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nameBackdrop" class="form-control" placeholder="{{__('Enter First Name')}}" name="firstname" required>
                  <label for="emailBackdrop">{{__('First Name')}}</label>
                </div>
              </div>
              <div class="col mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nameBackdrop" class="form-control" placeholder="{{__('Enter Last Name')}}" name="lastname" required>
                  <label for="dobBackdrop">{{__('Last Name')}}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col mb-4 mt-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nameBackdrop" class="form-control" placeholder="example@gmail.com" name="email">
                  <label for="nameBackdrop">{{__('Email')}}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col mb-4 mt-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nameBackdrop" class="form-control" placeholder="0666666666" name="phone">
                  <label for="nameBackdrop">{{__('Phone')}}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col mb-4 mt-2">
                <div class="form-floating form-floating-outline">
                  <div class="input-group">
                    <input type="text" class="form-control" id="passwordInput" placeholder="{{__('Password')}}" name="password" readonly required>
                    <button class="btn btn-outline-primary" type="button" id="copyPassword" ><span class="mdi mdi-content-copy"></span></button>
                    <button class="btn btn-outline-primary" type="button" id="generatePassword">{{__('Generate')}}</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal"id="submitFormAddUser">{{__('Save')}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Responsive Table -->
  <div class="card">
    <div class="row w-100">
      <div class="card-header col-sm-4 col-lg-3 col-xl-3 col-6" >
        <div class="input-group input-group-merge">
          <span class="input-group-text" id="basic-addon-search31"><i class="mdi mdi-magnify"></i></span>
          <input type="text" class="form-control" id="customSearch" placeholder="Search..." aria-describedby="basic-addon-search31" />
        </div>
      </div>
      <div class="card-header col-sm-3 col-lg-2 col-xl-2 col-5">
        <select id="RowSelect" class="form-select form-select-lg">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100" selected>100</option>
        </select>
      </div>
      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Users')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="users" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            {{-- <th style="font-size: medium"><input class="form-check-input" type="checkbox" value="" id="checkbox_all" /></th> --}}
            <th>#</th>
            <th>{{__('Full Name')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Phone')}}</th>
            <th>{{__('Counters')}}</th>
            <th>{{__('Status')}}</th>
            <th>{{__('Created At')}}</th>
            <th>{{__('Actions')}}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 justify-content-end justify-content-center-md justify-content-center-sm d-flex align-items-baseline">
        {{-- <button type="button" class="btn btn-outline-primary col-lg-2 col-xl-2 col-md-2 col-sm-3 col-6">
          <span class="tf-icons mdi mdi-download me-1"></span>Export
        </button> --}}
        <p class="card-header col-lg-3" id="infoTable" style="width: fit-content;"> </p>
        <nav class="card-header col-lg-3" aria-label="Page navigation" style="width: fit-content;">
          <ul class="pagination pagination-rounded pagination-outline-primary" id="custom-pagination">
              <!-- Custom pagination items go here -->
          </ul>
        </nav>
      </div>

    </div>
  </div>

<!--/ Responsive Table -->
<style>
  #users_length {
    display: none;
  }

  #users_filter {
    display: none;
  }

  #users_paginate {
    display: none;
  }
  #users_info {
    display: none;
  }
  .delete-alert-span::before {
    font-size: 110px;
  }
  td , tr{
    text-align: center;
  }
</style>
<script>
  var dataTable;
        function submitDistroyUser(id) {
          var password = $('input[name="password-' + id + '"]').val(); // Dynamically select the password input based on the modal ID

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
                    });
                    dataTable.ajax.reload();
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



    $.noConflict();
    dataTable = $('#users').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,
      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: '{{ route("users") }}',
      columns: [
        { data: 'id', title: '#' },
        { data: 'fullname', title: '{{__("Full Name")}}' },
        { data: 'email', title: '{{__("Email")}}' },
        { data: 'phone', title: '{{__("Phone")}}' },
        { data: 'counters', title: '{{__("Counters")}}' },
        { data: 'status', title: '{{__("Status")}}' },
        { data: 'created_at', title: '{{__("Created At")}}' },
        { data: 'actions', name: '{{ __("Actions")}}', orderable: false, searchable: false },
      ],
      "order": [[6, "desc"]],
      "drawCallback": function () {
        updateCustomPagination();
        var pageInfo = this.api().page.info();

        // Update the content of the custom info element
        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
        $('#users tbody').on('dblclick', 'tr', function () {
          var userId = $(this).find('a[data-worker-id]').attr('href').split('/').pop();
          window.location.href = '/user/' + userId;
        });

        $('.modal').on('dblclick', function (event) {
            event.stopPropagation();
        });
      },
    });
    $('#customSearch').on('keyup', function () {
      dataTable.search(this.value).draw();
    });
    $('#RowSelect').on('change', function () {
      dataTable.page.len(this.value).draw();
    });

    updateCustomPagination();

    // Function to update custom pagination
    function updateCustomPagination() {
      var customPaginationContainer = $('#custom-pagination');
      var pageInfo = dataTable.page.info();

      // Clear existing pagination items
      customPaginationContainer.empty();

      // Add "Previous" button with logic to disable and apply red color
      var prevButton = '<li class="page-item prev';
      if (pageInfo.page === 0) {
          prevButton += ' disabled"><a class="page-link" style="color: #d4d3d5;">';
      } else {
          prevButton += '"><a class="page-link" href="javascript:void(0);" onclick="changePage(' + (pageInfo.page - 1) + ')">';
      }
      prevButton += '<i class="tf-icon mdi mdi-chevron-left"></i></a></li>';
      customPaginationContainer.append(prevButton);

      // Add current page number
      customPaginationContainer.append('<li class="page-item active"><a class="page-link">' + (pageInfo.page + 1) + '</a></li>');

      // Add "Next" button with logic to disable and apply red color
      var nextButton = '<li class="page-item next';
      if (pageInfo.page === pageInfo.pages - 1) {
          nextButton += ' disabled"><a class="page-link" style="color: #d4d3d5;">';
      } else {
          nextButton += '"><a class="page-link" href="javascript:void(0);" onclick="changePage(' + (pageInfo.page + 1) + ')">';
      }
      nextButton += '<i class="tf-icon mdi mdi-chevron-right"></i></a></li>';
      customPaginationContainer.append(nextButton);
    }


    // Function to handle page change
    window.changePage = function (page) {
      dataTable.page(page).draw(false);
    };

      $('#createNewUser').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/create',
            data: $(this).serialize(),
            success: function (response) {
            Swal.fire({
                icon: 'success',
                title: response.state,
                text: response.message,
            });
            dataTable.ajax.reload();
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

      $(document).on('click', '.download-btn-user-file', function() {
        var placeId = $(this).data('worker-id');

        // Make an AJAX request to download Excel for the specific place
        $.ajax({
            url: '/exoprt-user-file/' + placeId, // Update the URL to your route for downloading Excel
            type: 'GET',
            xhrFields: {
                responseType: 'blob' // Important to set the responseType to 'blob'
            },
            success: function(response) {
                // Create a Blob from the response
                var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

                // Use FileSaver.js library to trigger the download
                saveAs(blob,+ placeId + '.xlsx');
            },
            error: function(error) {
                // Handle error
                console.error(error);
            }
        });
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
  });
</script>
@endsection
