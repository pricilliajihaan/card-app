@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Member</div>
                <div class="card-body">
                    <form action="{{ route('card.update', $member->id) }}" method="post">
                        @csrf
                        @method('PUT') <!-- Tambahkan ini -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $member->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $member->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor HP / Whatsapp</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $member->phone_number }}">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Awal Masuk</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $member->start_date }}">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male" {{ $member->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ $member->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
