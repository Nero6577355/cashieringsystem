<?php

namespace App\Http\Controllers;

use Gate;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
      
    // public function edit()
    // {
    //     // // Retrieve the logged-in user (assuming Laravel framework)
    //     // $user = Auth::user();
    
    //     // // Fetch paginated orders associated with the cashier (optimized)
    //     // $orders = Order::where('cashier_id', $user->id)->paginate(6);
    
    //     // // Return the view with orders data
    //     // return view('profile.edit',['orders' => $orders]);
    // }

    public function show()
    {
          $user = Auth::user();
    
          $orders = Order::where('cashier_id', $user->id)
                    ->where('status', '!=', 'Cancelled')
                    ->paginate(6);
      
          return view('profile.edit',['orders' => $orders]);
    }
    public function pay(Order $order)
{
    try {
        // Update order status
        $order->update(['status' => 'Paid']);
        
        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Order has been marked paid.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to pay the order.'], 500);
    }
}


    public function view(Order $order)
{
    // Fetch the order items associated with the given order
    $orderItems = OrderItem::where('order_id', $order->id)->get();

    // Pass the order and its associated items to the view
    return view('profile.edit', [
        'transaction_id' => $order->transaction_id,
        'orderItems' => $orderItems,
    ]);
}
public function getOrderItems(Order $order)
{
    $orderItems = OrderItem::where('order_id', $order->id)->get();

    return response()->json($orderItems);
}

    
public function cancelPayment(Order $order)
{
    try {
        // Update order status
        $order->update(['status' => 'Unpaid']);
        
        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Order has been marked unpaid.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error.'], 500);
    }
}
public function cancelOrder(Order $order)
{
    try {
        // Update order status
        $order->update(['status' => 'Cancelled']);
        
        // Return JSON response
        return response()->json(['success' => true, 'message' => 'Order has been cancelled.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to cancel the order.'], 500);
    }
}

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
{
    $user = auth()->user();

    // Update the individual name components
    $user->first_name = $request->input('first_name');
    $user->middle_name = $request->input('middle_name');
    $user->last_name = $request->input('last_name');

    // Combine them to update the full name
    $user->name = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");

    $user->save();

    return back()->withStatus(__('Profile successfully updated.'));
}

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
    public function generateOrderDetailsPdf(Request $request)
    {
        // Get HTML content from the request
        $htmlContent = $request->input('htmlContent');

        // Generate PDF
        $pdf = SnappyPdf::loadHTML($htmlContent);
        
        // Download the PDF
        return $pdf->download('order_details.pdf');
    }
    
}
