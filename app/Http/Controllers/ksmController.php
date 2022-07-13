<?php

namespace App\Http\Controllers;

use App\Model\Ksm;
use App\User;
use Validator;
use App\Helper\Alert;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ksmController extends Controller
{
    public function index()
    {
        return Datatables::of(Ksm::all())
            ->setRowId(function (Ksm $ksm) {
                return $ksm->id;
            })
            ->addColumn('aksi', 'admin.aksm.action-button')
            ->rawColumns(['aksi'])
            ->make(true);

        // $ksms = Ksm::all();
        // return view('admin.aksm.index', compact('ksms'));
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:ksms,id',
        ]);
        $response = ['ok' => true];
        if ($validator->fails()) {
            $response['ok'] = false;
            $response['msg'] = 'Id tidak valid';
        } else {
            Ksm::find($request->input('id'))->delete();
            $response['msg'] = 'berhasil menghapus data';
        }
        return response()->json($response, 200);
    }
    public function store(Request $request)
    {
        # code...
        $res = ['stored' => true];
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3',
            'nik' => 'required|min:3',
            'nama_ksm' => 'required',
            'capital' => 'required',
            'condition' => 'required',
            'capacity' => 'required',
            'character' => 'required',
            'collateral' => 'required',
        ]);
        if ($validator->fails()) {
            $res['msg'] = Alert::errorList($validator->errors());
            $res['stored'] = false;
        } else {
            $ksm = new Ksm();
            $ksm->nama = $request->input('nama');
            $ksm->nik = $request->input('nik');
            $ksm->nama_ksm = $request->input('nama_ksm');
            $ksm->capital = $request->input('capital');
            $ksm->character = $request->input('character');
            $ksm->condition = $request->input('condition');
            $ksm->capacity = $request->input('capacity');
            $ksm->collateral = $request->input('collateral');
            $ksm->save();
            $res['msg'] = Alert::success('Berhasil Menambahkan Data');
        }

        return response()->json($res, 200);
    }
    public function update(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|max:60',
            'role' => 'required',
        ]);

        $response = ['stored' => true];

        if ($validator->fails()) {
            $response['stored'] = false;
            $response['msg'] = Alert::errorList($validator->errors());
        } else {
            $user = User::find($request->input('id'));
            if ($user) {
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->save();
                $user->role()->sync($request->input('role'));
                $response['msg'] = Alert::success('Berhasil Memperbarui Data');
            } else {
                $response['stored'] = false;
                $response['msg'] = Alert::errorList('Data tidak ditemukan');
            }
        }
        return response()->json($response, 200);
    }
}
