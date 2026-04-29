<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Главная страница отчетов
     */
    public function index()
    {
        // Общая статистика
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Статистика по статусам заказов
        $statusStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Топ товаров
        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Статистика по дням (последние 30 дней)
        $dailyStats = Order::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Общее количество пользователей
        $totalUsers = User::count();

        // Количество новых пользователей за месяц
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.reports.index', compact(
            'totalOrders', 'totalRevenue', 'averageOrderValue', 'statusStats',
            'topProducts', 'dailyStats', 'totalUsers', 'newUsersThisMonth'
        ));
    }

    /**
     * Отчет по продажам
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $summary = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->where('status', 'completed')->sum('total_amount'),
            'avg_order' => 0,
        ];

        if ($summary['total_orders'] > 0) {
            $summary['avg_order'] = $summary['total_revenue'] / $summary['total_orders'];
        }

        return view('admin.reports.sales', compact('orders', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Отчет по товарам
     */
    public function products()
    {
        $products = Product::withCount('orderItems')
            ->withSum('orderItems', 'quantity')
            ->withSum('orderItems', 'total')
            ->orderBy('order_items_sum_quantity', 'desc')
            ->paginate(20);

        return view('admin.reports.products', compact('products'));
    }

    /**
     * Экспорт отчета в CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';

        $handle = fopen('php://temp', 'w+');

        // Заголовки CSV
        fputcsv($handle, [
            '№ заказа', 'Дата', 'Покупатель', 'Телефон', 'Сумма', 'Статус', 'Статус оплаты'
        ]);

        // Данные
        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->created_at->format('d.m.Y H:i'),
                $order->customer_name,
                $order->customer_phone,
                $order->total_amount,
                $order->status,
                $order->payment_status,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
