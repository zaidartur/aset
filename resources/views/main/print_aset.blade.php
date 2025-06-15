@extends('layouts.layout')

@section('title', 'Labeling Aset')
@section('master', '')
    

@section('css')
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/select2/select2.css" />
<!-- Row Group CSS -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
<!-- Form Validation -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/@form-validation/umd/styles/index.min.css" />
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table" id="tb_aset" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 5%;">
                            <div class="checkbox" title="Pilih Semua">
                                <input type="checkbox" name="checkall" id="checkall" class="form-check-input">
                                <label for="checkall">&nbsp;</label>
                            </div>
                        </th>
                        <th style="width: 20%;">Nama Barang</th>
                        <th style="width: 15%;">Merek Barang</th>
                        <th style="width: 5%;">Tahun Pembelian</th>
                        <th style="width: 15%;">Ruang</th>
                        <th style="width: 10%;">Kondisi</th>
                        <th style="width: 15%;">Kode</th>
                        <th style="width: 15%;">Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection


@section('js')
<!-- Vendors JS -->
<script src="{{ asset('') }}assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/select2/select2.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<!-- Flat Picker -->
<script src="{{ asset('') }}assets/vendor/libs/moment/moment.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.js"></script>
<!-- Form Validation -->
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>
<script src="{{ asset('') }}assets/vendor/libs/bs-stepper/bs-stepper.js"></script>

<script>
    let tb_aset
    $(document).ready(function() {
        var dt_basic_table = $('#tb_aset'),
            dt_complex_header_table = $('.dt-complex-header'),
            dt_row_grouping_table = $('.dt-row-grouping'),
            dt_multilingual_table = $('.dt-multilingual'),
            dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                language: {
                    processing: "Memproses ...",
                },
                proccessing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: '{{ route("report.label.ss") }}',
                },
                columns: [
                    { data: 'checkbox' },
                    { data: 'nama' },
                    { data: 'merek' },
                    { data: 'tahun' },
                    { data: 'ruang' },
                    { data: 'kondisi' },
                    { data: 'kode' },
                    { data: 'opsi' },
                ],
                columnDefs: [
                    {
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    },
                    {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    },
                    {
                        responsivePriority: 1,
                        targets: 2,
                        orderable: false,
                    },
                    {
                        targets: 3,
                        orderable: false,
                    },
                    {
                        targets: 4,
                        orderable: false,
                    },
                    {
                        targets: 5,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        targets: 6,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        targets: 7,
                        searchable: false,
                        orderable: false,
                    },
                ],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-4"l><"#toolbar.title-select col-sm-12 col-md-4 d-flex justify-content-center"><"col-sm-12 col-md-4 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                    {
                        extend: 'collection',
                        text: '<i class="ti ti-select me-sm-1"></i> <span class="d-none d-sm-inline-block">Custom Print</span>',
                        className: 'btn btn-success me-2 dropdown-toggle waves-effect bt-sel',
                        attr: {
                            'id': 'btn_sel',
                            'data-bs-toggle': 'dropdown',
                            'aria-haspopup': 'true',
                            'aria-expanded': 'false',
                        },
                        // action: function (e, dt, node, config) {
                        //     // _print_selected()
                        //     return false
                        // },
                        buttons: [
                            {
                                text: 'Ukuran Besar',
                                className: 'dropdown-item waves-effect',
                                action: function (e, dt, node, config) {
                                    _print_selected('big')
                                }
                            },
                            {
                                text: 'Ukuran Kecil',
                                className: 'dropdown-item waves-effect',
                                action: function (e, dt, node, config) {
                                    _print_selected('small')
                                }
                            }
                        ],
                    },
                    {
                        extend: 'collection',
                        text: '<i class="ti ti-printer me-sm-1"></i> <span class="d-none d-sm-inline-block">Print All</span>',
                        className: 'btn btn-primary me-2 dropdown-toggle waves-effect bt-all',
                        // action: function (e, dt, node, config) {
                        //     _print_all()
                        // },
                        buttons: [
                            {
                                text: 'Ukuran Besar',
                                className: 'dropdown-item waves-effect',
                                action: function (e, dt, node, config) {
                                    _print_all('big')
                                }
                            },
                            {
                                text: 'Ukuran Kecil',
                                className: 'dropdown-item waves-effect',
                                action: function (e, dt, node, config) {
                                    _print_all('small')
                                }
                            }
                        ],
                    },
                    {
                        text: '<i class="ti ti-file-spreadsheet me-sm-1"></i> <span class="d-none d-sm-inline-block">Export Excel</span>',
                        className: 'btn btn-label-info me-2',
                        action: function (e, dt, node, config) {
                            _export()
                        }
                    },
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['nama'];
                            }
                        }),
                        type: 'column',
                        renderer: function (api, rowIdx, columns) {
                            var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                        col.rowIndex +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                            '<td>' +
                                                col.title +
                                                ':' +
                                            '</td> ' +
                                            '<td>' +
                                                col.data +
                                            '</td>' +
                                        '</tr>'
                                : '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });

            $('div.head-label').html('<h5 class="card-title mb-0">Tabel Data Aset</h5>');
            $("div .title-select").html('<label class="m-l-15 m-t-15"><strong> 0 Selected</strong></label>');

            dt_basic.on('draw.dt', function() {
                // var PageInfo = $('#tb_aset').DataTable().page.info();
                // dt_basic.column(0, {
                //     page: 'current'
                // }).nodes().each(function(cell, i) {
                //     cell.innerHTML = i + 1 + PageInfo.start;
                // });
                $('.bt-sel').attr('disabled', '')
                $('.bt-sel').hide()
                $("div .title-select").html('<label class="m-l-15 m-t-15"><strong> 0 Selected</strong></label>');
            });
            dt_basic.on('search.dt', function () {
                document.getElementById('checkall').checked = false
                document.getElementById('checkall').indeterminate = false
            });
        }

        const numeralMask = document.querySelectorAll('.numeral-mask');
        // Verification masking
        if (numeralMask.length) {
            numeralMask.forEach(e => {
                new Cleave(e, {
                    numeral: true
                });
            });
        }

        $('#checkall').click(function () {
            if ($(this).is(':checked')) {
                $('.select-print').prop('checked', true);
                $('.bt-sel').removeAttr('disabled');
            } else {
                $('.select-print').prop('checked', false);
                $('.bt-sel').attr('disabled', '');
            }

            var totalchecked = 0;
            $('.select-print').each(function () {
                if ($(this).is(':checked')) {
                    totalchecked += 1;
                    $('.bt-sel').removeAttr('disabled');
                }
            });
            $("div .title-select").html('<label class="m-l-15 m-t-15"><strong> ' + totalchecked + ' Selected</strong></label>');
        });
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    function _detail(uid) {
        $.ajax({
            url: '/tabel-aset/detail/' + uid,
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                console.log(res)
                if (res.status === 'success') {
                    //
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Data tidak ditemukan!"
                    })
                }
            },
            error: function() {
                Toast.fire({
                    icon: "error",
                    title: "Terjadi kesalahan pada sistem!"
                })
            }
        })
    }

    function checkcheckbox() {
        // Total checkboxes
        const length = $('.select-print').length;

        // Total checked checkboxes
        let totalchecked = 0;
        const checkbox = document.getElementById('checkall')
        $('.select-print').each(function () {
            if ($(this).is(':checked')) {
                totalchecked += 1;
                $('.bt-sel').removeAttr('disabled');
            }
        });
        $("div .title-select").html('<label class="m-l-15 m-t-15"><strong> ' + totalchecked + ' Selected</strong></label>');
        if (totalchecked > 0) {
            if (totalchecked < length) {
                checkbox.indeterminate = true
            } else {
                checkbox.checked = true
            }
            $('.bt-sel').removeAttr('disabled');
        } else {
            checkbox.indeterminate = false
            checkbox.checked = false
            $('.bt-sel').attr('disabled', '');
        }

        // Checked unchecked checkbox
        // if (totalchecked == length) {
        //     $("#checkall").prop('checked', true);
        // } else {
        //     $('#checkall').prop('checked', false);
        // }
        console.log(totalchecked);
    }

    function _print(uid, size) {
        //
        console.log(uid, size)
    }

    function _print_selected(size) {
        let selected = [];

        $(".select-print:checked").each(function () {
            selected.push($(this).val());
        });

        if (size && selected.length > 0) {
            Swal.fire({
                title: "Cetak Data?",
                html: "Anda yakin ingin menyetak <b>"+ selected.length +"</b> barang "+(size === 'big' ? 'ukuran besar' : 'ukuran kecel')+"?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Konfirmasi!",
                cancelButtonText: 'Batalkan',
                allowEscapeKey: false,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("/report/print-aset?uuid="+ btoa(selected), "_blank")
                    // $.ajax({
                    //     url: "{{ route('report.aset.print') }}",
                    //     type: "get",
                    //     data: {uuid: btoa(selected)},
                    //     dataType: "JSON",
                    //     success: function(res) {
                    //         if (res.status === 'success') {
                    //             Swal.fire({
                    //                 title: "Berhasil!",
                    //                 html: "<b>"+name+"</b> berhasil dihapus!.",
                    //                 icon: "success",
                    //             }).then(function() {
                    //                 location.reload()
                    //             })
                    //         } else {
                    //             Toast.fire({
                    //                 icon: "error",
                    //                 title: "Barang gagal dihapus!"
                    //             })
                    //         }
                    //     },
                    //     error: function() {
                    //         Toast.fire({
                    //             icon: "error",
                    //             title: "Terjadi kesalahan pada sistem!"
                    //         })
                    //     }
                    // })
                }
            })
        }
        console.log(size, selected)
    }

    function _print_all(size) {
        //
    }

    function _export() {
        //
    }
</script>
@endsection