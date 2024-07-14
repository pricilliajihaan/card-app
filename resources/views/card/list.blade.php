@extends('layouts.master')
@section('title', 'List Employee')
@section('content')
    <div class="container">
    <div class="pagetitle">
        <h1>Employees</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href={{route('card.index')}}>Home</a></li>
            <li class="breadcrumb-item active">Employee Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

        <div class="row justify-content-center">
                <div class="card">
                    <div class="card-header">Employee Data</div>
                    <div class="card-body">
                            <table class="table align-middle table-borderless datatable" id="employeeTable">
                                <thead>
                                    <tr>
                                    <td>No</td>
                                    <td>Nama Lengkap</td>
                                    <td>Email</td>
                                    <td>Nomor Hp</td>
                                    <td>Jenis Kelamin</td>
                                    <td>Lama Kerja</td>
                                    <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($members as $key => $item)
                                <tr class="align-middle">
                                    <td>{{ $key + 1}}</td>
                                    <td class="text-start">{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone_number }}</td>
                                    <td>{{ $item->gender }}</td>
                                    <td>{{ $item->years_of_service }} tahun</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <!-- Ikon Detail -->
                                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $item->id }}">
                                                <i class="fas fa-info-circle"></i>
                                            </button>

                                            <!-- Ikon Edit -->
                                            <a href="{{ route('card.edit', $item->id) }}" class="btn btn-warning btn-sm me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Ikon Delete -->
                                            <form action="{{ route('card.destroy', $item->id) }}" method="post" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
    </div>

    @foreach ($members as $item)
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal-{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel-{{ $item->id }}">Detail Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama Lengkap:</strong> {{ $item->name }}</p>
                            <p><strong>Email:</strong> {{ $item->email }}</p>
                            <p><strong>Nomor Hp:</strong> {{ $item->phone_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jenis Kelamin:</strong> {{ $item->gender }}</p>
                            <p><strong>Tahun Masuk:</strong> {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</p>
                            <p><strong>Lama Kerja:</strong> {{ $item->years_of_service }} tahun</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!-- Tombol Preview Kartu -->
                        <form action="{{ route('card.ecard') }}" method="post" class="me-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-secondary btn-sm">Preview Kartu</button>
                        </form>
                        <!-- Tombol Send Email -->
                        <form action="{{ route('card.sendEmail') }}" method="post" class="me-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-primary btn-sm">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
  $(document).ready(function() {
      var table = $('#employeeTable').DataTable({
          "pageLength": 10,
          "searching": true,
          "ordering": true,
          "lengthChange": false,
          "responsive": true,
          "autoWidth": false, // Disable auto width calculation
      });

      // Adjust columns when sidebar is toggled
      $('.toggle-sidebar-btn').on('click', function() {
          setTimeout(function() {
              table.columns.adjust().responsive.recalc();
          }, 300); // Adjust the timeout as needed
      });
  });
</script>

@endsection