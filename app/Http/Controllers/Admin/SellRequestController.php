<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellRequestController extends Controller
{
    public function index()
    {
        $requests = SellRequest::latest()->paginate(20);

        return view('admin.sell.index', compact('requests'));
    }

    public function toggle(SellRequest $sell)
    {
        $sell->update(['is_handled' => ! $sell->is_handled]);

        return back();
    }

    public function destroy(SellRequest $sell)
    {
        if ($sell->photo) {
            Storage::disk('public')->delete($sell->photo);
        }
        $sell->delete();

        return back()->with('ok', 'Talep silindi.');
    }
}
