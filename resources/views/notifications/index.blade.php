@extends($layout)

@section('title', 'Notifikasi')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid px-3">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold"><i class="fas fa-bell mr-2"></i> Daftar Notifikasi</h3>
                        <p class="mb-0 text-sm text-muted">{{ auth()->user()->unreadNotifications->count() }} notifikasi belum dibaca.</p>
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check-double mr-1"></i> Tandai Semua Sudah Dibaca
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(auth()->user()->notifications->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-3"></i>
                            <p>Tidak ada notifikasi.</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach(auth()->user()->notifications as $notification)
                                @php
                                    $actionUrl = $notification->data['action_url'] ?? null;
                                    $isClickable = !empty($actionUrl) && !str_contains($actionUrl, 'localhost');
                                @endphp
                                @if($isClickable)
                                    <a href="{{ $actionUrl }}" class="list-group-item list-group-item-action {{ $notification->read_at ? 'bg-white' : 'bg-light border-left-primary' }}">
                                @else
                                    <div class="list-group-item {{ $notification->read_at ? 'bg-white' : 'bg-light border-left-primary' }}">
                                @endif
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div>
                                            <h5 class="mb-1">{{ \Illuminate\Support\Str::limit($notification->data['pesan'] ?? 'Notifikasi baru', 120) }}</h5>
                                            @if(!empty($notification->data['title']))
                                                <p class="mb-1 text-truncate text-muted">{{ $notification->data['title'] }}</p>
                                            @endif
                                            @if(!empty($notification->data['alasan']))
                                                <p class="mb-1 text-danger">Alasan: {{ \Illuminate\Support\Str::limit($notification->data['alasan'], 120) }}</p>
                                            @endif
                                        </div>
                                            <span class="badge badge-{{ $notification->read_at ? 'secondary' : 'warning' }}">{{ $notification->read_at ? 'Dibaca' : 'Baru' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">{{ $notification->created_at->format('d M Y H:i') }}</small>
                                            <small class="text-muted">Status: {{ $notification->read_at ? 'Sudah dibaca' : 'Belum dibaca' }}</small>
                                        </div>
                                @if($isClickable)
                                    </a>
                                @else
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
