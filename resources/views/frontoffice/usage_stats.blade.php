{{-- resources/views/frontoffice/usage_stats.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistik Penggunaan API - Semua User')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                            <div>
                                <h3 class="mb-2 fw-bold text-dark">📊 Statistik Penggunaan API - Semua User</h3>
                                <p class="text-muted mb-0">Monitor penggunaan dan biaya API Gemini dari seluruh pengguna</p>
                            </div>

                            <!-- Filter Section -->
                            <div class="filter-section">
                                <form method="GET" class="d-flex flex-column flex-sm-row gap-3 align-items-end">
                                    <div class="date-inputs d-flex gap-2">
                                        <div class="form-group">
                                            <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                                            <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}"
                                                   class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                                            <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="action-buttons d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            Filter
                                        </button>
                                        <a href="{{ route('backoffice.usage_export', request()->query()) }}"
                                           class="btn btn-success">
                                            Export CSV
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stats-card stats-primary">
                    <div class="stats-icon">📋</div>
                    <div class="stats-content">
                        <h3>{{ number_format($totalStats['total_requests'] ?? 0) }}</h3>
                        <p>Total Request</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stats-card stats-success">
                    <div class="stats-icon">🪙</div>
                    <div class="stats-content">
                        <h3>{{ number_format($totalStats['total_tokens'] ?? 0) }}</h3>
                        <p>Total Token</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stats-card stats-warning">
                    <div class="stats-icon">💰</div>
                    <div class="stats-content">
                        <h3>Rp {{ number_format($totalStats['total_cost_idr'] ?? 0, 0) }}</h3>
                        <p>Total Biaya</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stats-card stats-info">
                    <div class="stats-icon">👥</div>
                    <div class="stats-content">
                        <h3>{{ $userStats->count() }}</h3>
                        <p>Total User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Stats Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">👥 Statistik per User ({{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }})</h5>
                            @if($userStats->count() > 0)
                                <span class="badge badge-primary-soft">{{ $userStats->count() }} user</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($userStats->count() > 0)
                            <div class="table-responsive">
                                <table class="custom-table">
                                    <thead>
                                    <tr>
                                        <th>User</th>
                                        <th class="text-center d-none d-md-table-cell">Total Request</th>
                                        <th class="text-center d-none d-lg-table-cell">Input Token</th>
                                        <th class="text-center d-none d-lg-table-cell">Output Token</th>
                                        <th class="text-center">Total Token</th>
                                        <th class="text-end">Total Biaya</th>
                                        <th class="text-center d-none d-md-table-cell">Avg Response</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userStats as $userStat)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="user-avatar-small">
                                                    <span class="avatar-text-small">
                                                        {{ strtoupper(substr($userStat->user->name ?? 'NA', 0, 2)) }}
                                                    </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $userStat->user->name ?? 'Unknown User' }}</div>
                                                        <div class="small text-muted">{{ $userStat->user->email ?? 'No email' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center d-none d-md-table-cell">
                                                <span class="fw-medium">{{ number_format($userStat->total_requests) }}</span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="text-muted">{{ number_format($userStat->total_input_tokens) }}</span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="text-muted">{{ number_format($userStat->total_output_tokens) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ number_format($userStat->total_tokens) }}</span>
                                                <div class="small text-muted d-lg-none">
                                                    {{ number_format($userStat->total_input_tokens) }} + {{ number_format($userStat->total_output_tokens) }}
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-warning">Rp {{ number_format($userStat->total_cost_idr, 0) }}</span>
                                                <div class="small text-muted d-md-none">
                                                    {{ number_format($userStat->total_requests) }} req
                                                </div>
                                            </td>
                                            <td class="text-center d-none d-md-table-cell">
                                            <span class="small {{ $userStat->avg_response_time_ms > 3000 ? 'text-warning' : 'text-info' }}">
                                                {{ number_format($userStat->avg_response_time_ms, 0) }}ms
                                            </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                    <tr class="fw-bold">
                                        <td>TOTAL ({{ $userStats->count() }} user)</td>
                                        <td class="text-center d-none d-md-table-cell">{{ number_format($totalStats['total_requests']) }}</td>
                                        <td class="text-center d-none d-lg-table-cell">{{ number_format($userStats->sum('total_input_tokens')) }}</td>
                                        <td class="text-center d-none d-lg-table-cell">{{ number_format($userStats->sum('total_output_tokens')) }}</td>
                                        <td class="text-center text-primary">{{ number_format($totalStats['total_tokens']) }}</td>
                                        <td class="text-end text-warning">Rp {{ number_format($totalStats['total_cost_idr'], 0) }}</td>
                                        <td class="text-center d-none d-md-table-cell text-info">{{ number_format($totalStats['avg_response_time'], 0) }}ms</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">👥</div>
                                <h4>Belum Ada Data User</h4>
                                <p>Belum ada penggunaan API dari user manapun untuk periode {{ $dateFrom->format('d/m/Y') }} - {{ $dateTo->format('d/m/Y') }}</p>
                                <div class="alert alert-info d-inline-block">
                                    <small>💡 Data akan muncul setelah ada user yang menggunakan fitur diagnosis atau SWOT</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Activity Table -->
        @if($stats->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">📋 Detail Aktivitas per Hari & Tahap</h5>
                                <span class="badge badge-info-soft">{{ $stats->count() }} aktivitas</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="custom-table">
                                    <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tahap</th>
                                        <th class="text-center d-none d-md-table-cell">Request</th>
                                        <th class="text-center d-none d-lg-table-cell">Input Token</th>
                                        <th class="text-center d-none d-lg-table-cell">Output Token</th>
                                        <th class="text-center">Total Token</th>
                                        <th class="text-end">Biaya</th>
                                        <th class="text-center d-none d-md-table-cell">Avg Response</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stats as $stat)
                                        <tr>
                                            <td>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}</div>
                                                <div class="small text-muted">{{ \Carbon\Carbon::parse($stat->date)->format('l') }}</div>
                                            </td>
                                            <td>
                                        <span class="badge-custom badge-{{ $stat->step }}">
                                            {{ ucfirst(str_replace('_', ' ', $stat->step)) }}
                                        </span>
                                            </td>
                                            <td class="text-center d-none d-md-table-cell">
                                                <span class="fw-medium">{{ number_format($stat->total_requests) }}</span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="text-muted">{{ number_format($stat->total_input_tokens) }}</span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="text-muted">{{ number_format($stat->total_output_tokens) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ number_format($stat->total_tokens) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-warning">Rp {{ number_format($stat->total_cost_idr, 0) }}</span>
                                            </td>
                                            <td class="text-center d-none d-md-table-cell">
                                        <span class="small {{ $stat->avg_response_time_ms > 3000 ? 'text-warning' : 'text-info' }}">
                                            {{ number_format($stat->avg_response_time_ms, 0) }}ms
                                        </span>
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
        @endif

        <!-- Pricing Info -->
{{--        <div class="row">--}}
{{--            <div class="col-12">--}}
{{--                <div class="card border-0 shadow-lg">--}}
{{--                    <div class="card-header bg-white border-0 py-3">--}}
{{--                        <h5 class="mb-0 fw-semibold">💡 Informasi Harga Token Gemini 2.0 Flash</h5>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row g-4 mb-4">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="pricing-card pricing-input">--}}
{{--                                    <div class="pricing-header">--}}
{{--                                        <span class="pricing-icon">📥</span>--}}
{{--                                        <h6>Input Token</h6>--}}
{{--                                    </div>--}}
{{--                                    <div class="pricing-amount">Rp 75</div>--}}
{{--                                    <div class="pricing-unit">per 1.000 token</div>--}}
{{--                                    <p class="pricing-desc">Token yang dikirim ke API (prompt Anda)</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="pricing-card pricing-output">--}}
{{--                                    <div class="pricing-header">--}}
{{--                                        <span class="pricing-icon">📤</span>--}}
{{--                                        <h6>Output Token</h6>--}}
{{--                                    </div>--}}
{{--                                    <div class="pricing-amount">Rp 225</div>--}}
{{--                                    <div class="pricing-unit">per 1.000 token</div>--}}
{{--                                    <p class="pricing-desc">Token yang dihasilkan API (respons Gemini)</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="tips-section">--}}
{{--                            <h6 class="mb-3">📊 Insights & Tips</h6>--}}
{{--                            <div class="row g-3">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="tip-item">--}}
{{--                                        <span class="tip-icon">📈</span>--}}
{{--                                        <span>Monitor user dengan biaya tertinggi</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="tip-item">--}}
{{--                                        <span class="tip-icon">⚡</span>--}}
{{--                                        <span>Perhatikan response time yang lambat</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="tip-item">--}}
{{--                                        <span class="tip-icon">💰</span>--}}
{{--                                        <span>Set budget limit per user jika perlu</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="tip-item">--}}
{{--                                        <span class="tip-icon">📊</span>--}}
{{--                                        <span>Export data untuk analisis lebih lanjut</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

    <style>
        /* Existing styles... */
        :root {
            --primary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
        }

        /* User Avatar Small */
        .user-avatar-small {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-text-small {
            color: white;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        /* Badge Soft Styles */
        .badge-primary-soft {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-info-soft {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: var(--light-color);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
        }

        .date-inputs {
            min-width: 300px;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 14px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }

        .stats-icon {
            font-size: 32px;
            line-height: 1;
        }

        .stats-content h3 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .stats-content p {
            color: #6b7280;
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        .stats-primary .stats-content h3 { color: var(--primary-color); }
        .stats-success .stats-content h3 { color: var(--success-color); }
        .stats-warning .stats-content h3 { color: var(--warning-color); }
        .stats-info .stats-content h3 { color: var(--info-color); }

        /* Custom Table */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .custom-table thead th {
            background: #f8fafc;
            color: var(--dark-color);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
            border-bottom: 2px solid var(--border-color);
        }

        .custom-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            vertical-align: middle;
        }

        .custom-table tbody tr:hover {
            background: #f9fafb;
        }

        .custom-table tfoot td {
            padding: 16px;
            background: #f8fafc;
            border-top: 2px solid var(--border-color);
            font-weight: 600;
        }

        /* Custom Badges */
        .badge-custom {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .badge-diagnosis { background: #dbeafe; color: #1d4ed8; }
        .badge-swot { background: #d1fae5; color: #065f46; }
        .badge-content_plan { background: #fef3c7; color: #92400e; }
        .badge-shooting_script { background: #cffafe; color: #0e7490; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-state h4 {
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Pricing Cards */
        .pricing-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 2px solid var(--border-color);
            text-align: center;
            transition: all 0.2s ease;
        }

        .pricing-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .pricing-input:hover { border-color: var(--info-color); }
        .pricing-output:hover { border-color: var(--warning-color); }

        .pricing-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .pricing-icon {
            font-size: 20px;
        }

        .pricing-header h6 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        .pricing-amount {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .pricing-unit {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .pricing-desc {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        /* Tips Section */
        .tips-section {
            background: #f0fdf4;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #bbf7d0;
        }

        .tip-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .tip-icon {
            color: var(--success-color);
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-section {
                padding: 16px;
            }

            .date-inputs {
                min-width: auto;
                width: 100%;
                flex-direction: column;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons .btn {
                flex: 1;
            }

            .stats-card {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .stats-content h3 {
                font-size: 20px;
            }

            .custom-table thead th,
            .custom-table tbody td {
                padding: 12px 8px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 16px;
                padding-right: 16px;
            }

            .stats-card {
                padding: 16px;
            }

            .stats-content h3 {
                font-size: 18px;
            }

            .pricing-card {
                padding: 20px;
            }

            .pricing-amount {
                font-size: 24px;
            }
        }
    </style>
@endsection
