@extends('layouts/contentNavbarLayout')

@section('title', ' Place - Copied')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">{{ __('Place Copied') }} /</span> {{ $place->name }}
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id)}}"><i class="mdi mdi-account-outline mdi-20px me-1"></i>{{ __('Information')}}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id . '/counters')}}"><i class="mdi mdi-bell-outline mdi-20px me-1"></i>{{ __('Counters') }}</a></li>
      <li class="nav-item"><a class="nav-link" href="{{url('place/'. $place->id . '/workers')}}"><i class="mdi mdi-link mdi-20px me-1"></i>{{ __('Workers')}}</a></li>
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="mdi mdi-map-outline mdi-20px me-1"></i>{{ __('Copied')}}</a></li>
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
            <h5 class="card-header col-sm-5 col-lg-3 col-xl-3 d-flex align-items-center ms-auto" dir="rtl">{{__('All Copied')}}</h5>
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table table-striped w-100" id="placeCopied" dir="rtl">
              <thead>
                <tr class="text-nowrap">
                  <th>{{__('Counter Id')}}</th>
                  <th>{{__('Longitude')}}</th>
                  <th>{{__('Latitude')}}</th>
                  <th>{{__('Phone')}}</th>
                  <th>{{__('Copy')}}</th>
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
        #placeCopied_length {
          display: none;
        }

        #placeCopied_filter {
          display: none;
        }

        #placeCopied_paginate {
          display: none;
        }
        #placeCopied_info {
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-editable/1.3.3/jquery.editable.min.js"></script>

<script>

var lang = "{{ app()->getLocale() }}"
var placeCopiedDataTable;

    function submitRemoveCounterPlace(placeid, counterid) {

      $.ajax({
          type: 'GET',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '/place/remove-counter/' + placeid + '/' + counterid,
          success: function (response) {
              Swal.fire({
                  icon: 'success',
                  title: response.state,
                  text: response.message,
              });
              placeCopiedDataTable.ajax.reload();
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



  $(document).ready( function () {

    function initializeCustomSelects() {
        $('select[multiple]').each(function () {
            var select = $(this);
            var options = select.find('option');
            var div = $('<div />').addClass('selectMultiple w-100 text-wrap');
            var active = $('<div />');
            var list = $('<ul />');
            var placeholder = select.data('placeholder');
            var span = $('<span />').text(placeholder).appendTo(active);

            options.each(function () {
                var text = $(this).text();
                if ($(this).is(':selected')) {
                    active.append($('<a />').html('<em>' + text + '</em><i></i>'));
                    span.addClass('hide');
                } else {
                    list.append($('<li />').html(text));
                }
            });

            active.append($('<div />').addClass('arrow'));
            div.append(active).append(list);
            select.wrap(div).addClass('selectMultiple'); // Add the selectMultiple class here
        });
    }

    // Initialize custom select dropdowns
    initializeCustomSelects();



      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $(document).on('submit', '[id^="addCounterWorker-"]', function(e) {
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
                placeCopiedDataTable.ajax.reload();
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


      placeCopiedDataTable = $('#placeCopied').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        responsive: true,

        language: {
          info: "_START_-_END_ of _TOTAL_",
        },
        ajax: '{{ route("place-copied-table", ["id" => $place->id]) }}',
        columns: [
          { data: 'counter_id', title: '{{__("Counter Id")}}' },
          { data: 'longitude', title: '{{__("Longitude")}}',"searchable": false },
          { data: 'latitude', title: '{{__("Latitude")}}',"searchable": false },
          { data: 'phone', title: '{{__("Phone")}}' },
          { data: 'copy', title: '{{__("Copy")}}' },
          { data: 'created_at', title: '{{__("Created At")}}' },
          { data: 'actions', title: '{{__("Actions")}}' }
        ],
        "order": [[4, "desc"]],
        "drawCallback": function () {
          updateCustomPagination();
          initializeCustomSelects();

          var pageInfo = this.api().page.info();

          // Update the content of the custom info element
          $('#infoTable').text((pageInfo.start + 1) + '-' + pageInfo.end + ' of ' + pageInfo.recordsTotal);

          var currentlyEditing = null;
        var originalValue = null;

        $('#placeCopied tbody').on('dblclick', 'td', function() {
            var cell = placeCopiedDataTable.cell(this);
            var columnIdx = cell.index().column;
            var rowIdx = cell.index().row;
            var data = cell.data();

            if (columnIdx === 3) {
                // Check if there's an already active editing cell
                // if (currentlyEditing) {
                //     // Revert the previous cell to its original value
                //     var prevCell = placeCopiedDataTable.cell(currentlyEditing);
                //     $(currentlyEditing.node()).html(originalValue);
                // }

                if (currentlyEditing) {
                    if (currentlyEditing.index().row === rowIdx && currentlyEditing.index().column === columnIdx) {
                        // Do nothing if double-click is on the same cell that is already being edited
                        return;
                    } else {
                        // Revert the previous cell to its original value
                        var prevCell = placeCopiedDataTable.cell(currentlyEditing);
                        $(currentlyEditing.node()).html(originalValue);
                    }
                }


                // Save the original value of the new cell
                originalValue = data;
                currentlyEditing = cell;

                $(this).html('<input type="text" name="phone" value="' + data + '"/>');
                $('input[name="phone"]').focus();

                $('input[name="phone"]').on('keypress', function(e) {
                    if (e.which == 13) { // Enter key pressed
                        var newValue = $(this).val();
                        var rowData = placeCopiedDataTable.row(rowIdx).data();
                        var rowId = rowData.id; // Assuming the row has a 'counter_id' field

                        // Send AJAX request to update the value
                        $.ajax({
                            url: '/counters/save-counter-phone', // Replace with your URL
                            method: 'POST',
                            data: {
                                counter_id: rowId,
                                number: newValue,
                                _token: '{{ csrf_token() }}' // Add CSRF token if using Laravel
                            },
                            // Toast Here
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


                              placeCopiedDataTable.ajax.reload();
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
        placeCopiedDataTable.search(this.value).draw();
      });
      $('#RowSelect').on('change', function () {
        placeCopiedDataTable.page.len(this.value).draw();
      });

      updateCustomPagination();

      // Function to update custom pagination
      function updateCustomPagination() {
          var customPaginationContainer = $('#custom-pagination');
          var pageInfo = placeCopiedDataTable.page.info();

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
        placeCopiedDataTable.page(page).draw(false);
      };

    });
  </script>

@endsection
