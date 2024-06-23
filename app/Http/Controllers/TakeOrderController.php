<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;
class TakeOrderController extends Controller
{
    public function index()
    {
        $foods = Food::where('number_of_items', '>', 0)->get(); // Fetch only food items with number_of_items > 0
        return view('pages.takeorders', compact('foods')); // Pass data to the view
    }

    public function store(Request $request)
{
    $request->validate([
        'total_quantity' => 'required|integer|min:0',
        'total_price' => 'required|numeric|min:0',
        'transaction_id' => 'nullable|string|max:255|unique:orders,transaction_id',
        'items' => 'required|string', // Validate as a JSON string
    ]);

    $user = Auth::user();

    $order = new Order();
    $order->cashier_id = $user->id;
    $order->total_quantity = $request->total_quantity;
    $order->total_price = $request->total_price;
    $order->transaction_id = $request->transaction_id;

    $order->save();

    $items = json_decode($request->items, true); // Decode JSON string to array
    foreach ($items as $item) {
        $orderItem = new OrderItem([
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total_price' => $item['quantity'] * $item['price']
        ]);
        $order->items()->save($orderItem);
    }

    return redirect()->back()->with('success', 'Order has been successfully saved.');
}

    
    public function decreaseQuantity(Food $food)
    {
        request()->validate([
            'number_of_items' => 'required|integer|min:1',
        ]);
    
        $food->update([
            'number_of_items' => max(0, $food->number_of_items - request('number_of_items'))
        ]);
    
        return response()->json(['message' => 'Quantity decreased successfully']);
    }
    

public function generateOrderDetailsPdf(Request $request)
{
    $htmlContent = $request->input('htmlContent');

    $pdf = SnappyPdf::loadHTML($htmlContent);
    
    return $pdf->download('order_details.pdf');
}



}
