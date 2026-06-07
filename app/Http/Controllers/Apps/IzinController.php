<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use Spatie\Permission\Models\Permission as Izin;

class IzinController extends Controller
{
  private $toko;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  public function __invoke(Request $request)
  {
    $izins = Izin::when(request()->q, function ($izins) {
      $izins = $izins->where('name', 'like', '%' . request()->q . '%');
    })->latest()->paginate(5);

    return inertia('Apps/Izins/Index', [
      'izins' => $izins,
      'toko'  => $this->toko
    ]);
  }
}
