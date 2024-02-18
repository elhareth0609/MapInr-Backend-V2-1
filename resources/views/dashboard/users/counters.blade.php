@extends('layouts/contentNavbarLayout')

@section('title', ' User - Counters')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">{{ __('User Settings') }} / </span> {{ $user->name }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/places')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
      <li class="nav-item"><a class="nav-link active"><i class="mdi mdi-map-marker-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
    </ul>
    <div class="card mb-4">
      {{-- <h4 class="card-header">{{ __('Counters')}}</h4> --}}
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
            <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Counters')}}</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped w-100" id="userCounters" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  <th>{{__('Counter Number')}}</th>
                  <th>{{__('Counter Name')}}</th>
                  <th>{{__('Longitude')}}</th>
                  <th>{{__('Latitude')}}</th>
                  <th>{{__('Phone')}}</th>
                  <th>{{__('Status')}}</th>
                  <th>{{__('Created At')}}</th>
                  <th>{{__('Actions')}}</th>
                </tr>
              </thead>
            </table>
            <div class="row w-100 d-flex align-items-baseline justify-content-end ">
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
        #userCounters_length {
          display: none;
        }

        #userCounters_filter {
          display: none;
        }

        #userCounters_paginate {
          display: none;
        }
        #userCounters_info {
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


<script>
      var userCountersDataTable;

function submitRemoveCounterWorker(userid, counterid) {

    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/user/remove-counter/' + userid + '/' + counterid,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: response.state,
                text: response.message,
            });
            userCountersDataTable.ajax.reload();
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

      $('#addCounterWorker').on('submit', function(e) {
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
                    text: 'Counter Add To Worker successfully!',
                });
                userCountersDataTable.ajax.reload();
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

      $.noConflict();
      userCountersDataTable = $('#userCounters').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,

        language: {
          info: "_START_-_END_ of _TOTAL_",
        },
        ajax: '{{ route("worker-counters-table", ["id" => $user->id]) }}',
        columns: [
          { data: 'counter_id', title: '{{__("Counter Id")}}' },
          { data: 'name', title: '{{__("Name")}}' },
          { data: 'longitude', title: '{{__("Longitude")}}' },
          { data: 'latitude', title: '{{__("Latitude")}}' },
          { data: 'phone', title: '{{__("Phone")}}' },
          { data: 'status', title: '{{__("Status")}}' },
          { data: 'created_at', title: '{{__("Created At")}}' },
          { data: 'actions', title: '{{__("Actions")}}' }
        ],
        "order": [[6, "desc"]],
        "drawCallback": function () {
          updateCustomPagination();
          var pageInfo = this.api().page.info();

          // Update the content of the custom info element
          $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
        },
      });
      $('#customSearch').on('keyup', function () {
        userCountersDataTable.search(this.value).draw();
      });
      $('#RowSelect').on('change', function () {
        userCountersDataTable.page.len(this.value).draw();
      });

      updateCustomPagination();

      // Function to update custom pagination
      function updateCustomPagination() {
          var customPaginationContainer = $('#custom-pagination');
          var pageInfo = userCountersDataTable.page.info();

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
        userCountersDataTable.page(page).draw(false);
      };


    });
  </script>

@endsection
