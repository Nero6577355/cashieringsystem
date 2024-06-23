@php
    $user = auth()->user();
    $role = $user->roles ?? '';
@endphp

@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Transaction History',
    'activePage' => 'transactions',
    'activeNav' => '',
])

@section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
    <div class="card">
    <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Transaction History</h5>
                        <button type="button" class="btn btn-secondary" onclick="printTransactionHistory()">Print Transaction History<i class="fa-solid fa-print"></i></button>
                    </div>
                </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <th>Cashier</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                        <td>{{ $order->cashier->name }}</td>
                            <td>{{ $order->transaction_id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $order->total_quantity }}</td>
                            <td>{{ $order->total_price }}</td>
                            <td>{{ $order->status }}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
          {{ $orders->links('vendor.pagination.custom') }}
      </div>
        </div>
    </div>
</div>

</div>
      </div>
    </div>
  </div>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function printTransactionHistory() {
        // Show confirmation dialog using SweetAlert
        Swal.fire({
            title: 'Do you want to print the transaction history?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, print it!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Fetch transaction history details
                var orders = @json($orders);

                // Check if orders is an array
                if (!Array.isArray(orders.data)) {
                    Swal.fire('Error', 'Invalid order data', 'error');
                    return;
                }

                // Construct the HTML content for the print window
                var orderDetailsTable = '';
                orders.data.forEach(order => {
                    // Retrieve the cashier's name
                    var cashierName = order.cashier ? order.cashier.name : 'N/A';
                    orderDetailsTable += `
                        <tr>
                        
                            <td>${order.transaction_id}</td>
                            <td>${cashierName}</td>
                            <td>${order.total_quantity}</td>
                            <td>${order.total_price}</td>
                            <td>${order.status}</td>
                            <td>${order.created_at}</td>
                        </tr>
                    `;
                });

                var orderDetailsContent = `
                <html>
                    <head>
                        <title>Print Transaction History</title>
                        <style>
                            /* Define your CSS styles here */
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 1px solid #dddddd;
                                text-align: left;
                                padding: 8px;
                            }
                            th {
                                background-color: #f2f2f2;
                            }
                        </style>
                    </head>
                    <body>
                        <div id="header">
                            <h1 class="text-3xl font-bold mb-4">Transaction History</h1>
                        </div>
                        <div id="orderDetailsContent">
                            <table>
                                <thead>
                                    <tr>
                                    <th>Transaction ID</th>
                                    <th>Cashier</th>
                                        <th>Total Quantity</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${orderDetailsTable}
                                </tbody>
                            </table>
                        </div>
                    </body>
                </html>
            `;

                var newWindow = window.open('', '', 'height=600,width=800');

                newWindow.document.write('<html><head><title>Print Transaction History</title>');
                newWindow.document.write('<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/now-ui-dashboard.css') }}" />');
                newWindow.document.write('</head><body>');
                newWindow.document.write(orderDetailsContent);
                newWindow.document.write('</body></html>');

                newWindow.document.close();

                newWindow.print();

                $.ajax({
                    url: "{{ route('generate.order.details.pdf') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        htmlContent: orderDetailsContent
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'order_details.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                });
            }
        });
    }
</script>
