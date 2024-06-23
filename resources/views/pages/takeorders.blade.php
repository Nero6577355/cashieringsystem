@php
$user = auth()->user();
$role = $user->roles ?? '';
@endphp

@extends('layouts.app', [
    'namePage' => 'Take Orders',
    'class' => 'sidebar-mini',
    'activePage' => 'takeorders',
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
                        <h4 class="card-title">Take Order</h4>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#confirmOrderModal" onclick="confirmOrder()">Confirm Order</button>
                    </div>
                </div>
                @csrf
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($errors->has('name'))
                <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                @endif
                <div class="card-body">
                    @php $counter = 0; @endphp <!-- Counter to track items per row -->
                    @foreach($foods as $index => $food)
                    @if($counter % 4 == 0) <!-- Start a new row for every 4th item -->
                    <div class="row">
                    @endif
                    <!-- Inside the loop where you display each food item -->
<div class="col-md-3">
    <div class="" style="max-width: 200px;">
        <img src="{{ asset('storage/' . $food->photo) }}" alt="{{ $food->name }}" style="width: 200px; height: 160px; border-radius: 12px;">
        <div class="card-body">
            <h5 class="card-title">{{ $food->name }}</h5>
            <p class="card-text">
                <span class="font-weight-bold">Price:</span> Php {{ $food->price }}
                <span class="font-weight-bold">Number of Items:</span>
                @if($food->number_of_items > 0)
                    {{ $food->number_of_items }}
                @else
                    <span>Unavailable</span>
                @endif
            </p>
            <div class="d-flex justify-content-between align-items-center">
                <!-- Modified code block -->
                @if($food->number_of_items > 0)
    <button class="btn btn-primary" onclick="prevFood({{ $food->id }}, {{ $index }})" {{ $food->number_of_items == 0 ? 'disabled' : '' }}>
        <i class="fas fa-arrow-left"></i>
    </button>
    <input type="number" id="quantity{{ $index }}" value="0" min="0" max="{{ $food->number_of_items }}" class="form-control" style="width: 70px;" onchange="validateQuantity({{ $food->id }}, {{ $index }}, {{ $food->number_of_items }})">
    <button class="btn btn-primary" onclick="nextFood({{ $food->id }}, {{ $index }})" {{ $food->number_of_items == 0 ? 'disabled' : '' }}>
        <i class="fas fa-arrow-right"></i>
    </button>
@endif
<style>
    /* For Chrome, Safari, Edge, Opera */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* For Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>


                <!-- End of modified code block -->
            </div>
        </div>
    </div>
</div>
                    @php $counter++; @endphp <!-- Increment the counter -->
                    @if($counter % 4 == 0 || $loop->last) <!-- Close the row after every 4th item or if it's the last item -->
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmOrderModalLabel">Confirm Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            <p>Transaction ID: <span id="transactionId"></span></p>
            <div id="orderDetails">
            
                <!-- Rest of the order details content -->
            </div>

                
            </div>
            <div class="modal-footer" id="modalFooter">
                <!-- Content will be dynamically populated here -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var quantities = Array({{ count($foods) }}).fill(0);

    // Function to update the quantity and navigate to the previous food
    function prevFood(id, index) {
        if (quantities[index] > 0) {
            quantities[index]--;
            document.getElementById('quantity' + index).value = quantities[index];
            updateOrderSummary();
            decreaseFoodQuantity([{id: id, quantity: quantities[index]}]);
        }
    }

    // Function to update the quantity and navigate to the next food
    function nextFood(id, index) {
        var maxQuantity = parseInt(document.getElementById('quantity' + index).max);
        if (quantities[index] < maxQuantity) {
            quantities[index]++;
            document.getElementById('quantity' + index).value = quantities[index];
            updateOrderSummary();
            decreaseFoodQuantity([{id: id, quantity: quantities[index]}]); // Pass an array of objects containing id and quantity
        }
    }

    // Function to validate the quantity input
    function validateQuantity(id, index, maxItems) {
        var input = document.getElementById('quantity' + index);
        var value = parseInt(input.value);

        if (isNaN(value) || value < 0 || value > maxItems) {
            alert(`Invalid input. Please enter a number between 0 and ${maxItems}.`);
            input.value = quantities[index]; // Revert to the previous valid value
        } else {
            quantities[index] = value;
            updateOrderSummary();
            decreaseFoodQuantity([{id: id, quantity: quantities[index]}]);
        }
    }

    // Function to update the order summary label
    function updateOrderSummary() {
        var totalQuantity = quantities.reduce((a, b) => a + b, 0);
        var totalPrice = 0;

        @foreach($foods as $index => $food)
            totalPrice += {{ $food->price }} * quantities[{{ $index }}];
        @endforeach

    }

    // Function to handle confirming the order
    function confirmOrder() {
        var selectedItems = [];
        var totalPrice = 0;
        var totalQuantity = 0;
        var orderDetailsTable = '';

        @foreach($foods as $index => $food)
            var quantity{{ $index }} = quantities[{{ $index }}];
            if (quantity{{ $index }} > 0) {
                var price{{ $index }} = {{ $food->price }};
                var itemName{{ $index }} = "{{ $food->name }}";
                var itemTotalPrice{{ $index }} = price{{ $index }} * quantity{{ $index }};
                selectedItems.push({
                    name: itemName{{ $index }},
                    quantity: quantity{{ $index }},
                    price: price{{ $index }},
                    totalPrice: itemTotalPrice{{ $index }}
                });
                totalPrice += itemTotalPrice{{ $index }};
                totalQuantity += quantity{{ $index }};
                orderDetailsTable += `
                    <tr>
                        <td>${itemName{{ $index }}}</td>
                        <td>${quantity{{ $index }}}</td>
                        <td>Php ${price{{ $index }}}</td>
                        <td>Php ${itemTotalPrice{{ $index }}}</td>
                    </tr>
                `;
            }
        @endforeach

        var modalBody = document.getElementById('orderDetails');
        modalBody.innerHTML = `
            <div id="orderDetailsContent">
                <table>
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Price per Unit</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orderDetailsTable}
                        <tr>
                            <td colspan="3" class="text-right">Total Price:</td>
                            <td>Php ${totalPrice}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        modalBody.style.textAlign = 'center';
        modalBody.querySelector('table').style.width = '100%';
        modalBody.querySelectorAll('th, td').forEach(cell => {
            cell.style.padding = '8px';
            cell.style.border = '1px solid #ddd';
            cell.style.textAlign = 'center';
        });
        modalBody.querySelector('tbody:last-child tr:last-child').style.fontWeight = 'bold';
        modalBody.querySelector('tbody:last-child tr:last-child').style.textAlign = 'right';

        var transactionId = Date.now().toString();
        document.getElementById('transactionId').innerText = transactionId;
        var modalFooter = document.getElementById('modalFooter');
        modalFooter.innerHTML = `
            <button type="button" class="btn btn-secondary" onclick="printOrderDetails()">Print</button>
            <button type="button" class="btn btn-primary" onclick="submitOrder(${totalQuantity}, ${totalPrice}, '${transactionId}')">Confirm</button>
        `;
    }

    function printOrderDetails() {
        var transactionId = document.getElementById('transactionId').innerText;
        var bakeryName = "SugarBloom Bakery";
        var bakeryLocation = "Sogod, Southern Leyte";
        var logoSrc = "{{ asset('assets') }}/img/sugarbloom.png";

        var foodOrders = [];
        var totalPrice = 0; // Initialize total price
        @foreach($foods as $index => $food)
            var quantity{{ $index }} = quantities[{{ $index }}];
            if (quantity{{ $index }} > 0) {
                var price{{ $index }} = {{ $food->price }};
                var itemName{{ $index }} = "{{ $food->name }}";
                var itemTotalPrice{{ $index }} = price{{ $index }} * quantity{{ $index }};
                foodOrders.push({
                    name: itemName{{ $index }},
                    quantity: quantity{{ $index }},
                    price: price{{ $index }},
                    totalPrice: itemTotalPrice{{ $index }}
                });
                totalPrice += itemTotalPrice{{ $index }}; // Accumulate total price
            }
        @endforeach

        var orderDetailsTable = '';
        foodOrders.forEach(item => {
            orderDetailsTable += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>Php ${item.price}</td>
                    <td>Php ${item.totalPrice}</td>
                </tr>
            `;
        });

        var orderDetailsContent = `
            <div id="header">
                <h1 class="text-3xl font-bold mb-4">SugarBloom Bakery</h1>
                <p>Transaction ID: ${transactionId}</p>
                <p>${bakeryName}</p>
                <p>${bakeryLocation}</p>
            </div>
            <div id="orderDetailsContent">
                <table>
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Price per Unit</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orderDetailsTable}
                        <tr>
                            <td colspan="3" class="text-right">Total Price:</td>
                            <td>Php ${totalPrice}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>SugarBloom Bakery</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; }
            .logo { width: 100px; height: auto; margin-bottom: 10px; }
            #header { text-align: center; margin-bottom: 20px; }
            #header h1 { margin-top: 0; }
            #orderDetailsContent table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            #orderDetailsContent th, #orderDetailsContent td { padding: 10px; border: 1px solid #ccc; }
            #orderDetailsContent th { background-color: #f2f2f2; }
        `);
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(orderDetailsContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        if (confirm('Do you want to generate a PDF of the order details?')) {
            var pdf = new jsPDF();
            pdf.fromHTML(orderDetailsContent, 15, 15);
            pdf.save('order_details.pdf');
        } else {
            printWindow.print();
        }
    }

    function submitOrder(totalQuantity, totalPrice, transactionId) {
    Swal.fire({
        title: 'Confirm Order Submission',
        text: 'Are you sure you want to submit this order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            var selectedItems = [];
            @foreach($foods as $index => $food)
                if (quantities[{{ $index }}] > 0) {
                    selectedItems.push({
                        name: "{{ $food->name }}",
                        quantity: quantities[{{ $index }}],
                        price: {{ $food->price }}
                    });
                }
            @endforeach

            var formData = new FormData();
            formData.append('transaction_id', transactionId);
            formData.append('total_quantity', totalQuantity);
            formData.append('total_price', totalPrice);
            formData.append('items', JSON.stringify(selectedItems)); // Send items as a JSON string
            formData.append('_token', '{{ csrf_token() }}');

            fetch('/orders', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        title: 'Order Submitted',
                        text: 'Your order was submitted successfully!',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Print Order',
                        cancelButtonText: 'Close'
                    }).then((printResult) => {
                        if (printResult.isConfirmed) {
                            printOrderDetails();
                        } else {
                            window.location.reload();
                        }
                    });
                } else if (response.status === 422) {
                    throw new Error('Transaction ID already exists');
                } else {
                    throw new Error('Failed to submit order');
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
    });
}
    

    function decreaseFoodQuantity(selectedItems) {
        selectedItems.forEach(item => {
            fetch(`/foods/${item.id}/decreaseQuantity`, {
                method: 'POST',
                body: JSON.stringify({ number_of_items: item.quantity }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    console.error('Failed to decrease quantity for food item:', item.id);
                }
            })
            .catch(error => {
                console.error('Error occurred:', error);
            });
        });
    }
</script>


@endsection
