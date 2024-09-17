@extends('layouts/contentNavbarLayout')

@section('title', __('All Phones'))

@section('content')
  <div class="row w-100 d-flex align-items-baseline mb-2">
    <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
      <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Phones')}}
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
      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Phones')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="phones" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            <th>{{__('Send To')}}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('Value') }}</th>
            <th>{{__('Created At')}}</th>
            <th>{{ __('Audio') }}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 d-flex align-items-baseline justify-content-end ">
        {{-- <button type="button" class="btn btn-icon btn-outline-primary col-lg-1 col-xl-1 col-md-1 col-sm-1 col-1" id="confirm-send-all-button">
          <icon class="mdi mdi-send-check-outline"></icon>
        </button>
 --}}
        {{-- <button type="button" class="btn btn-outline-primary col-lg-1 col-xl-1 col-md-1 col-sm-1 col-1" id="delete-button">
          <icon class="mdi mdi-trash-can-outline"></icon>
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
  #phones_length {
    display: none;
  }

  #phones_filter {
    display: none;
  }

  #phones_paginate {
    display: none;
  }
  #phones_info {
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

<script>
var dataTable;
var lang = "{{ app()->getLocale() }}"

    // for play single audio
    function togglePlay(counterId) {
        var audio = document.getElementById('audio-' + counterId);
        var icon = document.getElementById('play-icon-' + counterId);

        if (audio.paused) {
            audio.play();
            icon.classList.remove('mdi-play-circle-outline');
            icon.classList.add('mdi-pause-circle-outline');

            // When audio ends, change the icon back to play
            audio.onended = function() {
                icon.classList.remove('mdi-pause-circle-outline');
                icon.classList.add('mdi-play-circle-outline');
            };
        } else {
            audio.pause();
            icon.classList.remove('mdi-pause-circle-outline');
            icon.classList.add('mdi-play-circle-outline');
        }
    }

$(document).ready( function () {

    $.noConflict();
    dataTable = $('#phones').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,
      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: '{{ route("phones-table") }}',
      columns: [
        { data: 'mot', title: '{{__("Send To")}}' },
        { data: 'phone', title: '{{__("Phone")}}' },
        { data: 'value', title: '{{__("Name")}}' },
        { data: 'created_at', title: '{{__("Created At")}}' },
        { data: 'audio', title: '{{__("Audio")}}' }
      ],
      "order": [[3, "desc"]],
      "drawCallback": function () {
        updateCustomPagination();
        var pageInfo = this.api().page.info();

        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);

        var currentlyEditing = null;
        var originalValue = null;

        $('#phones tbody').on('dblclick', 'td', function() {
            var cell = dataTable.cell(this);
            var columnIdx = cell.index().column;
            var rowIdx = cell.index().row;
            var data = cell.data();

            if (columnIdx === 2) {

                if (currentlyEditing) {
                    if (currentlyEditing.index().row === rowIdx && currentlyEditing.index().column === columnIdx) {
                        return;
                    } else {
                        var prevCell = dataTable.cell(currentlyEditing);
                        $(currentlyEditing.node()).html(originalValue);
                    }
                }


                originalValue = data;
                currentlyEditing = cell;

                $(this).html('<input type="text" name="value" value="' + data + '"/>');
                $('input[name="value"]').focus();

                $('input[name="value"]').on('keypress', function(e) {
                    if (e.which == 13) { // Enter key pressed
                            var newValue = $(this).val();
                            var rowData = dataTable.row(rowIdx).data();
                            var rowId = rowData.id;


                            $.ajax({
                                url: '/phones/save-audio-value', // Replace with your URL
                                method: 'POST',
                                data: {
                                    phone_id: rowId,
                                    value: newValue,
                                    _token: '{{ csrf_token() }}' // Add CSRF token if using Laravel
                                },

                                success: function (response) {
                                  var justNowLabel = __("Just Now", lang);

                                  var successToast = `
                                      <div class="bs-toast toast toast-placement-ex m-2 fade bottom-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
                                          <div class="toast-header">
                                              <i class="mdi mdi-content-copy text-success me-2"></i>
                                              <div class="me-auto fw-medium">${response.state}</div>
                                              <small class="text-muted">${justNowLabel}</small>
                                              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                          </div>
                                          <div class="toast-body">
                                              ${response.message}
                                          </div>
                                      </div>
                                  `;

                                  $('body').append(successToast);

                                  var toastElement = document.querySelector('.bs-toast');
                                  var toast = new bootstrap.Toast(toastElement);
                                  toast.show();


                                  dataTable.ajax.reload();
                            },
                            error: function (error) {
                              var justNowLabel = __("Just Now",lang);
                              var errorToast = `
                                <div class="bs-toast toast toast-placement-ex m-2 fade bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
                                    <div class="toast-header">
                                        <i class="mdi mdi-alert-outline text-danger me-2"></i>
                                        <div class="me-auto fw-medium">${error.responseJSON.title}</div>
                                        <small class="text-muted">${justNowLabel}</small>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        ${error.responseJSON.error}
                                    </div>
                                </div>
                              `;

                              $('body').append(errorToast);

                              var toastElement = document.querySelector('.bs-toast');
                              var toast = new bootstrap.Toast(toastElement);
                              toast.show();
                            }

                            });
                        }
                });
            } else if (columnIdx === 0) {

              if (currentlyEditing) {
                    if (currentlyEditing.index().row === rowIdx && currentlyEditing.index().column === columnIdx) {
                        return;
                    } else {
                        var prevCell = dataTable.cell(currentlyEditing);
                        $(currentlyEditing.node()).html(originalValue);
                    }
                }


                originalValue = data;
                currentlyEditing = cell;

                $(this).html('<input type="text" name="mot" value="' + data + '"/>');
                $('input[name="mot"]').focus();

                $('input[name="mot"]').on('keypress', function(e) {
                    if (e.which == 13) { // Enter key pressed
                            var mot = $(this).val();
                            var rowData = dataTable.row(rowIdx).data();
                            var rowId = rowData.id;


                            $.ajax({
                                url: '/phones/save-phone-counter', // Replace with your URL
                                method: 'POST',
                                data: {
                                  phone_id: rowId,
                                  mot: mot,
                                  _token: '{{ csrf_token() }}' // Add CSRF token if using Laravel
                                },

                                success: function (response) {
                                  var justNowLabel = __("Just Now", lang);

                                  var successToast = `
                                      <div class="bs-toast toast toast-placement-ex m-2 fade bottom-0 end-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
                                          <div class="toast-header">
                                              <i class="mdi mdi-content-copy text-success me-2"></i>
                                              <div class="me-auto fw-medium">${response.state}</div>
                                              <small class="text-muted">${justNowLabel}</small>
                                              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                          </div>
                                          <div class="toast-body">
                                              ${response.message}
                                          </div>
                                      </div>
                                  `;

                                  $('body').append(successToast);

                                  var toastElement = document.querySelector('.bs-toast');
                                  var toast = new bootstrap.Toast(toastElement);
                                  toast.show();


                                  dataTable.ajax.reload();
                            },
                            error: function (error) {
                              var justNowLabel = __("Just Now",lang);
                              var errorToast = `
                                <div class="bs-toast toast toast-placement-ex m-2 fade bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
                                    <div class="toast-header">
                                        <i class="mdi mdi-alert-outline text-danger me-2"></i>
                                        <div class="me-auto fw-medium">${error.responseJSON.title}</div>
                                        <small class="text-muted">${justNowLabel}</small>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        ${error.responseJSON.error}
                                    </div>
                                </div>
                              `;

                              $('body').append(errorToast);

                              var toastElement = document.querySelector('.bs-toast');
                              var toast = new bootstrap.Toast(toastElement);
                              toast.show();
                            }

                            });
                        }
                });
            }
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

    $(document).on('click', '#confirm-send-all-button', function() {
        var editedData = [];

        // Iterate through all rows to collect edited values
        dataTable.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            var sendToValue = $(this.node()).find('.send-to-input').val();
            editedData.push({ counter: data.id, send_to: sendToValue });
        });

        // Send the edited data to the server via AJAX
        $.ajax({
            url: '/phones/save-counter-place',
            type: 'POST',
            data: {
                editedData: editedData,
                _token: '{{ csrf_token() }}' // Add CSRF token if using Laravel
            },
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
                    title: error.responseJSON.title,
                    text: error.responseJSON.error
                });
            }
        });
    });

  });
</script>
@endsection
