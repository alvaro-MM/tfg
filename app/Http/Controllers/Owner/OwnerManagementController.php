<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTableRequest;
use App\Models\User;
use App\Models\Table;
use Illuminate\Http\Request;

class OwnerManagementController extends Controller
{
    public function makeStaff(User $user)
    {
        $user->assignRole('staff');

        return back()->with('success', 'Usuario convertido en staff.');
    }

    public function removeStaff(User $user)
    {
        $user->removeRole('staff');

        return back()->with('success', 'Staff dado de baja.');
    }

    public function storeTable(StoreTableRequest $request)
    {
        Table::create($request->validated());

        return back()->with('success', 'Mesa creada.');
    }

    public function destroyTable(Table $table)
    {
        $table->delete();

        return back()->with('success', 'Mesa eliminada.');
    }
}
