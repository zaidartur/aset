@extends('layouts.layout')

@section('title', 'Data Paket')
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
                        <th style="width: 5%;">#</th>
                        <th style="width: 15%;">Nama Barang</th>
                        <th style="width: 15%;">Merek</th>
                        <th style="width: 5%;">Tahun Pembelian</th>
                        <th style="width: 15%;">Ruang</th>
                        <th style="width: 10%;">Jumlah Barang</th>
                        <th style="width: 20%;">Keterangan</th>
                        <th style="width: 15%;">Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="detailBarang" tabindex="-1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="detailTitle">Detail Data</h3>
                    <p class="text-muted">&nbsp;</p>
                </div>

                <div class="row g-3 mb-5" id="isContent"></div>

                <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x"></i>&nbsp; Tutup</button>
                </div>
            </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/id.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('') }}assets/js/data-aset-validation.js"></script>

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
                    url: '{{ route("aset.paket.ss") }}',
                },
                columns: [
                    { data: null },
                    { data: 'nama' },
                    { data: 'merek' },
                    { data: 'tahun' },
                    { data: 'ruang' },
                    { data: 'jumlah' },
                    { data: 'keterangan' },
                    { data: 'opsi' },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        target: 1,
                        orderable: false,
                    },
                    {
                        responsivePriority: 1,
                        targets: 2,
                        orderable: false,
                    },
                    {
                        targer: 3,
                        orderable: false,
                    },
                    {
                        targer: 4,
                        orderable: false,
                    },
                    {
                        targets: 5,
                        searchable: false,
                        orderable: false,
                    },
                ],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0">><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['full_name'];
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
            dt_basic.on('draw.dt', function() {
                var PageInfo = $('#tb_aset').DataTable().page.info();
                dt_basic.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        }

        $('#subdata').select2({
            placeholder: 'Pilih Kategori',
            // search: true,
            // allowClear: true,
            // dropdownParent: $('#add_new'),
        })

        const numeralMask = document.querySelectorAll('.numeral-mask');
        // Verification masking
        if (numeralMask.length) {
            numeralMask.forEach(e => {
                new Cleave(e, {
                    numeral: true
                });
            });
        }
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var $state, val = state.element.value
        if (val === 'b') {
            $state = $(`<span><i class="ti ti-checks text-success"></i> ${state.text}</span>`)
        } else if (val === 'rr') {
            $state = $(`<span><i class="ti ti-egg-cracked text-warning"></i> ${state.text}</span>`)
        } else if (val === 'rb') {
            $state = $(`<span><i class="ti ti-alert-triangle text-danger"></i> ${state.text}</span>`)
        } else {
            $state = $(`<span>${state.text}</span>`)
        }
        return $state;
    }


    function _detail(uid) {
        const det = JSON.parse(atob(uid))

        console.log(det)
        // $.ajax({
        //     url: '/tabel-aset/detail/' + uid,
        //     type: 'GET',
        //     dataType: 'JSON',
        //     success: function(res) {
        //         if (res.status === 'success') {
        //             const data = res.data
        //             $('#detailTitle').html(`Detail Barang <b>${data.nama_barang}</b>`)
        //             let text = ''
        //             text += `<div class="col-12 col-md-6">
        //                         <label class="form-label" for="uraian">Kode Parameter</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-filter"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.subdata.kode_subdata}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-6">
        //                         <label class="form-label" for="uraian">Nama Parameter</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-list-details"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.parameter.uraian}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Nomor Register</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-number"></i></span>
        //                             <input type="text" class="form-control" value="   ${(parseInt(data.kode_urut) < 9 ? ('00' + data.kode_urut) : ((parseInt(data.kode_urut) > 9 && parseInt(data.kode_urut) < 99) ? ('0' + data.kode_urut) : data.kode_urut))}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Uraian</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-list-details"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.uraian}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Nama Barang</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-letter-case"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.nama_barang}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Merek</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-article"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.merek_barang}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Tipe Barang</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-category-2"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.type_barang ?? '-'}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Ukuran/Dimensi</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-dimensions"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.ukuran_barang ?? '-'}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Bahan</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-atom"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.bahan ?? '-'}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Harga Pembelian</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-cash"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.rupiah}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Tahun Pembelian</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-calendar"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.tahun_beli}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Ruang/Lokasi</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-home-2"></i></span>
        //                             <input type="text" class="form-control" value="   ${data.lokasi}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Kondisi Barang</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-${(data.kondisi_barang === 'b' ? 'circle-check text-success' : (data.kondisi_barang === 'rr' ? 'egg-cracked text-warning' : 'alert-triangle text-danger'))}"></i></span>
        //                             <input type="text" class="form-control ${(data.kondisi_barang === 'b' ? 'is-valid' : (data.kondisi_barang === 'rr' ? '' : 'is-invalid'))}" value="   ${(data.kondisi_barang === 'b' ? 'Baik' : (data.kondisi_barang === 'rr' ? 'Rusak Ringan' : 'Rusak Berat'))}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12 col-md-4">
        //                         <label class="form-label" for="uraian">Update Terakhir</label>
        //                         <div class="input-group input-group-merge">
        //                             <span class="input-group-text"><i class="ti ti-calendar-check"></i></span>
        //                             <input type="text" class="form-control" value="   ${(data.updated_at ? (moment(data.updated_at).format('DD MMMM YYYY')) : (moment(data.created_at).format('DD MMMM YYYY')))}" disabled />
        //                         </div>
        //                     </div>
        //                     <div class="col-12">
        //                         <label class="form-label" for="uraian">Keterangan</label>
        //                         <textarea cols="30" rows="5" class="form-control" disabled>${data.keterangan}</textarea>
        //                     </div>`

        //             $('#isContent').html(text)


        //             $('#detailBarang').modal('show')
        //         } else {
        //             Toast.fire({
        //                 icon: "error",
        //                 title: "Data tidak ditemukan!"
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

    function _test() {
        Toast.fire({
            icon: "error",
            title: "Terjadi kesalahan pada sistem!"
        })
    }
</script>
@endsection