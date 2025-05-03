@extends('layouts/contentNavbarLayout')

@section('title', ' User - Reactions')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

    <div class="row w-100 d-flex align-items-baseline mb-2">
      <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
        <span class="text-muted fw-light">{{ __('User Settings') }} /</span> {{ $user->fullname }}
      </h4>
    </div>


        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
              <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id)}}"><i class="mdi mdi-list-box-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/places')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/counters')}}"><i class="mdi mdi-map-marker-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id . '/transitions')}}"><i class="mdi mdi-transition  mdi-20px me-1"></i>{{ __('Transitions') }}</a></li>
              <li class="nav-item"><a class="nav-link" href="{{url('worker-phones/'. $user->id)}}"><i class="mdi mdi-phone-outline  mdi-20px me-1"></i>{{ __('Phones') }}</a></li>
              <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-swap-horizontal  mdi-20px me-1"></i>{{ __('Reactions') }}</a></li>
            </ul>

            <div class="row float-end">

            </div>

            <div class="row w-100 mx-auto">
              <div class="col-md-3 col-sm-4 my-1 px-0">
                <div class="card widget-card border-light shadow-sm mx-1" dir="rtl">
                  <div class="card-body p-4">
                    <div class="row">
                      <div class="col-8">
                        <h5 class="card-title widget-card-title mb-3">{{ __('C') }}</h5>
                        <h4 class="card-subtitle text-success m-0">{{ $data->c }}</h4>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-plus-outline fs-4"></i>
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
                        <h5 class="card-title widget-card-title mb-3">{{ __('D') }}</h5>
                        <h4 class="card-subtitle text-danger m-0">{{ $data->d }}</h4>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div class="lh-1 text-white bg-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-trash-can-outline fs-4"></i>
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
                        <h5 class="card-title widget-card-title mb-3">{{ __('R') }}</h5>
                        <h4 class="card-subtitle text-info m-0">{{ $data->r }}</h4>
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
                        <h5 class="card-title widget-card-title mb-3">{{ __('P') }}</h5>
                        <h4 class="card-subtitle text-warning m-0">{{ $data->p }}</h4>
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
            <div class="card-header col-sm-3 col-lg-2 col-xl-2 col-5">
              <select id="actionFilter" class="form-select form-select-lg">
                <option value="">{{ __('All') }}</option>
                <option value="p">{{ __('P') }}</option>
                <option value="c">{{ __('C') }}</option>
                <option value="r">{{ __('R') }}</option>
                <option value="d">{{ __('D') }}</option>
              </select>
            </div>
            {{-- <button type="button" class="btn btn-icon btn-outline-primary my-auto" data-bs-toggle="modal" data-bs-target="#transaction-add-modal">
              <span class="tf-icons mdi mdi-plus-outline"></span>
            </button> --}}
            <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Reactions')}}</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped w-100" id="userReactions" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  <th>#</th>
                  <th>{{__('Counter Id')}}</th>
                  <th>{{__('Action')}}</th>
                  <th>{{__('Note')}}</th>
                  <th>{{__('Created At')}}</th>
                  <th>{{__('Actions')}}</th>
                </tr>
              </thead>
            </table>
            <div class="row w-100 d-flex align-items-baseline justify-content-end ">

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
        #userReactions_length {
          display: none;
        }

        #userReactions_filter {
          display: none;
        }

        #userReactions_paginate {
          display: none;
        }

        #userReactions_info {
          display: none;
        }

        .delete-alert-span::before {
          font-size: 110px;
        }

        td , tr{
          text-align: center;
        }

        .image-overlay {
          background-color: rgba(0, 0, 0, 0.5);
          transition: opacity 0.3s ease;
        }

        .image-container:hover .image-overlay {
            opacity: 1!important;
        }

        .audio-container:hover .image-overlay {
            opacity: 1!important;
        }

        .publication-photo {
        width: 720px;
        }

        .swal2-container {
          z-index: 10000;
        }
        </style>


      </div>
      <!-- /Account -->
    </div>
  </div>
</div>

<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.14/js/dataTables.checkboxes.min.js" ></script>

<script>

      var dataTable;

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

        $('#addTransactionForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: response.state,
                    text:  response.message,
                });
                dataTable.ajax.reload();
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
      dataTable = $('#userReactions').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,
        language: {
          info: "_START_-_END_ of _TOTAL_",
        },
        ajax: {
          url: '{{ url("user/" . $user->id . "/reactions") }}',
          data: function(d) {
              d.action = $('#actionFilter').val();
          }
        },

        columns: [
          { data: 'id', title: '#' },
          { data: 'counter_id', title: '{{__("Counter Id")}}' },
          { data: 'action', title: '{{__("Action")}}' },
          { data: 'notes', title: '{{__("Note")}}' },
          { data: 'created_at', title: '{{__("Created At")}}' },
          { data: 'actions', title: '{{__("Actions")}}' }
        ],
        "order": [[5, "desc"]],
        // select: {
        //   style: 'multi',
        // },
        // columnDefs: [{
        //   targets: 0,
        //   checkboxes: {
        //     selectRow: true
        //   }
        // }],

        "drawCallback": function () {
          updateCustomPagination();
          var pageInfo = this.api().page.info();

          // Update the content of the custom info element
          $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
          $('#userPlaces tbody').on('dblclick', 'tr', function () {
            var placeId = $(this).find('a[data-place-id]').attr('href').split('/').pop();
            window.location.href = '/place/' + placeId + '/counters';
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
      $('#actionFilter').on('change', function() {
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


      $(document).on('click', '.trash-button', function(event) {
        var id = $(this).data('photo-id') || $(this).data('audio-id');
        var type = $(this).data('type'); // Add data-type attribute to distinguish between image and audio
        var container = $(this).closest('.image-container, .audio-container');

        Swal.fire({
            title: 'Do you really want to delete this Item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Submit",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/wallet/unupload/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { type: type }, // Send the type of file in the request
                    success: function(response) {
                        container.remove();

                        Swal.fire({
                            icon: response.icon,
                            title: response.state,
                            text: response.message,
                            confirmButtonText: __("Ok", lang),
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        const response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: response.icon,
                            title: response.state,
                            text: response.message,
                            confirmButtonText: __("Ok", lang),
                        });
                    }
                });
            }
        });
      });
      
    });
  </script>


@endsection
