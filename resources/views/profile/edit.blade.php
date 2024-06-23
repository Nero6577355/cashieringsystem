@php
    $user = auth()->user();
    $role = $user->roles ?? '';
@endphp

@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'User Profile',
    'activePage' => 'profile',
    'activeNav' => '',
])

@if($role !== 'manager')
@section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-user">
          <div class="image">
            <img src="{{asset('assets')}}/img/foodcateg.jpg" alt="...">
          </div>
          <div class="card-body">
            <div class="author">
              <a href="">
                <img class="avatar border-gray" src="{{asset('assets')}}/img/default-avatar.png" alt="...">
                <h5 class="title">{{ auth()->user()->name }}</h5>
              </a>
              <p class="description">
                  {{ auth()->user()->email }}
              </p>
              <a href="#" id="sendEmailBtn" class="btn btn-info" data-toggle="modal" data-target="#emailModal">
                            Compose Email <i class="fas fa-envelope"></i>
                        </a>
            </div>
          </div>
          <hr>
          <div class="button-container">
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-facebook-square"></i>
            </button>
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-twitter"></i>
            </button>
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-google-plus-square"></i>
            </button>
            
          </div>
        </div>
      </div>
      <div class="col-md-8">
    <!-- Table and Data -->
    <div class="card">
    <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Transaction History</h5>
                        <!-- <button type="button" class="btn btn-secondary" onclick="printTransactionHistory()">Print Transaction History<i class="fa-solid fa-print"></i></button> -->
                    </div>
                </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->transaction_id }}</td>
                        <td>{{ $order->total_quantity }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                        <div class="btn-group" role="group">
                        <!-- Default "View" Button -->
                        <form action="#" method="POST" class="view-order-form">
                        @csrf
                        <button type="button" class="btn btn-primary view-order" 
                            data-toggle="modal" 
                            data-target="#orderModal" 
                            data-order-id="{{ $order->id }}" 
                            data-transaction-id="{{ $order->transaction_id }}" 
                            data-created-at="{{ $order->created_at }}">
                            <i class="fas fa-check-circle"></i> Order Details
                        </button>
                    </form>

                        <!-- Status-Based Buttons -->
                        @if($order->status === 'Unpaid')
                        <form action="{{ route('edit.pay', $order) }}" method="POST" class="pay-form">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-success pay-button">
                                <i class="fas fa-money-check-alt"></i> Mark as Paid
                            </button>
                        </form>
                            <form action="{{ route('profile.orders.cancel', $order) }}" method="POST" class="cancel-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-danger cancel-button">
                                    <i class="fas fa-times-circle"></i> Cancel Order
                                </button>
                            </form>
                        @elseif($order->status === 'Paid')
                            
                            <form action="{{ route('profile.orders.cancelpayment', $order) }}" method="POST" class="cancel-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-danger cancelpayment-button">
                                    <i class="fas fa-times-circle"></i> Mark as Unpaid
                                </button>
                            </form>
                            <form action="{{ route('profile.orders.cancel', $order) }}" method="POST" class="cancel-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-danger cancel-button">
                                    <i class="fas fa-times-circle"></i> Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>

                        </td>
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

<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">
                    <span id="transactionID"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">
                        <!-- Order items will be populated here by AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <span id="totalPrice" class="mr-auto"></span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-order').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                const transactionId = this.getAttribute('data-transaction-id');
                const createdAt = this.getAttribute('data-created-at');

                fetch(`/orders/${orderId}/items`)
                    .then(response => response.json())
                    .then(data => {
                        // Update modal title with transaction ID and date
                        document.getElementById('transactionID').innerText = `Transaction ID: ${transactionId} | Date: ${createdAt}`;

                        // Clear previous data
                        const orderItemsBody = document.getElementById('orderItemsBody');
                        orderItemsBody.innerHTML = '';

                        let totalPrice = 0;

                        // Populate table with order items
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.price}</td>
                                <td>${item.total_price}</td>
                            `;
                            orderItemsBody.appendChild(row);

                            totalPrice += parseFloat(item.total_price);
                        });

                        // Update total price in the modal footer
                        document.getElementById('totalPrice').innerText = `Total Price: Php${totalPrice.toFixed(2)}`;
                    })
                    .catch(error => console.error('Error fetching order items:', error));
            });
        });
    });
</script>

</script>

          <div id="emailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="emailForm">
                <div class="modal-body">
                    <div class="form-group">
                    <label for="fromEmail">From:</label>
                    <input type="text" class="form-control" id="fromEmail" value="{{ Auth::user()->email }}" readonly>
                    </div> 
                    <div class="form-group">   
                    <label for="recipientEmail">To:</label>
                        <input type="email" class="form-control" id="recipientEmail" name="recipientEmail" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="emailSubject">Subject</label>
                        <input type="text" class="form-control" id="emailSubject" name="emailSubject" placeholder="Email Subject" required>
                    </div>
                    <div class="form-group">
                        <label for="emailContent">Content</label>
                        <textarea class="form-control" id="emailContent" name="emailContent" placeholder="Compose your email..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div>
      </div>
    </div>
  </div>
@endsection
@endif



  @if($role === 'manager')
  @section('content')
  <div class="panel-header panel-header-sm">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="title">{{__(" Edit Profile")}}</h5>
          </div>
          <div class="card-body">
            <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
            enctype="multipart/form-data">
              @csrf
              @method('put')
              @include('alerts.success')
              <div class="row">
              </div>
              <div class="row">
    <div class="col-md-7 pr-1">
        <div class="form-group">
            <label>{{ __("First Name") }}</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()->first_name) }}">
            @include('alerts.feedback', ['field' => 'first_name'])
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7 pr-1">
        <div class="form-group">
            <label>{{ __("Middle Name") }}</label>
            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', auth()->user()->middle_name) }}">
            @include('alerts.feedback', ['field' => 'middle_name'])
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7 pr-1">
        <div class="form-group">
            <label>{{ __("Last Name") }}</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
            @include('alerts.feedback', ['field' => 'last_name'])
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7 pr-1">
        <div class="form-group">
            <label>{{ __("Full Name") }}</label>
            <input type="text" id="full_name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" readonly>
            @include('alerts.feedback', ['field' => 'name'])
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateFullName() {
            const firstName = document.querySelector('input[name="first_name"]').value;
            const middleName = document.querySelector('input[name="middle_name"]').value;
            const lastName = document.querySelector('input[name="last_name"]').value;
            document.getElementById('full_name').value = `${firstName} ${middleName} ${lastName}`.trim();
        }

        document.querySelector('input[name="first_name"]').addEventListener('input', updateFullName);
        document.querySelector('input[name="middle_name"]').addEventListener('input', updateFullName);
        document.querySelector('input[name="last_name"]').addEventListener('input', updateFullName);
    });
</script>
                
                <div class="row">
                  <div class="col-md-7 pr-1">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{__(" Email address")}}</label>
                      <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', auth()->user()->email) }}">
                      @include('alerts.feedback', ['field' => 'email'])
                    </div>
                  </div>
                </div>
              <div class="card-footer ">
                <button type="submit" class="btn btn-primary btn-round">{{__('Save')}}</button>
              </div>
              <hr class="half-rule"/>
            </form>
          </div>
          <div class="card-header">
            <h5 class="title">{{__("Password")}}</h5>
          </div>
          <div class="card-body">
            <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
              @csrf
              @method('put')
              @include('alerts.success', ['key' => 'password_status'])
              <div class="row">
                <div class="col-md-7 pr-1">
                  <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label>{{__(" Current Password")}}</label>
                    <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="old_password" placeholder="{{ __('Current Password') }}" type="password"  required>
                    @include('alerts.feedback', ['field' => 'old_password'])
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-7 pr-1">
                  <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label>{{__(" New password")}}</label>
                    <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" type="password" name="password" required>
                    @include('alerts.feedback', ['field' => 'password'])
                  </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-7 pr-1">
                <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
                  <label>{{__(" Confirm New Password")}}</label>
                  <input class="form-control" placeholder="{{ __('Confirm New Password') }}" type="password" name="password_confirmation" required>
                </div>
              </div>
            </div>
            <div class="card-footer ">
              <button type="submit" class="btn btn-primary btn-round ">{{__('Change Password')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
      <div class="col-md-4">
        <div class="card card-user">
          <div class="image">
            <img src="{{asset('assets')}}/img/bg5.jpg" alt="...">
          </div>
          <div class="card-body">
            <div class="author">
              <a href="">
                <img class="avatar border-gray" src="{{asset('assets')}}/img/default-avatar.png" alt="...">
                <h5 class="title">{{ auth()->user()->name }}</h5>
              </a>
              <p class="description">
                  {{ auth()->user()->email }}
              </p>
            </div>
          </div>
          <hr>
          <div class="button-container">
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-facebook-square"></i>
            </button>
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-twitter"></i>
            </button>
            <button href="#" class="btn btn-neutral btn-icon btn-round btn-lg">
              <i class="fab fa-google-plus-square"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewOrder(orderId) {
        // Make an AJAX request to fetch the order details
        fetch(`/orders/${orderId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch order details');
                }
                return response.json();
            })
            .then(data => {
                // Update the modal content with the fetched order details
                updateModalContent(data);
            })
            .catch(error => {
                console.error('Error fetching order details:', error);
                // Handle the error here, e.g., display an error message to the user
            });
    }

    function updateModalContent(orderData) {
        // Update the modal content with the fetched order details
        // You can access the order details and order items from the 'orderData' object
        console.log(orderData);
        // Example: Update the modal body with order details
        document.getElementById('orderDetails').innerHTML = `
            <p>Transaction ID: ${orderData.order.transaction_id}</p>
            <p>Total Quantity: ${orderData.order.total_quantity}</p>
            <p>Total Price: ${orderData.order.total_price}</p>
            <p>Status: ${orderData.order.status}</p>
            <p>Created At: ${orderData.order.created_at}</p>
            <h4>Order Items:</h4>
            <ul>
                ${orderData.order_items.map(item => `
                    <li>${item.name} - Quantity: ${item.quantity}, Price: ${item.price}, Total Price: ${item.total_price}</li>
                `).join('')}
            </ul>
        `;
        // Show the modal
        $('#myModal').modal('show');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButtons = document.querySelectorAll('.pay-button');

        payButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                const action = form.getAttribute('action');

                Swal.fire({
                    title: 'Confirm Payment?',
                    text: "Are you sure you want to mark this order as paid?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, pay it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                _method: 'PATCH'
                            })
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        }).then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Paid!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        }).catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'There was an error processing the payment.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelButtons = document.querySelectorAll('.cancel-button');

        cancelButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                const action = form.getAttribute('action');

                Swal.fire({
                    title: 'Cancel Order?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                _method: 'PATCH'
                            })
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        }).then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Cancelled!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        }).catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'There was an error canceling the order.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelButtons = document.querySelectorAll('.cancelpayment-button');

        cancelButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const form = this.closest('form');
                const action = form.getAttribute('action');

                Swal.fire({
                    title: 'Mark as Unpaid?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                _method: 'PATCH'
                            })
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok.');
                            }
                            return response.json();
                        }).then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Unpaid!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        }).catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'There was an error canceling the order.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
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
                    orderDetailsTable += `
                        <tr>
                            <td>${order.transaction_id}</td>
                            <td>${order.total_quantity}</td>
                            <td>${order.total_price}</td>
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
                            <th>Total Quantity</th>
                            <th>Total Price</th>
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


                // Open a new window
                var newWindow = window.open('', '', 'height=600,width=800');

                // Write the content into the new window
                newWindow.document.write('<html><head><title>Print Transaction History</title>');
                newWindow.document.write('<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/now-ui-dashboard.css') }}" />');
                newWindow.document.write('</head><body>');
                newWindow.document.write(orderDetailsContent);
                newWindow.document.write('</body></html>');

                // Close the document to finish the writing process
                newWindow.document.close();

                // Print the content of the new window
                newWindow.print();

                // Send the HTML content to the server to generate a PDF
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

<script>
        document.addEventListener('DOMContentLoaded', function () {
        const emailForm = document.getElementById('emailForm');

        emailForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const recipientEmail = document.getElementById('recipientEmail').value;
            const emailSubject = document.getElementById('emailSubject').value;
            const emailContent = document.getElementById('emailContent').value;

            Swal.fire({
                title: 'Send Email',
                text: 'Are you sure you want to send the email?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send email using AJAX
                    fetch('/send-email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            recipientEmail: recipientEmail,
                            emailSubject: emailSubject,
                            emailContent: emailContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Success",
                                text: data.success,
                                icon: "success"
                            });
                        } else if (data.error) {
                            Swal.fire({
                                title: "Error",
                                text: data.error,
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
</script>