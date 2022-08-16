<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\all;

class PurchaseController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $model = Purchase::all();
            return Datatables()
                ->of($model)
                ->addIndexColumn()
                ->addColumn('date', function ($model) {
                    return Carbon::parse($model->created_at)->translatedFormat('l, d F Y H:i');
                })
                ->addColumn('total_item', function ($model) {
                    $details = PurchaseDetail::where('purchase_id', $model->id)->get();
                    $total_item = 0;
                    foreach ($details as $detail) {
                        $total_item += $detail->amount;
                    }
                    return $total_item;
                })
                ->addColumn('total_price', function ($model) {
                    return "Rp." . $model->total_price;
                })
                ->addColumn('action', function ($model) {
                    $btn = '<button type="button" id="detail" class="btn btn-info btn-sm" value="' . $model->id . '"><i class="fas fa-eye"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.purchase.index');
    }

    public function detail(Request $request)
    {
        $details = PurchaseDetail::with('item')->where('purchase_id', $request->id)->get();
        return response()->json($details);
    }

    public function create()
    {
        $items = Item::orderBy('item_name', 'ASC')->get();
        $datas = \Cart::session('purchase')->getContent();
        $total = \Cart::session('purchase')->getSubTotal();
        return view('pages.purchase.create', compact('items', 'datas', 'total'));
    }

    public function addCart(Request $request)
    {
        $validator = Validator::make($request->only('item', 'price', 'amount'), [
            'item' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $item = Item::find($request->item);
            \Cart::session('purchase')->add([
                'id' => $item->id,
                'name' => $item->item_name,
                'price' => $item->unit_price,
                'quantity' => $request->amount,
            ]);
            $datas = \Cart::session('purchase')->getContent();
            $total = \Cart::session('purchase')->getSubTotal();
            return response()->json(['status' => 1, 'msg' => 'Barang Berhasil Ditambahkan ke List', 'datas' => $datas, 'total' => $total]);
        }
    }

    public function removeCart(Request $request)
    {
        \Cart::session('purchase')->remove($request->id);
        $datas = \Cart::session('purchase')->getContent();
        $total = \Cart::session('purchase')->getSubTotal();
        return response()->json(['status' => 1, 'msg' => 'Barang Berhasil Dihapus dari List', 'datas' => $datas, 'total' => $total]);
    }

    public function store(Request $request)
    {
        if (\Cart::session('purchase')->isEmpty()) {
            return back()->with('error', 'List Barang Tidak Boleh Kosong')->withInput();
        } else {
            $purchase = Purchase::create([
                'total_price' => \Cart::session('purchase')->getSubTotal()
            ]);

            foreach (\Cart::session('purchase')->getContent() as $data) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $data->id,
                    'amount' => $data->quantity,
                    'unit_price' => $data->price,
                ]);
            }
        }
        \Cart::session('purchase')->clear();
        return redirect()->route('pembelian.index')->with('success', 'Transaksi Pembelian Berhasil Disimpan');
    }
}
