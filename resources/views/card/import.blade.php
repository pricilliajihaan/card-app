@extends('layouts.master')
@section('title', 'Import Page')
@section('content')
<div class="container">
    <div class="pagetitle">
        <h1>Employee</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href={{route('card.index')}}>Home</a></li>
                <li class="breadcrumb-item active">Import Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    
    <form action="{{ route('card.download_template') }}" method="GET" class="d-inline-flex mb-3">
        <div class="input-group">
            <button class="btn btn-success btn-sm" type="submit">Download Format</button>
        </div>
    </form>

    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">Employees</div>
                <div class="card-body">
                    <form action="{{ route('card.import_data') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="my_file" class="form-label">Xls, xlsx or csv</label>
                                <input type="file" name="my_file" id="my_file" class="form-control @error('my_file') is-invalid @enderror">
                                @error('my_file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fungsi" class="form-label">Fungsi</label>
                                <select name="fungsi" class="form-select @error('fungsi') is-invalid @enderror" id="fungsi">
                                    <option selected hidden disabled>Pilih Fungsi</option>
                                    <option value="1">Hapus & Buat Baru</option>
                                    <option value="2">Tambahkan</option>
                                </select>
                                @error('fungsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary">Upload File</button>
                        </div>
                    </form>
                    <form action="{{ route('card.truncate') }}" method="post">
                        @csrf
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-danger">Delete all</button>
                        </div>
                    </form>
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