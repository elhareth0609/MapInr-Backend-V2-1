@extends('layouts/contentNavbarLayout')

@section('title', 'All Transitions')

@section('content')


<div class="row w-100 d-flex align-items-baseline mb-2">
  <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
    <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Transitions')}}
  </h4>

</div>


        <div class="row w-100 mx-auto">
          <div class="col-md-3 col-sm-4 my-1 px-0">
            <div class="card widget-card border-light shadow-sm mx-1" dir="rtl">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-8">
                    <h5 class="card-title widget-card-title mb-3">{{ __('Pending') }}</h5>
                    @if ($data->pending > 0)
                      <h4 class="card-subtitle text-success m-0">{{ $data->pending }} د.ج</h4>
                    @else
                      <h4 class="card-subtitle text-danger m-0">{{ $data->pending * -1 }} د.ج</h4>
                    @endif
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-clock-time-eight-outline fs-4"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-4 my-1 px-0">
            <div class="card widget-card border-light shadow-sm mx-1" dir="rtl">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-8">
                    <h5 class="card-title widget-card-title mb-3">{{ __('Completed') }}</h5>
                    @if ($data->completed > 0)
                      <h4 class="card-subtitle text-success m-0">{{ $data->completed }} د.ج</h4>
                    @else
                      <h4 class="card-subtitle text-danger m-0">{{ $data->completed * -1 }} د.ج</h4>
                    @endif
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-check-all fs-4"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-4 my-1 px-0">
            <div class="card widget-card border-light shadow-sm mx-1" dir="rtl">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-8">
                    <h5 class="card-title widget-card-title mb-3">{{ __('Rejected') }}</h5>
                    @if ($data->rejected > 0)
                      <h4 class="card-subtitle text-success m-0">{{ $data->rejected }} د.ج</h4>
                    @else
                      <h4 class="card-subtitle text-danger m-0">{{ $data->rejected * -1 }} د.ج</h4>
                    @endif
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-alert-outline fs-4"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-4 my-1 px-0">
            <div class="card widget-card border-light shadow-sm mx-1" dir="rtl">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-8">
                    <h5 class="card-title widget-card-title mb-3">{{ __('Hidden') }}</h5>
                    @if ($data->hidden > 0)
                    <h4 class="card-subtitle text-success m-0">{{ $data->hidden }} د.ج</h4>
                    @else
                    <h4 class="card-subtitle text-danger m-0">{{ $data->hidden * -1 }} د.ج</h4>
                    @endif
                  </div>
                  <div class="col-4">
                    <div class="d-flex justify-content-end">
                      <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-circle-off-outline fs-4"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>







    <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
      {{-- <button type="button" class="m-1 btn btn-outline-primary col-lg-2 col-xl-2 col-md-3 col-sm-3 col-12">
        <span class="tf-icons mdi mdi-upload me-1"></span>{{__('Import')}}
      </button>
      <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12">
        <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
      </button> --}}
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
      <div class="card-header col-sm-3 col-lg-2 col-xl-2 col-5">
        <select id="statusFilter" class="form-select form-select-lg">
          <option value="">{{ __('All') }}</option>
          <option value="pending">{{ __('Pending') }}</option>
          <option value="completed">{{ __('Completed') }}</option>
          <option value="rejected">{{ __('Rejected') }}</option>
          <option value="hidden">{{ __('Hidden') }}</option>
        </select>
      </div>
      <div class="card-header col-sm-3 col-lg-2 col-xl-2 col-5">
        <select id="typeFilter" class="form-select form-select-lg">
          <option value="">{{ __('All') }}</option>
          <option value="credit">{{ __('Credit') }}</option>
          <option value="debit">{{ __('Debit') }}</option>
        </select>
      </div>

      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Transitions')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="wallets" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            <th>#</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Transaction Type')}}</th>
            <th>{{__('Status')}}</th>
            <th>{{__('Amount')}}</th>
            <th>{{__('Description')}}</th>
            <th>{{__('Created At')}}</th>
            <th>{{__('Actions')}}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 d-flex align-items-baseline justify-content-end ">
        <button type="button" class="btn btn-icon btn-outline-primary col-lg-1 col-xl-1 col-md-1 col-sm-1 col-1" id="delete-button">
          <icon class="mdi mdi-trash-can-outline"></icon>
        </button>
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
  #wallets_length {
    display: none;
  }

  #wallets_filter {
    display: none;
  }

  #wallets_paginate {
    display: none;
  }
  #wallets_info {
    display: none;
  }
  .delete-alert-span::before {
    font-size: 110px;
  }
  td , tr{
    text-align: center;
  }
</style>
{{-- <script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.14/js/dataTables.checkboxes.min.js" ></script> --}}
<script>
var dataTable;



    function acceptTransaction(id) {
        var amount = $('input[name="amount-' + id + '"]').val(); // Dynamically select the password input based on the modal ID
        var description = $('textarea[name="description-' + id + '"]').val(); // Dynamically select the password input based on the modal ID
        var type = $('select[name="type-' + id + '"]').val(); // Dynamically select the password input based on the modal ID

          $.ajax({
              type: 'POST',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                amount: amount,
                description: description,
                type: type
              },
              url: '/wallets/' + id + '/accept',
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
                      title: error.responseJSON.state,
                      text: error.responseJSON.message,
                  });
              }
          });
    }

    function rejectTransaction(id) {

          $.ajax({
              type: 'GET',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              url: '/wallets/' + id + '/reject',
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

    function hideTransaction(id) {

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/wallets/' + id + '/hide',
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

    function submitDistroyTransaction(id) {
      var password = $('input[name="password-' + id + '"]').val(); // Dynamically select the password input based on the modal ID

      $.ajax({
          type: 'DELETE',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            password: password
          },
          url: '/wallets/' + id + '/delete',
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

    $.noConflict();
    dataTable = $('#wallets').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,
      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: {
        url: '{{ route("wallets-table") }}',
        data: function(d) {
            d.status = $('#statusFilter').val();
            d.type = $('#typeFilter').val();
        }
      },
      columns: [
        { data: 'id', title: '#' },
        { data: 'user_id', title: '{{__("Name")}}' },
        { data: 'transaction_type', title: '{{__("Transaction Type")}}' },
        { data: 'amount', title: '{{__("Amount")}}' },
        { data: 'status', title: '{{__("Status")}}' },
        { data: 'description', title: '{{__("Description")}}' },
        { data: 'created_at', title: '{{__("Created At")}}' },
        { data: 'actions', title: '{{__("Actions")}}' }
      ],
      "order": [[7, "desc"]],
        select: {
          style: 'multi',
        },
        columnDefs: [{
          targets: 0,
          checkboxes: {
            selectRow: true
          }
        }],

      "drawCallback": function () {
        updateCustomPagination();
        var pageInfo = this.api().page.info();

        // Update the content of the custom info element
        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
      },

    });

    $('#customSearch').on('keyup', function () {
      dataTable.search(this.value).draw();
    });
    $('#RowSelect').on('change', function () {
      dataTable.page.len(this.value).draw();
    });

    $('#statusFilter').on('change', function() {
      dataTable.ajax.reload();
    });

    $('#typeFilter').on('change', function() {
      dataTable.ajax.reload();
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

    $(document).on('click', '#exportButton', function() {

        // Make an AJAX request to download Excel for the specific place
        $.ajax({
            url: '/exoprt-file/0', // Update the URL to your route for downloading Excel
            type: 'GET',
            xhrFields: {
                responseType: 'blob' // Important to set the responseType to 'blob'
            },
            success: function(response) {
                // Create a Blob from the response
                var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

                // Use FileSaver.js library to trigger the download
                saveAs(blob,+ '0' + '.xlsx');
            },
            error: function(error) {
                // Handle error
                console.error(error);
            }
        });
    });


    $('#delete-button').on('click', function() {
        var selectedRowsIds = [];

        dataTable.rows().every(function () {
            var rowNode = this.node(); // Get the row node
            var checkbox = $(rowNode).find('td:eq(0) input[type="checkbox"]'); // Assuming the checkboxes are in the first column (index 0)
            var isChecked = checkbox.prop('checked');

            if (isChecked) {
                selectedRowsIds.push(this.data().id); // Assuming you have a method to get the ID of each row (replace with your actual method)
                // console.log('Checkbox in this row is checked',this.data().id);
            } else {
                // console.log('Checkbox in this row is not checked');
            }
        });


        var requestData = {
            _token: '{{ csrf_token() }}',
            ids: selectedRowsIds
        };

        $.ajax({
          url: '{{ route("transitions.delete.all") }}',
          type: 'POST',
          data: requestData,
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


  });
</script>
@endsection
