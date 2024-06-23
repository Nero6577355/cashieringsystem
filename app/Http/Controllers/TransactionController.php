<?php

namespace App\Http\Controllers;

use Gate;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    public function show()
    {
        // Retrieve only orders with status "Paid" and paginate them
        $orders = Order::with('cashier')
                       ->paginate(6);
    
        return view('pages.transaction', ['orders' => $orders]);
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
