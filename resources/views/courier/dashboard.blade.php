@extends('layouts.courier')

@section('title', 'Мои заказы')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">🚚 Мои заказы</h1>
        <form method="POST" action="{{ route('courier.logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-sm">Выйти</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Статистика -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h4 class="display-6">{{ $newOrders }}</h4>
                    <p class="mb-0">Новых заказов</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h4 class="display-6">{{ $onWayOrders }}</h4>
                    <p class="mb-0">В пути</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h4 class="display-6">{{ $deliveredToday }}</h4>
                    <p class="mb-0">Доставлено сегодня</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Список заказов -->
    <div class="row g-3">
        @forelse($orders as $order)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold">#{{ $order->order_number }}</span>
                    <span class="badge
                        @if($order->delivery_status == 'assigned_to_courier') bg-warning text-dark
                        @elseif($order->delivery_status == 'courier_took') bg-info text-white
                        @elseif($order->delivery_status == 'on_the_way') bg-primary text-white
                        @elseif($order->delivery_status == 'delivered') bg-success text-white
                        @else bg-secondary text-white @endif">
                        {{ str_replace('_', ' ', ucfirst($order->delivery_status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Клиент:</strong> {{ $order->customer_name }}<br>
                        <strong>Телефон:</strong> {{ $order->customer_phone }}<br>
                        <strong>Адрес:</strong> {{ $order->delivery_address }}<br>
                        <strong>Сумма:</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} ₽<br>
                        <strong>Товаров:</strong> {{ $order->items()->count() }} шт.
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if($order->delivery_status == 'assigned_to_courier')
                            <form method="POST" action="{{ route('courier.took', $order) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">✅ Взял заказ</button>
                            </form>
                        @endif

                        @if($order->delivery_status == 'courier_took')
                            <form method="POST" action="{{ route('courier.on_way', $order) }}">
                                @csrf
                                <button class="btn btn-primary btn-sm">🚗 В пути</button>
                            </form>
                        @endif

                        @if($order->delivery_status == 'on_the_way')
                            <form method="POST" action="{{ route('courier.delivered', $order) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">✅ Доставлен</button>
                            </form>
                        @endif

                        @if($order->delivery_status == 'delivered')
                            <span class="badge bg-success p-2">✅ Доставлен</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-muted small">
                    {{ $order->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <h5>📭 У вас пока нет заказов</h5>
                <p class="mb-0">Ожидайте назначения новых заказов</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
