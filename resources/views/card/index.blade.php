@extends('layouts.master')
@section('content')
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href={{route('card.index')}}>Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Total Employee Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card employee-card">

                <div class="card-body">
                  <h5 class="card-title">Total Employee</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bxs-group"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $totalEmployees }}</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Total Employee Card -->
            
            <!-- Sent Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sent-card">

                <div class="card-body">
                  <h5 class="card-title">Email Sent</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-mail-send"></i>
                    </div>
                    <div class="ps-3">
                      {{-- Calculate number of sent emails --}}
                      @php
                        $sentEmailCount = $employees->where('email_sent', true)->count();
                      @endphp
                      <h6>{{ $sentEmailCount }}</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sent Card -->

            <!-- Employee Data Table -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="card-body">
                  <h5 class="card-title">Employee Information</h5>

                  <table class="table table-borderless datatable" id="employee-table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Tanggal Ulang Tahun Kerja</th>
                        <th scope="col">Status (Email Dikirim)</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employees as $employee)
                      <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->start_date)->formatLocalized('%d %B') }}</td>
                        <td>
                          @if ($employee->email_sent)
                          <span class="badge bg-success">Sudah dikirim</span>
                          @else
                          <span class="badge bg-danger">Belum dikirim</span>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- End Employee Data Table -->
          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">

            <div class="card-body">
              <h5 class="card-title">Upcoming Celebration</h5>

              <div class="activity">

              @foreach ($upcomingAnniversaries as $anniversary)
                <div class="activity-item d-flex">
                    <div class="activite-label">{{ $anniversary['days_remaining'] == 0 ? 'Today' : $anniversary['days_remaining'] . ' days' }}</div>
                      <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                      <div class="activity-content">
                      {{ $anniversary['display_text'] }}
                      </div>
                </div><!-- End activity item-->
              @endforeach

              </div>

            </div>
          </div><!-- End Recent Activity -->
          </div><!-- End Right side columns -->
          </div>
    </section>
@endsection