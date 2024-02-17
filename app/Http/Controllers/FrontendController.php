<?php

namespace App\Http\Controllers;

use Dipantry\Rajaongkir\Constants\RajaongkirCourier;
use Exception;
use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CheckoutRequest;
use App\Models\TransactionItem;
use Dipantry\Rajaongkir\Models\ROCity;
use Dipantry\Rajaongkir\Models\ROProvince;
use Illuminate\Support\Facades\Http;
use Dipantry\Rajaongkir\Rajaongkir;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['galleries'])->latest()->get();

        return view ('pages.frontend.index', compact('products')); 
    }

    public function details(Request $request, $slug)
    {
        $product = Product::with(['galleries'])->where('slug', $slug)->firstOrFail();
        $recommendations = Product::with(['galleries'])->inRandomOrder()->limit(4)->get();

        return view('pages.frontend.details', compact('product','recommendations'));
    }

    
    public function cartAdd(Request $request, $id)
    {
        Cart::create([
            'users_id' => Auth::user()->id,
            'products_id' => $id
        ]);

        return redirect('cart');
    }

    public function cartDelete(Request $request, $id){
        $item = Cart::FindOrFail($id);

        $item->delete();

        return redirect('cart');
    }
    
    public function cart(Request $request)
    {
        $carts = Cart::with(['product.galleries'])->where('users_id', Auth::user()->id)->get();

        $total_berat = $carts->sum('product.berat');

        $provinces = ROProvince::all();

        return view('pages.frontend.cart', compact(['carts', 'total_berat', 'provinces']));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities($id)
    {
        $city = ROCity::where('province_id', $id)->pluck('name', 'id');
        return response()->json($city);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_ongkir(Request $request)
    {
        $response = Http::asForm()->withHeaders([
            'key' => 'e927368d797bbea60a9dc6ab6724f348', // Ganti dengan API key Anda
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => $request->input('city_origin'),
            'destination' => $request->input('city_destination'),
            'weight' => $request->input('weight'),
            'courier' => $request->input('courier'),
        ]);
        return $response->json();
    }

    public function checkout(Request $request){
        $data = $request->all();


        // Get Carts data
        $carts = Cart::with(['product'])->where('users_id', Auth::user()->id)->get();

        // Add to Transaction data
        $data['users_id'] = Auth::user()->id;
        $data['total_price'] = $carts->sum('product.price');
    
        // Create Transaction
        $transaction = Transaction::create($data);

        // Create Transaction item
        foreach($carts as $cart) {
            $items[] = TransactionItem::create([
                'transactions_id' => $transaction->id,
                'users_id' => $cart->users_id,
                'products_id' => $cart->products_id,
            ]);
        }
        
        // Delete cart after transaction
        Cart::where('users_id', Auth::user()->id)->delete();

        // Konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Setup midtrans variable
        $midtrans = array(
            'transaction_details' => array(
                'order_id' =>  'LX-' . $transaction->id,
                'gross_amount' => (int) $transaction->total_price,
            ),
            'customer_details' => array(
                'first_name'    => $transaction->name,
                'email'         => $transaction->email
            ),
            'enabled_payments' => array('gopay','bank_transfer'),
            'vtweb' => array()
        );

        try {
            // Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Redirect ke halaman midtrans
            return redirect($paymentUrl);
        }
        catch (Exception $e) {
            return $e;
        }

    }

    public function updateCount()
    {
        $count = Cart::count();
        return response()->json(['count' => $count]);
    }

    public function succes(Request $request)
    {
        return view ('pages.frontend.succes'); 
    }
}
