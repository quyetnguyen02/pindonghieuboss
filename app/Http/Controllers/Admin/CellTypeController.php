<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CellType;
use Illuminate\Http\Request;

class CellTypeController extends Controller
{
    public function index()
    {
        $cellTypes = CellType::orderBy('name')->get();

        return view('Admin.cell-type.index', compact('cellTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cell_types,name',
        ]);

        CellType::create($validated);

        return redirect()->route('admin.cell-types.index')
            ->with('success', 'Thêm thương hiệu cell thành công');
    }

    public function destroy(CellType $cellType)
    {
        $cellType->delete();

        return redirect()->route('admin.cell-types.index')
            ->with('success', 'Xóa thương hiệu cell thành công');
    }
}
