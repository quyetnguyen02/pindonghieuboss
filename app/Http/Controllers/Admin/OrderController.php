<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $period = $request->period;
        $keyword = trim($request->keyword ?? '');
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $orders = Order::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('customer_name', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%")
                        ->orWhere('id', $keyword);
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($period, function ($query) use ($period) {
                $today = now();
                if ($period === 'week') {
                    $query->whereBetween('created_at', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $query->whereBetween('created_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()]);
                }
            })
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('Admin.order.index', compact(
            'orders',
            'status',
            'period',
            'keyword',
            'fromDate',
            'toDate'
        ));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        $products = Product::select(['id', 'name', 'original_price', 'sale_price'])->orderBy('name')->get();

        return view('Admin.order.show', compact('order', 'products'));
    }

    public function addItem(Request $request, Order $order)
    {
        if (! $order->can_edit_items) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Không thể thêm sản phẩm cho đơn hàng này.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products_p,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $price = $product->sale_price ?: $product->original_price;

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'qty' => $validated['qty'],
            'price' => $price,
        ]);

        $this->recalculateOrderTotal($order);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Đã thêm sản phẩm vào đơn hàng.');
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        if (! $order->can_edit_items) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Không thể xóa sản phẩm cho đơn hàng này.');
        }

        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $item->delete();
        $this->recalculateOrderTotal($order);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Đã xóa sản phẩm khỏi đơn hàng.');
    }

    protected function recalculateOrderTotal(Order $order): void
    {
        $total = $order->items()->sum(DB::raw('price * qty'));
        $order->update(['total_price' => $total]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:0,1,2,3,4,5,6,7',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
