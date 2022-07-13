<?php

namespace App\Http\Controllers;

// use App\rtlh;
use Illuminate\Http\Request;
use App\Model\rtlh;
use App\User;
use Validator;
use App\Helper\Alert;
use Yajra\Datatables\Datatables;

class RtlhController extends Controller
{
    public function index()
    {
        return Datatables::of(Rtlh::all())
            ->setRowId(function (Rtlh $rtlh) {
                return $rtlh->id;
            })
            ->addColumn('aksi', 'admin.artlh.action-button')
            ->rawColumns(['aksi'])
            ->make(true);

        // $ksms = Ksm::all();
        // return view('admin.aksm.index', compact('ksms'));
    }

    public function edit($id)
    {
        $rtlh = Rtlh::find($id);
        return response()->json($rtlh);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:rtlhs,id',
        ]);
        $response = ['ok' => true];
        if ($validator->fails()) {
            $response['ok'] = false;
            $response['msg'] = 'Id tidak valid';
        } else {
            Rtlh::find($request->input('id'))->delete();
            $response['msg'] = 'berhasil menghapus data';
        }
        return response()->json($response, 200);
    }

    public function softDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:rtlhs,id',
        ]);
        $response = ['ok' => true];
        if ($validator->fails()) {
            $response['ok'] = false;
            $response['msg'] = 'Id tidak valid';
        } else {
            Rtlh::withTrashed()
                ->find($request->input('id'))
                ->get();
            $response['msg'] = 'berhasil menghapus data';
        }
        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        # code...
        $res = ['stored' => true];
        $validator = Validator::make($request->all(), [
            'no_kk' => 'required',
            'nama_lengkap' => 'required|min:3',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'pendidikan' => 'required',
            'dinding' => 'required',
            'atap' => 'required',
            'lantai' => 'required',
            'fmck' => 'required',
            'luas_lantai' => 'required',
            'penghasilan' => 'required',
        ]);
        if ($validator->fails()) {
            $res['msg'] = Alert::errorList($validator->errors());
            $res['stored'] = false;
        } else {
            $rtlh = new Rtlh();
            $rtlh->no_kk = $request->input('no_kk');
            $rtlh->nama_lengkap = $request->input('nama_lengkap');
            $rtlh->tanggal_lahir = $request->input('tanggal_lahir');
            $rtlh->jenis_kelamin = $request->input('jenis_kelamin');
            $rtlh->pendidikan = $request->input('pendidikan');
            $rtlh->dinding = $request->input('dinding');
            $rtlh->atap = $request->input('atap');
            $rtlh->lantai = $request->input('lantai');
            $rtlh->fmck = $request->input('fmck');
            $rtlh->luas_lantai = $request->input('luas_lantai');
            $rtlh->penghasilan = $request->input('penghasilan');
            $rtlh->save();
            $res['msg'] = Alert::success('Berhasil Menambahkan Data');
        }

        return response()->json($res, 200);
    }
    public function update(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'no_kk' => 'required',
            'nama_lengkap' => 'required|min:3',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'pendidikan' => 'required',
            'dinding' => 'required',
            'atap' => 'required',
            'lantai' => 'required',
            'fmck' => 'required',
            'luas_lantai' => 'required',
            'penghasilan' => 'required',
        ]);

        $response = ['stored' => true];

        if ($validator->fails()) {
            $response['stored'] = false;
            $response['msg'] = Alert::errorList($validator->errors());
        } else {
            $rtlh = Rtlh::find($request->input('id'));
            if ($rtlh) {
                $rtlh->no_kk = $request->input('no_kk');
                $rtlh->nama_lengkap = $request->input('nama_lengkap');
                $rtlh->tanggal_lahir = $request->input('tanggal_lahir');
                $rtlh->jenis_kelamin = $request->input('jenis_kelamin');
                $rtlh->pendidikan = $request->input('pendidikan');
                $rtlh->dinding = $request->input('dinding');
                $rtlh->atap = $request->input('atap');
                $rtlh->lantai = $request->input('lantai');
                $rtlh->fmck = $request->input('fmck');
                $rtlh->luas_lantai = $request->input('luas_lantai');
                $rtlh->penghasilan = $request->input('penghasilan');
                $rtlh->save();
                $response['msg'] = Alert::success('Berhasil Memperbarui Data');
            } else {
                $response['stored'] = false;
                $response['msg'] = Alert::errorList('Data tidak ditemukan');
            }
        }
        return response()->json($response, 200);
    }

    public function penerima(){
        $penerimas = Rtlh::onlyTrashed()->get();
        return view('admin.artlh.penerima', compact('penerimas'));
    }
}