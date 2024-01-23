@extends('layouts/contentNavbarLayout')

@section('title', ' User - Places')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

    <div class="row w-100 d-flex align-items-baseline mb-2">
      <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
        <span class="text-muted fw-light">{{ __('User Settings') }} /</span> {{ $user->fullname }}
      </h4>
      <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
        <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12" data-bs-toggle="modal" data-bs-target="#adduser">
          <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
        </button>
      </div>
    </div>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('user/'. $user->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Inforamtion')}}</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Places') }}</a></li>
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
            <table class="table table-striped w-100" id="userPlaces" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  <th>#</th>
                  <th>{{__('Place Number')}}</th>
                  <th>{{__('Longitude')}}</th>
                  <th>{{__('Latitude')}}</th>
                  <th>{{__('created At')}}</th>
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
        #userPlaces_length {
          display: none;
        }

        #userPlaces_filter {
          display: none;
        }

        #userPlaces_paginate {
          display: none;
        }
        #userPlaces_info {
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

var select = $('select[multiple]');
    var options = select.find('option');

    var div = $('<div />').addClass('selectMultiple w-100');
    var active = $('<div />');
    var list = $('<ul />');
    var placeholder = select.data('placeholder');

    var span = $('<span />').text(placeholder).appendTo(active);

    options.each(function() {
        var text = $(this).text();
        if($(this).is(':selected')) {
            active.append($('<a />').html('<em>' + text + '</em><i></i>'));
            span.addClass('hide');
        } else {
            list.append($('<li />').html(text));
        }
    });

    active.append($('<div />').addClass('arrow'));
    div.append(active).append(list);

    select.wrap(div);

    $(document).on('click', '.selectMultiple ul li', function(e) {
        var select = $(this).parent().parent();
        var li = $(this);
        if(!select.hasClass('clicked')) {
            select.addClass('clicked');
            li.prev().addClass('beforeRemove');
            li.next().addClass('afterRemove');
            li.addClass('remove');
            var a = $('<a />').addClass('notShown').html('<em>' + li.text() + '</em><i></i>').hide().appendTo(select.children('div'));
            a.slideDown(400, function() {
                setTimeout(function() {
                    a.addClass('shown');
                    select.children('div').children('span').addClass('hide');
                    select.find('option:contains(' + li.text() + ')').prop('selected', true);
                }, 500);
            });
            setTimeout(function() {
                if(li.prev().is(':last-child')) {
                    li.prev().removeClass('beforeRemove');
                }
                if(li.next().is(':first-child')) {
                    li.next().removeClass('afterRemove');
                }
                setTimeout(function() {
                    li.prev().removeClass('beforeRemove');
                    li.next().removeClass('afterRemove');
                }, 200);

                li.slideUp(400, function() {
                    li.remove();
                    select.removeClass('clicked');
                });
            }, 600);
        }
    });

    $(document).on('click', '.selectMultiple > div a', function(e) {
        var select = $(this).parent().parent();
        var self = $(this);
        self.removeClass().addClass('remove');
        select.addClass('open');
        setTimeout(function() {
            self.addClass('disappear');
            setTimeout(function() {
                self.animate({
                    width: 0,
                    height: 0,
                    padding: 0,
                    margin: 0
                }, 300, function() {
                    var li = $('<li />').text(self.children('em').text()).addClass('notShown').appendTo(select.find('ul'));
                    li.slideDown(400, function() {
                        li.addClass('show');
                        setTimeout(function() {
                            select.find('option:contains(' + self.children('em').text() + ')').prop('selected', false);
                            if(!select.find('option:selected').length) {
                                select.children('div').children('span').removeClass('hide');
                            }
                            li.removeClass();
                        }, 400);
                    });
                    self.remove();
                })
            }, 300);
        }, 400);
    });

    $(document).on('click', '.selectMultiple > div .arrow, .selectMultiple > div span', function(e) {
        $(this).parent().parent().toggleClass('open');
    });


      var userPlacesdataTable;
    function submitRemovePlaceWorker(placeid, userid) {

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
                    text: 'Place Removed From Worker successfully!',
                });
                userPlacesdataTable.ajax.reload();
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

      $('#addWorkerPlace').on('submit', function(e) {
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
                    text: 'Worker Add To Place successfully!',
                });
                userPlacesdataTable.ajax.reload();
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
      userPlacesdataTable = $('#userPlaces').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,

        language: {
          info: "_START_-_END_ of _TOTAL_",
        },
        ajax: '{{ url("worker-places/" . $user->id) }}',
        columns: [
          // { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
          { data: 'id', title: '#' },
          { data: 'place_id', title: 'Place ID' },
          { data: 'longitude', title: 'longitude' },
          { data: 'latitude', title: 'latitude' },
          { data: 'created_at', title: 'Created At' },
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
        userPlacesdataTable.search(this.value).draw();
      });
      $('#RowSelect').on('change', function () {
        userPlacesdataTable.page.len(this.value).draw();
      });

      updateCustomPagination();

      // Function to update custom pagination
      function updateCustomPagination() {
          var customPaginationContainer = $('#custom-pagination');
          var pageInfo = userPlacesdataTable.page.info();

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
        userPlacesdataTable.page(page).draw(false);
      };


    });
  </script>


@endsection
