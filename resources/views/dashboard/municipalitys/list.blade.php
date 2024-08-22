@extends('layouts/contentNavbarLayout')

@section('title', 'All Municipalitys')

@section('content')
  <div class="row w-100 d-flex align-items-baseline mb-2">
    <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
      <span class="text-muted fw-light" {{ app()->getLocale() === 'ar' ? 'dir="rtl"' : '' }}>{{__('Pages')}} /</span> {{__('All Municipalitys')}}
    </h4>
    <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
      <button type="button" class="m-1 btn btn-outline-primary col-lg-2 col-xl-2 col-md-3 col-sm-3 col-12"  data-bs-toggle="modal" data-bs-target="#createMunicipality">
        <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
      </button>
    </div>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="createMunicipality" data-bs-backdrop="static" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content" id="createNewMunicipality" action="{{ route('municipality.create') }}">
          <div class="modal-header">
            <h4 class="modal-title" id="backDropModalTitle">{{__('Add Municipality')}}</h4>
          </div>
          <div class="modal-body">
              <input type="hidden"class="form-control" name="id" required>
            <div class="row">
              <div class="col mb-4 mt-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control" placeholder="{{__('Enter Name')}}" name="name" required>
                  <label for="name">{{__('Municipality Name')}}</label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col mb-4 mt-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control" placeholder="{{__('Enter Code')}}" name="code" required>
                  <label for="code">{{__('Code')}}</label>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" id="submitFormAddUser">{{__('Submit')}}</button>
          </div>
        </form>
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
      <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Municipalitys')}}</h5>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-striped w-100" id="municipalitys" dir="rtl">
        <thead>
          <tr class="text-nowrap">
            <th>#</th>
            <th>{{__('Municipality Name')}}</th>
            <th>{{__('Code')}}</th>
            <th>{{__('Places')}}</th>
            {{-- <th>{{__('Workers')}}</th> --}}
            {{-- <th>{{__('Created At')}}</th> --}}
            <th>{{__('Actions')}}</th>
          </tr>
        </thead>
      </table>
      <div class="row w-100 d-flex align-items-baseline justify-content-end ">
        <button type="button" class="btn btn-outline-primary col-lg-2 col-xl-2 col-md-2 col-sm-3 col-6" id="exportButton">
          <span class="tf-icons mdi mdi-download me-1"></span>{{__('Export All')}}
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
  #municipalitys_length {
    display: none;
  }

  #municipalitys_filter {
    display: none;
  }

  #municipalitys_paginate {
    display: none;
  }
  #municipalitys_info {
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
<script>
    var dataTable;


      function downloadOnesMunicipality(id) {
        $.ajax({
            url: '/exoprt-file-zip/' + id,
            method: 'GET',
            responseType: 'arraybuffer', // Use 'arraybuffer' for binary data
            success: function (data) {
            // Redirect the user to the download link
            window.location.href = data.url;
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

    $('#button-upload-file').on('click', function () {
      $('#fileID').click();
    });

    $('#fileID').on('change', function (e) {
      var files = e.target.files;
      var fileNames = Array.from(files).map(file => file.name);

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
                dataTable.ajax.reload();
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
    dataTable = $('#municipalitys').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 100,
      responsive: true,

      language: {
        info: "_START_-_END_ of _TOTAL_",
      },
      ajax: '{{ route("municipalitys") }}',
      columns: [
        { data: 'id', title: '#' },
        { data: 'name', title: '{{ __("Municipality Name")}}' },
        { data: 'code', title: '{{ __("Code")}}' },
        { data: 'places', title: '{{ __("Places")}}' },
        { data: 'actions', name: '{{ __("Actions")}}', orderable: false, searchable: false },
      ],
      "drawCallback": function () {
        updateCustomPagination();
        var pageInfo = this.api().page.info();

        // Update the content of the custom info element
        $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);

        $('#municipalitys tbody').on('dblclick', 'tr', function () {
            var municipalityId = $(this).find('a[data-municipality-id]').data('municipality-id');
            window.location.href = '/municipality/' + municipalityId + '/places';
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

    $('#exportButton').click(function () {
        $.ajax({
            url: '/exoprt-municipalitys-zip',
            method: 'GET',
            responseType: 'arraybuffer', // Use 'arraybuffer' for binary data
            success: function (data) {
            // Redirect the user to the download link
            window.location.href = data.url;
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

    $('#createNewMunicipality').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/municipality/create',
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
