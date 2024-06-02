@extends('layouts/contentNavbarLayout')

@section('title', 'All Counters')

@section('content')
  <div class="row w-100 d-flex align-items-baseline mb-2">
    <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
      <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Counters')}}
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
      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Counters')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="counters" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            {{-- <th></th> --}}
            <th>{{__('Counter Number')}}</th>
            <th>{{__('Counter Name')}}</th>
            {{-- <th>{{__('Place Number')}}</th> --}}
            <th>{{ __('Worker') }}</th>
            <th>{{ __('Longitude') }}</th>
            <th>{{ __('Latitude') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{__('Created At')}}</th>
            <th>{{ __('Audio') }}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 d-flex align-items-baseline justify-content-end ">
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
  #counters_length {
    display: none;
  }

  #counters_filter {
    display: none;
  }

  #counters_paginate {
    display: none;
  }
  #counters_info {
    display: none;
  }
  .delete-alert-span::before {
    font-size: 110px;
  }
  td , tr{
    text-align: center;
  }
  tr td input {
    width: 90px !important;
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-editable/1.3.3/jquery.editable.min.js"></script>

{{-- <script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.14/js/dataTables.checkboxes.min.js" ></script> --}}
<script>
var dataTable;

// function saveAudioNumber(counterId) {

//   var number = document.getElementById('audio-number-' + counterId).value;
//   $.ajax({
//       type: 'POST',
//       headers: {
//           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//       },
//       data: {
//         counter_id: counterId,
//         number: number
//       },
//       url: '/counters/save-audio-number',
//       success: function (response) {
//           Swal.fire({
//               icon: 'success',
//               title: response.state,
//               text: response.message,
//           });
//           dataTable.ajax.reload();
//       },
//       error: function (error) {
//           Swal.fire({
//               icon: 'error',
//               title: error.responseJSON.title,
//               text: error.responseJSON.error
//           });
//       }
//   });
// }


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
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });

    $.noConflict();
    dataTable = $('#counters').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,
      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: '{{ route("counters-table") }}',
      columns: [
        { data: 'counter_id', title: '{{__("Counter Id")}}' },
        { data: 'name', title: '{{__("Name")}}' },
        { data: 'worker_id', title: '{{__("Worker")}}' },
        { data: 'longitude', title: '{{__("Longitude")}}',"searchable": false },
        { data: 'latitude', title: '{{__("Latitude")}}',"searchable": false },
        { data: 'phone', title: '{{__("Phone")}}' },
        { data: 'created_at', title: '{{__("Created At")}}' },
        { data: 'audio', title: '{{__("Audio")}}' }
      ],
      "order": [[6, "desc"]],
      "drawCallback": function () {
        updateCustomPagination();
        var pageInfo = this.api().page.info();

        // Update the content of the custom info element
        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);

        var currentlyEditing = null;
        var originalValue = null;

        $('#counters tbody').on('dblclick', 'td', function() {
            var cell = dataTable.cell(this);
            var columnIdx = cell.index().column;
            var rowIdx = cell.index().row;
            var data = cell.data();

            // Check if the double-clicked cell is in the 'name' column (index 1)
            if (columnIdx === 1) {
                // Check if there's an already active editing cell
                // if (currentlyEditing) {
                //     // Revert the previous cell to its original value
                //     var prevCell = dataTable.cell(currentlyEditing);
                //     $(currentlyEditing.node()).html(originalValue);
                // }

                if (currentlyEditing) {
                        if (currentlyEditing.index().row === rowIdx && currentlyEditing.index().column === columnIdx) {
                            // Do nothing if double-click is on the same cell that is already being edited
                            return;
                        } else {
                            // Revert the previous cell to its original value
                            var prevCell = dataTable.cell(currentlyEditing);
                            $(currentlyEditing.node()).html(originalValue);
                        }
                    }

                    
                // Save the original value of the new cell
                originalValue = data;
                currentlyEditing = cell;

                $(this).html('<input type="text" name="name" value="' + data + '"/>');
                $('input[name="name"]').focus();

            $('input[name="name"]').on('keypress', function(e) {
                if (e.which == 13) { // Enter key pressed
                        var newValue = $(this).val();
                        var rowData = dataTable.row(rowIdx).data();
                        var rowId = rowData.id; // Assuming the row has a 'counter_id' field

                        // Send AJAX request to update the value
                        $.ajax({
                            url: '/counters/save-audio-number', // Replace with your URL
                            method: 'POST',
                            data: {
                                counter_id: rowId,
                                number: newValue,
                                _token: '{{ csrf_token() }}' // Add CSRF token if using Laravel
                            },
                            // url: '/counters/save-audio-number',
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


  });
</script>
@endsection
