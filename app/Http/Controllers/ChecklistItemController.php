<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckList;
use App\Models\CheckListItem;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ChecklistItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['test']),
        ];
    }

    public function detailChecklist($id)
    {
        // cek user_id
        $checklistItem = CheckListItem::where('check_list_id', $id);

        // jika kosong
        if (!$checklistItem->first()) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            $checklist = CheckList::where('id', $id)->select('user_id')->get();
            $getUserId = $checklist[0]->user_id;
    
            // jika data dimiliki user
            if ($getUserId == Auth::user()->id){
                $getItem = CheckListItem::where('check_list_id', $id)->get();
                return response()->json([
                    $getItem
                ]);
            } else {
                return response()->json([
                    'message' => 'checklist tidak dapat diakses'
                ]);
            }
        }
    }

    public function createChecklistItem($id)
    {
        // cek user_id
        $checklist = CheckList::where('id', $id)->first();
        
        // jika kosong
        if (!$checklist) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            $getUserId = $checklist->user_id;
    
            // jika data dimiliki user
            if ($getUserId == Auth::user()->id){
                // validasi
                $validator = Validator::make(request()->all(), [
                    'itemName' => 'required'
                ]);
        
                if($validator->fails()) {
                    return response()->json($validator->messages());
                }
    
                $createItem = CheckListItem::create([
                    'itemName'      => request('itemName'),
                    'check_list_id' => $id,
                    'status'        => 0
                ]);
    
                return response()->json([
                    $createItem
                ]);
            } else {
                return response()->json([
                    'message' => 'checklist tidak dapat diakses'
                ]);
            }
        }
    }

    public function getChecklistItem($id, $itemId)
    {
        // cek user_id
        $checklistItem = CheckListItem::where('id', $itemId)->first();

        // jika kosong
        if (!$checklistItem) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            $getIdItem = $checklistItem->check_list_id;
            $checklist = CheckList::where('id', $getIdItem)->select('user_id')->get();
    
            // jika data dimiliki user
            if ($checklist[0]->user_id == Auth::user()->id){
                // get
                $getItem = CheckListItem::where('id', $itemId)->get();
    
                return response()->json([
                    $getItem
                ]);
            } else {
                return response()->json([
                    'message' => 'item tidak dapat diakses'
                ]);
            }
        }
    }

    public function updateStatusChecklistItem($id, $itemId)
    {
        // cek user_id
        $checklistItem = CheckListItem::where('id', $itemId)->first();

        // jika kosong
        if (!$checklistItem) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            $getIdItem = $checklistItem->check_list_id;
            $checklist = CheckList::where('id', $getIdItem)->select('user_id')->get();
    
            // jika data dimiliki user
            if ($checklist[0]->user_id == Auth::user()->id){
                // update
                $getItem = CheckListItem::find($itemId)->update([
                    'status' => 1
                ]);
    
                return response()->json([
                    'status'    => $getIdItem,
                    'keterangan'=> 'Terselesaikan'
                ]);
            } else {
                return response()->json([
                    'message' => 'item tidak dapat diakses'
                ]);
            }
        }
    }

    public function deleteChecklistItem($id, $itemId)
    {
        // cek user_id
        $checklistItem = CheckListItem::where('id', $itemId)->first();

        // jika kosong
        if (!$checklistItem) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            // get user_id
            $getIdItem = $checklistItem->check_list_id;
            $checklist = CheckList::where('id', $getIdItem)->select('user_id')->get();

            // jika data dimiliki user
            if ($checklist[0]->user_id == Auth::user()->id){
                // delete
                CheckListItem::destroy($itemId);

                return response()->json([
                    'message' => 'item berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'message' => 'item tidak dapat diakses'
                ]);
            }
        }
    }

    public function renameChecklistItem(Request $request, $id, $itemId)
    {
        //cek user_id
        $checklistItem = CheckListItem::where('id', $itemId)->first();

        // jika kosong
        if (!$checklistItem) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        } else {
            $getIdItem = $checklistItem->check_list_id;
            $checklist = CheckList::where('id', $getIdItem)->select('user_id')->get();
    
            // jika data dimiliki user
            if ($checklist[0]->user_id == Auth::user()->id){
                // validasi
                $validator = Validator::make(request()->all(), [
                    'itemName' => 'required'
                ]);
        
                if($validator->fails()) {
                    return response()->json($validator->messages());
                }
    
                // update
                $getItem = CheckListItem::find($itemId)->update([
                    'itemName' => request('itemName'),
                ]);
    
                return response()->json([
                    'message'   => 'berhasil mengubah item checklist'
                ]);
            } else {
                return response()->json([
                    'message' => 'item tidak dapat diakses'
                ]);
            }
        }
    }
}
