<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $model = Item::all();
            return Datatables()
                ->of($model)
                ->addIndexColumn()
                ->addColumn('unit_price', function ($model) {
                    return "Rp." . $model->unit_price;
                })
                ->addColumn('action', function ($model) {
                    $btn = '<button type="button" id="edit" class="btn btn-warning btn-sm" value="' . $model->id . '"><i class="fas fa-edit"></i></button>';
                    $btn = $btn . '<button type="button" id="delete" class="btn btn-danger btn-sm" value="' . $model->id . '"><i class="fas fa-trash-alt"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.item.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => ['required', Rule::unique('items', 'item_name')->ignore($request->id, 'id')],
            'unit_price' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $query =  Item::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'item_name' => $request->item_name,
                    'unit_price' => $request->unit_price,
                ]
            );
            if ($query) {
                return response()->json(['status' => 1, 'msg' => 'Data Berhasil Disimpan']);
            }
        }
    }

    public function edit($id)
    {
        $item = Item::find($id);
        return response()->json($item);
    }

    public function destroy($id)
    {
        Item::find($id)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }
}
