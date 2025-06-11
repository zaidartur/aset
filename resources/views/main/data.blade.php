@extends('layouts.layout')

@section('title', 'Data Aset')
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
        <div class="p-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUser">
            Show
            </button>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table" id="tb_aset">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Merek Barang</th>
                        <th>Tipe Barang</th>
                        <th>Tahun Pembelian</th>
                        <th>Ruang</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="addnew" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="title_modal">Tambah Data</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="title_modal">Edit User Information</h3>
                    <p class="text-muted">&nbsp;</p>
                </div>
                <form action="#" method="POST" id="formAdd" class="row g-3" onsubmit="return false">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label for="subdata" class="form-label">Kategori *</label>
                        <div class="w-100">
                            <select name="subdata" id="subdata" class="form-select" data-allow-clear="true" style="width: 100%;" onchange="_urutan(this.value)" required>
                                {{-- @if (count($subdata) > 0)
                                    <option value="">Pilih Kategori Barang</option>
                                    @foreach ($subdata as $param)
                                        <option value="{{ $param->uuid_subdata }}">{{ $param->kode_subdata }} - {{ $param->uraian }}</option>
                                    @endforeach
                                @endif --}}
                                <option value="">Tidak Ada Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="nama" class="form-label">Nama Barang *</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Barang" required autofocus>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="urutan" class="form-label">Nomor Urut *</label>
                        <input type="number" class="form-control" id="urutan" name="urutan" placeholder="000" required readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="merek" class="form-label">Merek Barang *</label>
                        <input type="text" class="form-control" id="merek" name="merek" placeholder="Merek Barang" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="tipe" class="form-label">Tipe Barang </label>
                        <input type="text" class="form-control" id="tipe" name="tipe" placeholder="Tipe Barang">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="ukuran" class="form-label">Ukuran/Dimensi</label>
                        <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Tulis ukuran barang">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="bahan" class="form-label">Bahan</label>
                        <input type="text" class="form-control" id="bahan" name="bahan" placeholder="Bahan/Material">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="harga" class="form-label">Harga Pembelian *</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control numeral-mask" id="harga" name="harga" placeholder="Nominal harga" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="tahun" class="form-label">Tahun Pembelian *</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" placeholder="{{ date('Y') }}" max="{{ date('Y') +1 }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="ruang" class="form-label">Ruang *</label>
                        <input type="text" class="form-control" id="ruang" name="ruang" placeholder="Lokasi barang tersebut" required>
                    </div>
                    {{-- <div class="col-12 col-md-4">
                        <label for="kondisi" class="form-label">Kondisi Barang *</label>
                        <select name="kondisi" id="kondisi" class="form-select" data-allow-clear="true" required>
                            <option value="">Pilih Kondisi Barang</option>
                            <option value="b"><span class="ti ti-checks"></span> Baik</option>
                            <option value="rr">Rusak Ringan</option>
                            <option value="rb">Rusak Berat</option>
                        </select>
                    </div> --}}
                    <div class="col-12 row g-3">
                        <label for="">Kondisi Barang</label>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="baik">
                                    <span class="custom-option-body">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.336"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M13.8179 4.54512L13.6275 4.27845C12.8298 3.16176 11.1702 3.16176 10.3725 4.27845L10.1821 4.54512C9.76092 5.13471 9.05384 5.45043 8.33373 5.37041L7.48471 5.27608C6.21088 5.13454 5.13454 6.21088 5.27608 7.48471L5.37041 8.33373C5.45043 9.05384 5.13471 9.76092 4.54512 10.1821L4.27845 10.3725C3.16176 11.1702 3.16176 12.8298 4.27845 13.6275L4.54512 13.8179C5.13471 14.2391 5.45043 14.9462 5.37041 15.6663L5.27608 16.5153C5.13454 17.7891 6.21088 18.8655 7.48471 18.7239L8.33373 18.6296C9.05384 18.5496 9.76092 18.8653 10.1821 19.4549L10.3725 19.7215C11.1702 20.8382 12.8298 20.8382 13.6275 19.7215L13.8179 19.4549C14.2391 18.8653 14.9462 18.5496 15.6663 18.6296L16.5153 18.7239C17.7891 18.8655 18.8655 17.7891 18.7239 16.5153L18.6296 15.6663C18.5496 14.9462 18.8653 14.2391 19.4549 13.8179L19.7215 13.6275C20.8382 12.8298 20.8382 11.1702 19.7215 10.3725L19.4549 10.1821C18.8653 9.76092 18.5496 9.05384 18.6296 8.33373L18.7239 7.48471C18.8655 6.21088 17.7891 5.13454 16.5153 5.27608L15.6663 5.37041C14.9462 5.45043 14.2391 5.13471 13.8179 4.54512Z" fill="#2d8227"></path> <path d="M13.8179 4.54512L13.6275 4.27845C12.8298 3.16176 11.1702 3.16176 10.3725 4.27845L10.1821 4.54512C9.76092 5.13471 9.05384 5.45043 8.33373 5.37041L7.48471 5.27608C6.21088 5.13454 5.13454 6.21088 5.27608 7.48471L5.37041 8.33373C5.45043 9.05384 5.13471 9.76092 4.54512 10.1821L4.27845 10.3725C3.16176 11.1702 3.16176 12.8298 4.27845 13.6275L4.54512 13.8179C5.13471 14.2391 5.45043 14.9462 5.37041 15.6663L5.27608 16.5153C5.13454 17.7891 6.21088 18.8655 7.48471 18.7239L8.33373 18.6296C9.05384 18.5496 9.76092 18.8653 10.1821 19.4549L10.3725 19.7215C11.1702 20.8382 12.8298 20.8382 13.6275 19.7215L13.8179 19.4549C14.2391 18.8653 14.9462 18.5496 15.6663 18.6296L16.5153 18.7239C17.7891 18.8655 18.8655 17.7891 18.7239 16.5153L18.6296 15.6663C18.5496 14.9462 18.8653 14.2391 19.4549 13.8179L19.7215 13.6275C20.8382 12.8298 20.8382 11.1702 19.7215 10.3725L19.4549 10.1821C18.8653 9.76092 18.5496 9.05384 18.6296 8.33373L18.7239 7.48471C18.8655 6.21088 17.7891 5.13454 16.5153 5.27608L15.6663 5.37041C14.9462 5.45043 14.2391 5.13471 13.8179 4.54512Z" stroke="#2d8227" stroke-width="1.3679999999999999" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 12L10.8189 13.8189V13.8189C10.9189 13.9189 11.0811 13.9189 11.1811 13.8189V13.8189L15 10" stroke="#2d8227" stroke-width="1.3679999999999999" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="custom-option-title"> Baik </span>
                                        <small> Tidak ada kerusakan pada barang </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="b" id="baik" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="ringan">
                                    <span class="custom-option-body">
                                        <svg fill="#ca6716" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 496 496" xml:space="preserve" stroke="#ca6716" stroke-width="10.416"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M256,160V0h-43.744l-47.08,56.496l31.096,31.088l-83.144,72.064l33.344-66.688l-40-24L140.944,0H0v160 c0,61.448,44.24,114.248,104,125.616V448H75.776c-25.12,0-47.696,13.952-58.936,36.424L11.056,496H232h12.944H496V160H256z M288,176h48v96h-48V176z M39.128,480c9.288-10.096,22.432-16,36.656-16H120V271.816l-6.944-0.928 C57.728,263.488,16,215.824,16,160V16h99.056l-29.52,59.048l40,24L62.872,224.36L219.728,88.424l-32.904-32.912L219.744,16H240 v144c0,55.824-41.728,103.488-97.056,110.88L136,271.816V464h44.224c14.224,0,27.368,5.904,36.656,16H39.128z M480,480H236.552 c-11.88-19.8-32.944-32-56.328-32H152V285.616c54.488-10.36,95.96-55.208,102.872-109.616H272v112h80V176h128V480z"></path> <rect x="448" y="448" width="16" height="16"></rect> <rect x="416" y="448" width="16" height="16"></rect> <rect x="384" y="448" width="16" height="16"></rect> <rect x="448" y="416" width="16" height="16"></rect> <rect x="448" y="384" width="16" height="16"></rect> <rect x="416" y="416" width="16" height="16"></rect> </g> </g> </g> </g></svg>

                                        <span class="custom-option-title"> Rusak Ringan </span>
                                        <small> Terdapat kerusakan minor pada barang </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="rr" id="ringan" required>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-3">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="berat">
                                    <span class="custom-option-body">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M10.2501 5.147L3.64909 17.0287C2.9085 18.3618 3.87244 20 5.39741 20H18.5994C20.1243 20 21.0883 18.3618 20.3477 17.0287L13.7467 5.147C12.9847 3.77538 11.0121 3.77538 10.2501 5.147Z" fill="#b41313"></path> <path d="M12 10V13" stroke="#b41313" stroke-width="2.112" stroke-linecap="round"></path> <path d="M12 16V15.9888" stroke="#b41313" stroke-width="2.112" stroke-linecap="round"></path> <path d="M10.2515 5.147L3.65056 17.0287C2.90997 18.3618 3.8739 20 5.39887 20H18.6008C20.1258 20 21.0897 18.3618 20.3491 17.0287L13.7482 5.147C12.9861 3.77538 11.0135 3.77538 10.2515 5.147Z" stroke="#b41313" stroke-width="2.112" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                        <span class="custom-option-title"> Rusak Berat </span>
                                        <small> Barang tidak bisa digunakan untuk operasional </small>
                                    </span>
                                    <input name="kondisi" class="form-check-input" type="radio" value="rb" id="berat" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan barang"></textarea>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button
                        type="reset"
                        class="btn btn-label-secondary"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                        Cancel
                        </button>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="ti ti-circle-x"></i>&nbsp; Tutup</button>
                <button type="button" class="btn btn-outline-success" onclick="$('#formAdd').submit()"><i class="ti ti-device-floppy"></i>&nbsp; Simpan</button>
            </div> --}}
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg ">
    <div class="modal-content p-3 p-md-5">
    <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
        <h3 class="mb-2">Edit User Information</h3>
        <p class="text-muted">Updating user details will receive a privacy audit.</p>
        </div>
        <form id="editUserForm" class="row g-3" onsubmit="return false">
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserFirstName">First Name</label>
            <input
            type="text"
            id="modalEditUserFirstName"
            name="modalEditUserFirstName"
            class="form-control"
            required
            placeholder="John" />
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserLastName">Last Name</label>
            <input
            type="text"
            id="modalEditUserLastName"
            name="modalEditUserLastName"
            class="form-control"
            placeholder="Doe" />
        </div>
        <div class="col-12">
            <label class="form-label" for="modalEditUserName">Username</label>
            <input
            type="text"
            id="modalEditUserName"
            name="modalEditUserName"
            class="form-control"
            placeholder="john.doe.007" />
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserEmail">Email</label>
            <input
            type="text"
            id="modalEditUserEmail"
            name="modalEditUserEmail"
            class="form-control"
            placeholder="example@domain.com" />
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserStatus">Status</label>
            <select
            id="modalEditUserStatus"
            name="modalEditUserStatus"
            class="select2 form-select"
            aria-label="Default select example">
            <option selected>Status</option>
            <option value="1">Active</option>
            <option value="2">Inactive</option>
            <option value="3">Suspended</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditTaxID">Tax ID</label>
            <input
            type="text"
            id="modalEditTaxID"
            name="modalEditTaxID"
            class="form-control modal-edit-tax-id"
            placeholder="123 456 7890" />
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserPhone">Phone Number</label>
            <div class="input-group">
            <span class="input-group-text">US (+1)</span>
            <input
                type="text"
                id="modalEditUserPhone"
                name="modalEditUserPhone"
                class="form-control phone-number-mask"
                placeholder="202 555 0111" />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserLanguage">Language</label>
            <select
            id="modalEditUserLanguage"
            name="modalEditUserLanguage"
            class="select2 form-select"
            multiple>
            <option value="">Select</option>
            <option value="english" selected>English</option>
            <option value="spanish">Spanish</option>
            <option value="french">French</option>
            <option value="german">German</option>
            <option value="dutch">Dutch</option>
            <option value="hebrew">Hebrew</option>
            <option value="sanskrit">Sanskrit</option>
            <option value="hindi">Hindi</option>
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="modalEditUserCountry">Country</label>
            <select
            id="modalEditUserCountry"
            name="modalEditUserCountry"
            class="select2 form-select"
            data-allow-clear="true">
            <option value="">Select</option>
            <option value="Australia">Australia</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Belarus">Belarus</option>
            <option value="Brazil">Brazil</option>
            <option value="Canada">Canada</option>
            <option value="China">China</option>
            <option value="France">France</option>
            <option value="Germany">Germany</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Japan">Japan</option>
            <option value="Korea">Korea, Republic of</option>
            <option value="Mexico">Mexico</option>
            <option value="Philippines">Philippines</option>
            <option value="Russia">Russian Federation</option>
            <option value="South Africa">South Africa</option>
            <option value="Thailand">Thailand</option>
            <option value="Turkey">Turkey</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            </select>
        </div>
        <div class="col-12">
            <label class="switch">
            <input type="checkbox" class="switch-input" />
            <span class="switch-toggle-slider">
                <span class="switch-on"></span>
                <span class="switch-off"></span>
            </span>
            <span class="switch-label">Use as a billing address?</span>
            </label>
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button
            type="reset"
            class="btn btn-label-secondary"
            data-bs-dismiss="modal"
            aria-label="Close">
            Cancel
            </button>
        </div>
        </form>
    </div>
    </div>
</div>
</div>
<!--/ Edit User Modal -->

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

<script src="{{ asset('') }}assets/js/modal-edit-user.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        const formValidationInput = document.getElementById('formAdd'),
            formValidationSubdata = jQuery(formValidationInput.querySelector('[name="subdata"]'))

        let addNew = document.getElementById('addnew');
        addNew.addEventListener('show.bs.modal', function (event) {
            // Init custom option check
            window.Helpers.initCustomOptionCheck();
        });
        const fv = FormValidation.formValidation(formValidationInput, {
            fields: {
                // nama: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Nama barang wajib di isi'
                //         }
                //     }
                // },
                merek: {
                    validators: {
                        notEmpty: {
                            message: 'Merek barang wajib di isi'
                        }
                    }
                },
                harga: {
                    validators: {
                        notEmpty: {
                            message: 'Harga barang wajib di isi'
                        }
                    }
                },
                tahun: {
                    validators: {
                        notEmpty: {
                            message: 'Tahun pembelian wajib di isi'
                        }
                    }
                },
                kondisi: {
                    validators: {
                        notEmpty: {
                            message: 'Mohon untuk memilih kondisi barang'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                    if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                        e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });

        if (formValidationSubdata.length) {
            formValidationSubdata.wrap('<div class="position-relative"></div>');
            formValidationSubdata.select2({
                placeholder: 'Pilih Kategori',
                dropdownParent: formValidationSubdata.parent()
            }).on('change.select2', function () {
                // Revalidate the color field when an option is chosen
                fv.revalidateField('subdata');
            });
        }
    })();
});
</script>

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
                // proccessing: true,
                // serverSide: true,
                // paging: true,
                // ajax: {
                //     url: '{{ route("aset.ss") }}',
                // },
                columns: [
                    { data: null },
                    { data: 'kode' },
                    { data: 'uraian' },
                    { data: 'parameter'},
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
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                    {
                        text: '<i class="ti ti-circle-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Tambah Data</span>',
                        className: 'btn btn-success me-2',
                        action: function (e, dt, node, config) {
                            _add()
                        }
                    },
                    {
                        text: '<i class="ti ti-file-spreadsheet me-sm-1"></i> <span class="d-none d-sm-inline-block">Import Data</span>',
                        className: 'btn btn-primary me-2',
                        action: function (e, dt, node, config) {
                            _import()
                        }
                    },
                    {
                        text: '<i class="ti ti-download me-sm-1"></i> <span class="d-none d-sm-inline-block">Download Template</span>',
                        className: 'btn btn-label-info me-2',
                        action: function (e, dt, node, config) {
                            _template()
                        }
                    },
                ],
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

    function _add() {
        $('#title_modal').text('Tambah Data')
        $('#formAdd')[0].reset()
        const uid = document.getElementById('uuid')
        if (uid) {
            $('#uuid').remove()
        }
        $('#formAdd').attr('action', '{{ route("parameter.save") }}')
        $('#addnew').modal('show')
    }
</script>
@endsection