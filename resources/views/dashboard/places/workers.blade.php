@extends('layouts/contentNavbarLayout')

@section('title', ' Place - Workers')

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
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Inforamtion')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id . '/counters')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-link mdi-20px me-1"></i>{{ __('Workers')}}</a></li>
    </ul>
    <div class="card mb-4">
      {{-- <h4 class="card-header">{{ __('Workers')}}</h4> --}}
      <!-- Account -->
      <div class="card-body pt-2 mt-1">
        <!-- Responsive Table -->
        {{-- <div class="card"> --}}
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
                <option value="25" selected>25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Users')}}</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped w-100" id="placeWorkers" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  {{-- <th style="font-size: medium"><input class="form-check-input" type="checkbox" value="" id="checkbox_all" /></th> --}}
                  <th>#</th>
                  <th>{{__('Full Name')}}</th>
                  <th>{{__('Email')}}</th>
                  <th>{{__('Phone')}}</th>
                  <th>{{__('Status')}}</th>
                  <th>{{__('created At')}}</th>
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

          {{-- </div> --}}
        </div>

        <!--/ Responsive Table -->
        <style>
        #placeWorkers_length {
          display: none;
        }

        #placeWorkers_filter {
          display: none;
        }

        #placeWorkers_paginate {
          display: none;
        }
        #placeWorkers_info {
          display: none;
        }
        .delete-alert-span::before {
          font-size: 110px;
        }
        td , tr{
          text-align: center;
        }
        </style>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>

<script type="text/javascript">
    var placeWorkersDataTable;
    function submitDistroyUser(placeid, userid) {

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/user/remove-place/' + userid + '/' + placeid,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Worker Removed From Place successfully!',
                });
                placeWorkersDataTable.ajax.reload();
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: error.responseJSON.error,
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


  $.noConflict();
  placeWorkersDataTable = $('#placeWorkers').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 20,
    responsive: true,

    language: {
      info: "_START_-_END_ of _TOTAL_",
    },
    ajax: '{{ url("place-workers/" . $place->id) }}',
    columns: [
      { data: 'id', name: '#' },
      { data: 'fullname', name: 'fullname' },
      { data: 'email', name: 'email' },
      { data: 'phone', name: 'phone' },
      { data: 'status', name: 'status' },
      { data: 'created_at', name: 'created_at' },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
    ],
    "drawCallback": function () {
      updateCustomPagination();
      var pageInfo = this.api().page.info();

      // Update the content of the custom info element
      $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
    },
  });
  $('#customSearch').on('keyup', function () {
    placeWorkersDataTable.search(this.value).draw();
  });
  $('#RowSelect').on('change', function () {
    placeWorkersDataTable.page.len(this.value).draw();
  });

  updateCustomPagination();

  // Function to update custom pagination
  function updateCustomPagination() {
      var customPaginationContainer = $('#custom-pagination');
      var pageInfo = placeWorkersDataTable.page.info();

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
    placeWorkersDataTable.page(page).draw(false);
  };


});
</script>



@endsection
