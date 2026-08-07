<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $keyword = $request->input('keyword');
        $period = $request->input('period');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $consultations = Consultation::query()
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('customer_name', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%")
                      ->orWhere('product', 'like', "%{$keyword}%");
                });
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($from_date)),
                    date('Y-m-d 23:59:59', strtotime($to_date)),
                ]);
            })
            ->when($period && !($from_date && $to_date), function ($query) use ($period) {
                if ($period === 'today') {
                    $query->whereDate('created_at', now()->toDateString());
                } elseif (is_numeric($period)) {
                    $query->where('created_at', '>=', now()->subDays((int) $period));
                }
            })
            ->latest()
            ->paginate(20)
            ->appends($request->except('page'));

        $viewData = compact('consultations', 'status', 'keyword', 'period', 'from_date', 'to_date');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Consultations retrieved.',
            ]);
        }

        return view('Admin.consultation.index', $viewData);
    }

    public function done($id)
    {

        Consultation::where('id', $id)->update([
            'status' => 1,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Đã cập nhật.');
    }

}

