@extends('layouts/contentNavbarLayout')

@section('title', ' Place - Workers')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
  <div class="row w-100 d-flex align-items-baseline mb-2">
    <h4 class="py-3 mb-4 col-lg-4 col-xl-4 col-md-5 col-sm-6 col-12">
      <span class="text-muted fw-light">{{ __('Place Settings') }} /</span> {{ $place->name }}
    </h4>
    <div class="col-lg-8 col-xl-8 col-md-7 col-sm-12 col-12 text-end">
      <button type="button" class="m-1 btn btn-outline-primary col-lg-3 col-xl-4 col-md-5 col-sm-5 col-12" data-bs-toggle="modal" data-bs-target="#addWorker">
        <span class="tf-icons mdi mdi-plus-outline me-1"></span>{{__('Add')}}
      </button>
    </div>
  </div>

      <!-- Modal -->
      <div class="modal fade" id="addWorker" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
          <form class="modal-content" id="addWorkerPlace" action="{{ route('add.place.worker') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
              <h4 class="modal-title" id="backDropModalTitle">{{__('Add Worker')}}</h4>
            </div>
            <div class="modal-body">

              <select class="select-mult" multiple data-placeholder="Choose Workers ..." name="selectedWorkers[]">
                @foreach ($users as $userName => $userId)
                    <option value="{{ $userId }}" {{ in_array($userId, $workers->toArray()) ? 'selected' : '' }}>
                        {{ $userName }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" id="place_id" name="place_id" value="{{ $place->id }}">
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
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
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
                  <th>{{__('Counters')}}</th>
                  <th>{{__('Status')}}</th>
                  <th>{{__('Created At')}}</th>
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

    :root {
      --textColor: #1E2330;
      --primary: #4D18FF;
    }

    .selectMultiple {
      width: 240px;
      position: relative;
    }

    .selectMultiple select {
      display: none;
    }
    .selectMultiple > div {
      position: relative;
      z-index: 2;
      padding: 8px 12px 2px 12px;
      border-radius: 8px;
      background: #fff;
      font-size: 14px;
      min-height: 44px;
      box-shadow: 0 4px 16px 0 rgba(22, 42, 90, 0.12);
      transition: box-shadow .3s ease;
    }
    .selectMultiple > div:hover {
      box-shadow: 0 4px 24px -1px rgba(22, 42, 90, 0.16);

    }
    .selectMultiple .arrow {
        position: absolute;
        right: 1px;
        top: 0;
        bottom: 0;
        cursor: pointer;
        width: 28px;
    }
    .selectMultiple .arrow::before,
    .selectMultiple .arrow::after {
        content: "";
        position: absolute;
        display: block;
        width: 2px;
        height: 8px;
        border-bottom: 8px solid #99a3ba;
        top: 43%;
        transition: all 0.3s ease;
    }
    .selectMultiple .arrow::before {
        right: 12px;
        transform: rotate(-130deg);
    }
    .selectMultiple .arrow::after {
        left: 9px;
        transform: rotate(130deg);
    }
    .selectMultiple span {
        color: #99a3ba;
        display: block;
        position: absolute;
        left: 12px;
        cursor: pointer;
        top: 8px;
        line-height: 28px;
        transition: all 0.3s ease;
    }
    .selectMultiple span.hide {
        opacity: 0;
        visibility: hidden;
        transform: translate(-4px, 0);
    }
    .selectMultiple a {
        position: relative;
        padding: 0 24px 6px 8px;
        line-height: 28px;
        color: var(--textColor);
        display: inline-block;
        vertical-align: top;
        margin: 0 6px 0 0;
    }
    .selectMultiple a em {
        font-style: normal;
        display: block;
        white-space: nowrap;
    }
    .selectMultiple a::before {
        content: "";
        left: 0;
        top: 0;
        bottom: 6px;
        width: 100%;
        position: absolute;
        display: block;
        background: rgba(228, 236, 250, 0.7);
        z-index: -1;
        border-radius: 4px;
    }
    .selectMultiple a i {
        cursor: pointer;
        position: absolute;
        top: 0;
        right: 0;
        width: 24px;
        height: 28px;
        display: block;
    }
    .selectMultiple a i::before,
    .selectMultiple a i::after {
        content: "";
        display: block;
        width: 2px;
        height: 10px;
        position: absolute;
        left: 50%;
        top: 50%;
        background: var(--primary);
        border-radius: 1px;
    }
    .selectMultiple a i::before {
        transform: translate(-50%, -50%) rotate(45deg);
    }
    .selectMultiple a i::after {
        transform: translate(-50%, -50%) rotate(-45deg);
    }
    .selectMultiple a.notShown {
        /* opacity: 0;
        transition: opacity 0.3s ease; */
    }
    .selectMultiple a.notShown::before {
        /* width: 28px; */
        transition: width 0.45s
            cubic-bezier(0.87, -0.41, 0.19, 1.44) 0.2s;
    }
    .selectMultiple a.notShown i {
        opacity: 0;
        transition: all 0.3s ease 0.3s;
    }
    .selectMultiple a.notShown em {
        opacity: 0;
        transform: translate(-6px, 0);
        transition: all 0.4s ease 0.3s;
    }
    .selectMultiple a.notShown.shown {
        opacity: 1;
    }
    .selectMultiple a.notShown.shown::before {
        width: 100%;
    }
    .selectMultiple a.notShown.shown i {
        opacity: 1;
    }
    .selectMultiple a.notShown.shown em {
        opacity: 1;
        transform: translate(0, 0);
    }
    .selectMultiple a.remove::before {
        width: 28px;
        transition: width 0.4s cubic-bezier(0.87, -0.41, 0.19, 1.44)
            0s;
    }
    .selectMultiple a.remove i {
        opacity: 0;
        transition: all 0.3s ease 0s;
    }
    .selectMultiple a.remove em {
        opacity: 0;
        transform: translate(-12px, 0);
        transition: all 0.4s ease 0s;
    }
    .selectMultiple a.remove.disappear {
        opacity: 0;
        transition: opacity 0.5s ease 0s;
    }

    .selectMultiple > ul {
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 16px;
        z-index: 1;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        visibility: hidden;
        opacity: 0;
        border-radius: 8px;
        transform: translate(0, 20px) scale(0.8);
        transform-origin: 0 0;
        filter: drop-shadow(0 12px 20px rgba(22, 42, 90, 0.08));
        transition: all 0.4s ease,
            transform 0.4s cubic-bezier(0.87, -0.41, 0.19, 1.44),
            filter 0.3s ease 0.2s;
        max-height: 200px;
        overflow-y: auto;
    }
    .selectMultiple ul li {
      color: var(--textColor);
      background: #fff;
      padding: 12px 16px;
      cursor: pointer;
      overflow: hidden;
      position: relative;
      transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease 0.3s, opacity 0.5s ease 0.3s, border-radius 0.3s ease 0.3s;

    }
    .selectMultiple ul li:first-child {
      border-radius: 8px 8px 0 0;
    }
    .selectMultiple ul li:first-child:last-child {
      border-radius: 8px;
    }
    .selectMultiple ul li:last-child {
        border-radius: 0 0 8px 8px;
    }
    .selectMultiple ul li:last-child:first-child {
        border-radius: 8px;
    }
    .selectMultiple ul li:hover {
        background: var(--primary);
        color: #fff;
    }
    .selectMultiple ul li::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 6px;
      height: 6px;
      background: rgba(#000, 0.4);
      opacity: 0;
      border-radius: 100%;
      transform: scale(1, 1) translate(-50%, -50%);
      transform-origin: 50% 50%;
    }
    .selectMultiple ul li.beforeRemove{
      border-radius: 0 0 8px 8px;
    }
    .selectMultiple ul li.beforeRemove:first-child{
      border-radius: 8px;
    }
    .selectMultiple ul li.afterRemove{
      border-radius: 0 0 8px 8px;
    }
    .selectMultiple ul li.afterRemove:first-child{
      border-radius: 8px;
    }
    .selectMultiple ul li.remove {
        transform: scale(0);
        opacity: 0;
    }
    .selectMultiple ul li.remove::after {
        animation: ripple 0.4s ease-out;
    }
    .selectMultiple ul li.notShown {
        display: none;
        transform: scale(0);
        opacity: 0;
        transition: transform 0.35s ease, opacity 0.4s ease;
    }
    .selectMultiple ul li.notShown.show {
        transform: scale(1);
        opacity: 1;
    }



    .selectMultiple.open > div {
      box-shadow: 0 4px 20px -1px rgba(22, 42, 90, 0.12);
    }
    .selectMultiple.open .arrow::before {
        transform: rotate(-50deg);
    }
    .selectMultiple.open .arrow::after {
        transform: rotate(50deg);
    }

    .selectMultiple.open ul {
      transform: translate(0, 12px) scale(1);
            opacity: 1;
            visibility: visible;
            filter: drop-shadow(0 16px 24px rgba(22, 42, 90, 0.16););
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 1;
        }
        25% {
            transform: scale(30, 30);
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: scale(50, 50);
        }
    }

    .dribbble {
            position: fixed;
            display: block;
            right: 20px;
            bottom: 20px;
            opacity: 0.5;
            transition: all 0.4s ease;
    }

    .dribbble:hover {
        opacity: 1;
    }
    img {
        display: block;
        height: 36px;
    }


        </style>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>

<script type="text/javascript">


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

    var placeWorkersDataTable;
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
                    title: response.state,
                    text: response.message,
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
                    title: response.state,
                    text: response.message,

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
      { data: 'fullname', name: '{{ __("Full Name")}}' },
      { data: 'email', name: '{{ __("Email")}}' },
      { data: 'phone', name: '{{ __("Phone")}}' },
      { data: 'counters', name: '{{ __("Counters")}}' },
      { data: 'status', name: '{{ __("Status")}}' },
      { data: 'created_at', name: '{{ __("Created At")}}' },
      { data: 'actions', name: '{{ __("Actions")}}', orderable: false, searchable: false },
    ],
    "order": [[6, "desc"]],
    "drawCallback": function () {
      updateCustomPagination();
      var pageInfo = this.api().page.info();

      // Update the content of the custom info element
      $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);
      $('#placeWorkers tbody').on('dblclick', 'tr', function () {
          var userId = $(this).find('a[data-worker-id]').attr('href').split('/').pop();
          window.location.href = '/user/' + userId;
      });

      $('.modal').on('dblclick', function (event) {
            event.stopPropagation();
        });
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
