<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View { $units=Unit::query()->orderBy('name')->paginate(10); return view('admin.units.index',compact('units')); }
    public function create(): View { return view('admin.units.create'); }
    public function store(Request $request): RedirectResponse { $data=$request->validate(['code'=>['required','string','max:30','unique:units,code'],'name'=>['required','string','max:80','unique:units,name'],'description'=>['nullable','string','max:255'],'is_active'=>['nullable','boolean']]); $data['is_active']=$request->boolean('is_active'); Unit::create($data); return to_route('admin.units.index')->with('success','Satuan berhasil ditambahkan.'); }
    public function edit(Unit $unit): View { return view('admin.units.edit',compact('unit')); }
    public function update(Request $request, Unit $unit): RedirectResponse { $data=$request->validate(['code'=>['required','string','max:30','unique:units,code,'.$unit->id],'name'=>['required','string','max:80','unique:units,name,'.$unit->id],'description'=>['nullable','string','max:255'],'is_active'=>['nullable','boolean']]); $data['is_active']=$request->boolean('is_active'); $unit->update($data); return to_route('admin.units.index')->with('success','Satuan berhasil diperbarui.'); }
    public function destroy(Unit $unit): RedirectResponse { $unit->delete(); return to_route('admin.units.index')->with('success','Satuan berhasil dihapus.'); }
}