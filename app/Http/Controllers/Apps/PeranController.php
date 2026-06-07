<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as Peran;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User as Pengguna;
use Spatie\Permission\Models\Permission as Izin;

class PeranController extends Controller
{
  private $toko;

  public function __construct()
  {
    $this->toko = Toko::findOrFail(1);
  }

  public function index()
  {
    //get roles
    $perans = Peran::when(request()->q, function ($perans) {
      $perans = $perans->where('name', 'like', '%' . request()->q . '%');
    })->with('permissions')->latest()->paginate(5);

    //render with inertia
    return inertia('Apps/Perans/Index', [
      'perans'  => $perans,
      'toko'    => $this->toko
    ]);
  }

  public function create()
  {
    //get permission all
    $izins = Izin::all();

    //render with inertia
    return inertia('Apps/Perans/Create', [
      'izins' => $izins,
      'toko'  => $this->toko
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama_peran'    => 'required|unique:roles,name',
      'izins'         => 'required',
    ]);

    //create role
    $peran = Peran::create(['name' => $request->nama_peran]);

    //assign permissions to role
    $peran->givePermissionTo($request->izins);

    return redirect()->route('apps.perans.index');
  }

  public function edit($id)
  {
    //get role
    $peran = Peran::with('permissions')->findOrFail($id);

    //get permission all
    $izins = Izin::all();

    //render with inertia
    return inertia('Apps/Perans/Edit', [
      'peran'   => $peran,
      'izins'   => $izins,
      'toko'    => $this->toko
    ]);
  }

  public function update(Request $request, Peran $peran)
  {
    $request->validate([
      'nama_peran'    => 'required|unique:roles,name,' . $peran->id,
      'izins'         => 'required',
    ]);

    //update role
    $peran->update(['name' => $request->nama_peran]);

    //sync permissions
    $peran->syncPermissions($request->izins);

    return redirect()->route('apps.perans.index');
  }

  public function destroy($id)
  {
    $penggunas = Pengguna::all();
    $peran = Peran::findOrFail($id);

    $ada = 0;
    foreach ($penggunas as $pengguna) {
      foreach ($pengguna->getRoleNames() as $role) {
        if ($role == $peran->name) $ada += 1;
      }
    }

    if ($ada == 0) {
      $peran->delete();
      return response()->json(['success'   => true,]);
    } else {
      return response()->json(['success'   => false,]);
    }
  }
}
