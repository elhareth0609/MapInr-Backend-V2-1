@extends('layouts/contentNavbarLayout')

@section('title', __('All Bills'))

@section('content')
  <div class="row w-100 d-flex align-items-baseline mb-2">
    <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
      <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Bills')}}
    </h4>
    <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
      {{-- <button type="button" class="m-1 btn btn-outline-primary col-lg-2 col-xl-2 col-md-3 col-sm-3 col-12">
        <span class="tf-icons mdi mdi-upload me-1"></span>{{__('Import')}}
      </button>
      <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12">
        <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
      </button> --}}
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
      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Bills')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="bills" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            <th>{{__('Id')}}</th>
            <th>{{__('Counter Id')}}</th>
            <th>{{__('Worker')}}</th>
            <th>{{__('Amount')}}</th>
            <th>{{__('Created At')}}</th>
            <th>{{ __('Actions') }}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 d-flex align-items-baseline justify-content-end mb-2">
        <button type="button" class="btn btn-outline-primary btn-icon" id="delete-button">
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
  #bills_length {
    display: none;
  }

  #bills_filter {
    display: none;
  }

  #bills_paginate {
    display: none;
  }
  #bills_info {
    display: none;
  }
  .delete-alert-span::before {
    font-size: 110px;
  }
  td , tr{
    text-align: center;
  }
  tr td input {
    width: 100% !important;
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-editable/1.3.3/jquery.editable.min.js"></script>
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.14/js/dataTables.checkboxes.min.js" ></script>

<script>
var dataTable;
var lang = "{{ app()->getLocale() }}"


    function submitDistroyBill(id) {
      var password = $('input[name="password-' + id + '"]').val(); // Dynamically select the password input based on the modal ID

      $.ajax({
          type: 'DELETE',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            password: password
          },
          url: '/bills/' + id + '/delete',
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

    $.noConflict();
    dataTable = $('#bills').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,
      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: '{{ route("bills-table") }}',
      columns: [
        { data: 'id', title: '{{__("Id")}}' },
        { data: 'counter_id', title: '{{__("Counter Id")}}' },
        { data: 'user_id', title: '{{__("Worker")}}' },
        { data: 'amount', title: '{{__("Amount")}}' },
        { data: 'created_at', title: '{{__("Created At")}}' },
        { data: 'action', title: '{{__("Actions")}}' }
      ],
      "order": [[4, "desc"]],
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

        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);

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


    $('.show-password').on('click', function() {
      var billId = $(this).data('bill-id');
      var passwordInput = $('#show-password-bill-' + billId);

      // Toggle between password and text type
      if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        $(this).find('i').removeClass('mdi-lock-outline').addClass('mdi-lock-open-outline');
      } else {
        passwordInput.attr('type', 'password');
        $(this).find('i').removeClass('mdi-lock-open-outline').addClass('mdi-lock-outline');
      }
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
          url: '{{ route("bill.delete.all") }}',
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
