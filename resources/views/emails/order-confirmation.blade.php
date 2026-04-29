<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Подтверждение заказа #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .order-details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #e5e7eb; }
        .total { font-size: 18px; font-weight: bold; color: #dc2626; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍔 GooDFooD</h1>
            <p>Подтверждение заказа #{{ $order->order_number }}</p>
        </div>

        <div class="content">
            <p>Здравствуйте, <strong>{{ $order->customer_name }}</strong>!</p>
            <p>Спасибо за ваш заказ. Мы подтверждаем его получение и скоро начнем обработку.</p>

            <div class="order-details">
                <h3>📋 Детали заказа</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }} шт.</td>
                            <td>{{ number_format($item->product_price, 0, ',', ' ') }} ₽</td>
                            <td>{{ number_format($item->total, 0, ',', ' ') }} ₽</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Итого:</td>
                            <td class="total">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="order-details">
                <h3>🚚 Данные доставки</h3>
                <p><strong>Получатель:</strong> {{ $order->customer_name }}</p>
                <p><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Адрес:</strong> {{ $order->delivery_address }}</p>
                @if($order->delivery_notes)
                    <p><strong>Комментарий:</strong> {{ $order->delivery_notes }}</p>
                @endif
            </div>

            <div class="order-details">
                <h3>📊 Статус заказа</h3>
                <p><strong>Текущий статус:</strong>
                    @switch($order->status)
                        @case('pending') ⏳ Ожидает обработки @break
                        @case('processing') 🔄 В обработке @break
                        @case('completed') ✅ Выполнен @break
                        @case('cancelled') ❌ Отменен @break
                        @default {{ $order->status }}
                    @endswitch
                </p>
                <p><strong>Статус оплаты:</strong>
                    {{ $order->payment_status === 'paid' ? '✅ Оплачен' : '⏳ Ожидает оплаты' }}
                </p>
            </div>

            <p>Мы свяжемся с вами для уточнения деталей доставки.</p>

            <hr>
            <p style="font-size: 12px; color: #6b7280; text-align: center;">
                Спасибо, что выбрали GooDFooD!<br>
                Вопросы? Пишите: info@goodfood.ru
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} GooDFooD - Вкусная еда с доставкой</p>
        </div>
    </div>
</body>
</html>
