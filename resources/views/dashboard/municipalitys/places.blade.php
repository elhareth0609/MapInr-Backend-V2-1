@extends('layouts/contentNavbarLayout')

@section('title', ' Municipality - Places')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

<div class="row w-100 d-flex align-items-baseline mb-2">
  <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
    <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir' : '' }}>{{ __('Municipality Settings') }} /</span> {{ $municipality->name }}
  </h4>
  <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
    <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12" data-bs-toggle="modal" data-bs-target="#importFile">
      <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Import')}}
    </button>
  </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="importFile" data-bs-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content" id="uploadForm" action="{{ route('upload.file') }}" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title" id="backDropModalTitle">{{__('Import File')}}</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" value="{{ $municipality->id }}">

            <div class="container-upload-file w-100">
              <div class="card-upload-file w-100">
                <div class="drop_box-upload-file w-100">
                    <h4>{{__('Select File here')}}</h4>
                  <p>Files Supported: Excel</p>
                  <input type="file" hidden accept=".xlsx,.xls" id="fileID" name="excelFile[]" style="display:none;" multiple>
                  <div class="btn btn-outline-primary" id="button-upload-file" >{{__('Choose File')}}</div>
                </div>
              </div>
            </div>
            <div id="selectedFileName"></div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" id="submitFormAddUser">{{__('Submit')}}</button>
          </div>
        </form>
      </div>
    </div>


<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('municipality/'. $municipality->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('municipality/'. $municipality->id . '/workers')}}"><i class="mdi mdi-link mdi-20px me-1"></i>{{ __('Workers')}}</a></li> --}}
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
            <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Places')}}</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped w-100" id="municipalityPlaces" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  <th>{{__('Place Number')}}</th>
                  <th>{{__('Counters')}}</th>
                  <th>{{__('Workers')}}</th>
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
        #municipalityPlaces_length {
          display: none;
        }

        #municipalityPlaces_filter {
          display: none;
        }

        #municipalityPlaces_paginate {
          display: none;
        }
        #municipalityPlaces_info {
          display: none;
        }
        .delete-alert-span::before {
          font-size: 110px;
        }
        td , tr{
          text-align: center;
        }


      .container-upload-file {

      display: flex;
      justify-content: center;
      }


      .card-upload-file h3 {
      font-size: 22px;
      font-weight: 600;

      }

      .drop_box-upload-file {
      margin: 10px 0;
      padding: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      border: 3px dotted #a3a3a3;
      border-radius: 5px;
      }

      .drop_box-upload-file h4 {
      font-size: 16px;
      font-weight: 400;
      color: #2e2e2e;
      }

      .drop_box-upload-file p {
      margin-top: 10px;
      margin-bottom: 20px;
      font-size: 12px;
      color: #a3a3a3;
      }
        </style>

      </div>
      <!-- /Account -->
    </div>
  </div>
</div>


<script>
    var municipalityPlacesDataTable;

    function submitDistroyPlace(id) {
        var password = $('input[name="password-' + id + '"]').val(); // Dynamically select the password input based on the modal ID

          $.ajax({
              type: 'DELETE',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                password: password
              },
              url: '/place/destroy/' + id,
              success: function (response) {
                  Swal.fire({
                      icon: 'success',
                      title: response.state,
                      text: response.message,
                  });
                  municipalityPlacesDataTable.ajax.reload();
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

      $('#button-upload-file').on('click', function () {
      $('#fileID').click();
    });

    $('#fileID').on('change', function (e) {
      var files = e.target.files;
      var fileNames = Array.from(files).map(file => file.name);

    });

    $(document).on('change', '#fileID', function (e) {
    // Get the selected file(s)
    var files = $(this)[0].files;

    // Display the file name(s)
    var fileNameContainer = $('#selectedFileName');
    fileNameContainer.empty(); // Clear previous file name(s)
    for (var i = 0; i < files.length; i++) {
        fileNameContainer.append('<p>' + files[i].name + '</p>');
    }
});

    $(document).on('submit', '#uploadForm', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Manually append file data to FormData
        formData.append('excelFile', $('#fileID')[0].files);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: response.state,
                    text: response.message,
                });
                municipalityPlacesDataTable.ajax.reload();
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.responseJSON.errors,
                });
            }
        });
    });


      $.noConflict();
      municipalityPlacesDataTable = $('#municipalityPlaces').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,

        language: {
          info: "_START_-_END_ of _TOTAL_",
        },
        ajax: '{{ route("municipality.places", ["id" => $municipality->id]) }}',
        columns: [
        { data: 'place_id', title: '{{ __("Place Id")}}' },
        { data: 'counters', title: '{{ __("Counters")}}' },
        { data: 'workers', title: '{{ __("Workers")}}' },
        { data: 'actions', name: '{{ __("Actions")}}', orderable: false, searchable: false },
        ],
        "drawCallback": function () {
          updateCustomPagination();
          var pageInfo = this.api().page.info();

          // Update the content of the custom info element
          $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
          $('#municipalityPlaces tbody').on('dblclick', 'tr', function () {
              var placeId = $(this).find('a[data-place-id]').attr('href').split('/').pop();
              window.location.href = '/place/' + placeId + '/counters';
          });

          $('.modal').on('dblclick', function (event) {
            event.stopPropagation();
        });

        },
      });
      $('#customSearch').on('keyup', function () {
        municipalityPlacesDataTable.search(this.value).draw();
      });
      $('#RowSelect').on('change', function () {
        municipalityPlacesDataTable.page.len(this.value).draw();
      });

      updateCustomPagination();

      // Function to update custom pagination
      function updateCustomPagination() {
          var customPaginationContainer = $('#custom-pagination');
          var pageInfo = municipalityPlacesDataTable.page.info();

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
        municipalityPlacesDataTable.page(page).draw(false);
      };

      $(document).on('click', '.download-btn', function() {
        var placeId = $(this).data('place-id');

        // Make an AJAX request to download Excel for the specific place
        $.ajax({
            url: '/exoprt-file/' + placeId, // Update the URL to your route for downloading Excel
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
