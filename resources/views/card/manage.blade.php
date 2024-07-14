@extends('layouts.master')
@section('title', 'Add Member')
@section('content')
    <div class="container">
    <div class="pagetitle">
        <h1>Manage e-Card</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href={{route('card.index')}}>Home</a></li>
            <li class="breadcrumb-item active">Manage e-Card</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

        <div class="row justify-content-center">
                <div class="card">
                    <div class="card-header">e-Greeting Card</div>
                    <div class="card-body">
                            <table class="table align-middle table-borderless datatable" id="employeeTable">
                                <thead>
                                    <tr>
                                        <td>No</td>
                                        <td>Nama Lengkap</td>
                                        <td>Email</td>
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
                                        <td>{{ $item->years_of_service }} tahun</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <!-- E-Card Preview -->
                                                <form action="{{ route('card.ecard', ['user_id' => $item->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm me-2">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </form>

                                                <!-- Send Email -->
                                                <form action="{{ route('card.sendEmail', ['user_id' => $item->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm me-2">
                                                        <i class="bx bx-mail-send"></i>
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