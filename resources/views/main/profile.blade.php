@extends('layouts.layout')

@section('title', 'Profile')


@section('css')
    
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            <img
                                class="img-fluid rounded mb-3 pt-1 mt-4"
                                src="{{ asset('') }}assets/img/avatars/1.png"
                                height="100"
                                width="100"
                                alt="User avatar" 
                            />
                            <div class="user-info text-center">
                                <h4 class="mb-2">{{ Auth::user()->name }}</h4>
                                <span class="badge bg-label-secondary mt-1">Admin</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                        <div class="d-flex align-items-start me-4 mt-3 gap-2">
                            <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
                            <div>
                                <p class="mb-0 fw-medium">1.23k</p>
                                <small>Tasks Done</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mt-3 gap-2">
                            <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-briefcase ti-sm"></i></span>
                            <div>
                                <p class="mb-0 fw-medium">568</p>
                                <small>Projects Done</small>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 small text-uppercase text-muted">Details</p>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Email:</span>
                                <span>{{ Auth::user()->email }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Status:</span>
                                <span class="badge bg-label-success">Aktif</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Tanggal Buat</span>
                                <span>{{ Auth::user()->created_at }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Update Terakhir</span>
                                <span>{{ Auth::user()->updated_at }}</span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal" >Edit</a>
                            {{-- <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspended</a> --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
        </div>
        <!--/ User Sidebar -->

        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <div class="card mb-4">
            <h5 class="card-header">Daftar User</h5>
            <div class="table-responsive mb-3">
                <table class="table datatable-project border-top">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th class="text-nowrap">Nama</th>
                            <th>Tanggal Buat</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    @if($item->id == Auth::user()->id)
                                        <button type="button" class="btn btn-warning btn-icon me-3" title="Edit Akun"><i class="ti ti-pencil"></i></button>
                                    @endif
                                    @if ($item->id != Auth::user()->id)
                                        <button type="button" class="btn btn-danger btn-icon me-3" title="Hapus Akun"><i class="ti ti-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">Edit Profile User</h3>
                    <p class="text-muted">Silahkan mengisi password baru apabila ingin merubah password yang lama.</p>
                </div>
                <form id="editUserForm" class="row g-3" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Nama</label>
                        <input type="hidden" name="uuid" value="{{ Auth::user()->uuid }}" required>
                        <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" required autofocus />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}" disabled />
                    </div>
                    <div class="col-12">
                        <div class="divider">
                            <div class="divider-text">Ubah Password</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Konfirmasi Password Baru</label>
                        <input type="password" id="password-confirm" name="password_confirm" class="form-control" autocomplete="new-password" />
                    </div>

                    <div class="col-12 text-center">
                          <button type="submit" class="btn btn-success me-sm-3 me-1"><i class="ti ti-device-floppy"></i>&nbsp; Simpan</button>
                          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x"></i>&nbsp; Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')

<script>
    $(document).ready(function() {
        $('#editUserForm').submit(function(e) {
            // e.preventDefault();
            const pwd  = $('#password').val()
            const pwdc = $('#password-confirm').val()

            if (pwd || pwdc) {
                if (pwd === pwdc) {
                    e.submit()
                } else {
                    e.preventDefault();
                    Toast.fire({
                        icon: "error",
                        title: "Password tidak sama!"
                    })
                }
            } else {
                e.submit()
            }
        })
    })

    @if (Session::has('password') && Session::get('password') == 'true')
        $('#logout').submit()
    @endif
</script>
@endsection