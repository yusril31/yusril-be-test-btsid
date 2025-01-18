<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckList;
use App\Models\CheckListItem;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['test']),
        ];
    }

    // public function test()
    // {
    //     //
    // }

    public function getChecklist()
    {
        // get
        $checklist = CheckList::with('checklistItems')->where('user_id', Auth::user()->id)->get();
        return response()->json([
            $checklist
        ]);
    }

    public function createChecklist()
    {
        // validasi
        $validator = Validator::make(request()->all(), [
            'name'      => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages());
        }

        $checklist = CheckList::create([
            'name'      => request('name'),
            'user_id'   => Auth::user()->id
        ]);

        if (!$checklist) {
            return response()->json([
                'message'   => 'error'
            ]);
        } else {
            return response()->json([
                $checklist
            ]);
        }
    }

    public function deleteChecklist($id)
    {
        // cek user_id
        $checklist = CheckList::where('id', $id)->select('user_id')->first();

        // jika data kosong
        if (!$checklist) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            // jika data dimiliki user
            if ($checklist->user_id == Auth::user()->id){
                CheckList::destroy($id);
                return response()->json([
                    'message' => 'data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'message' => 'gagal menghapus data'
                ]);
            }
        }
    }
}
