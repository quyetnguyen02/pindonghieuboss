<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $consultations = Consultation::query()
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('Admin.consultation.index', compact(
            'consultations',
            'status'
        ));

    }

    public function done($id)
    {

        Consultation::where('id', $id)->update([
            'status' => 1,
            'updated_at' => now()
        ]);

        return back()->with('success','Đã cập nhật.');
    }
}
