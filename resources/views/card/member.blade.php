@extends('layouts.master')
@section('title', 'Member Page')
@section('content')
<div class="container">
<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header">Table</div>
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
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('card.search') }}" method="GET" class="d-inline-flex me-2">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
                        </div>
                    </form>
                    <form action="{{ route('card.member') }}" method="GET" class="d-inline-flex me-2">
                        <button class="btn btn-warning btn-sm" type="submit">Back</button>
                    </form>
                    <form action="{{ route('card.download_template') }}" method="GET" class="d-inline-flex">
                        <div class="input-group">
                            <button class="btn btn-success btn-sm" type="submit">Download Format</button>
                        </div>
                    </form>
                </div>
                <hr>
                <table id="employeeTable" class="table text-center align-middle">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Nama Lengkap</td>
                            <td>Email</td>
                            <td>Nomor Hp / Whatsapp</td>
                            <td>Jenis Kelamin</td>
                            <td>Tahun Masuk</td>
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
                            <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</td>
                            <td>{{ $item->years_of_service }} tahun</td>
                            <td class="text-center">
                                <form action="{{ route('card.ecard') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-link">Download</button>
                                </form>
                                <a href="{{ route('card.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('card.destroy', $item->id) }}" method="post" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <form action="{{ route('card.sendEmail') }}" method="post" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm">Send Email</button>
                                </form>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#employeeTable').DataTable({
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "lengthChange": false
        });
    });
</script>
@endsection