@extends('layouts.app', [
    'namePage' => 'Dashboard',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'home',
    'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')
<div class="panel-header panel-header-lg" style="overflow-y: auto;">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <a href="{{ route('show') }}" class="btn btn-primary"><i class="fas fa-sun"></i> Show Weather Details</a>
            </div>
        </div>
        <div class="card">
    <div class="card-body">
        <canvas id="weeklySalesChart" width="400" height="100"></canvas>
    </div>
</div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-chart-line card-icon"> </i>Orders</h5>
                    <p>Today's sales goal is ₱ 2000. Let's strive to achieve it! We're halfway through the week! Our sales target for this week is ₱ 10,000.</p>
                      <div class="dropdown dropup">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="ordersDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Select an Option
                        </button>
                        <div class="dropdown-menu" aria-labelledby="ordersDropdown">
                            <a class="dropdown-item" href="#" onclick="showOrders('daily')">Daily Orders</a>
                            <a class="dropdown-item" href="#" onclick="showOrders('weekly')">Weekly Orders</a>
                            <!-- <a class="dropdown-item" href="#" onclick="showOrders('total')">Total Orders</a> -->
                            <a class="dropdown-item" href="#" onclick="showOrders('dsales')">Daily Sales</a>
                            <a class="dropdown-item" href="#" onclick="showOrders('wsales')">Weekly Sales</a>
                            <a class="dropdown-item" href="#" onclick="resetOrders()">Return</a>
                        </div>
                    </div>
                    <div id="ordersContent" class="mt-3"></div>
                </div>
                </div>
            </div>
       
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-utensils card-icon"></i> Food Menu</h5>
                <p>Don't forget to remind customers about our ongoing promo! Buy one, get one free!</p>
                <div class="dropdown dropup">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="foodMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select an Option
                    </button>
                    <div class="dropdown-menu" aria-labelledby="foodMenuDropdown">
                        <a class="dropdown-item" href="#" onclick="showFood('items')">Food Items</a>
                        <a class="dropdown-item" href="#"onclick="showFood('categories')">Food Categories</a>
                          <a class="dropdown-item" href="#" onclick="resetMenu()">Return</a>
                    </div>
                </div>
                <div id="foodMenuContent" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-cash-register card-icon"></i>Cashiers</h5>
                <p>Reminders to the {{$addcashier}} cashiers, deliver excellent customer service with a smile! Every interaction leaves a lasting impression.</p>
              </div>
        </div>
    </div>
    <div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Transaction History</h5>
            <p class="card-text">View all transactions</p>
            @if(Auth::user()->roles === 'cashier')
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">View</a>
            @elseif(Auth::user()->roles === 'manager')
                <a href="{{ route('pages.transaction') }}" class="btn btn-primary">View</a>
            @endif
        </div>
    </div>
</div>

    
</div>
    </div>
</div>

<style>
  .panel-header {
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
  }
  .card {
    background-color: #f5f5f5;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 20px;
}

.card-title {
    font-size: 20px;
    margin-bottom: 15px;
    color: #333;
}

.card-icon {
    font-size: 28px;
    margin-right: 15px;
    color: #333;
}

.dropup .dropdown-menu {
    bottom: 100%;
    top: auto;
    display: none;
}

.dropup.show .dropdown-menu {
    display: block;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    left: auto;
    top: auto;
    display: none;
    float: left;
    min-width: 10rem;
    padding: 0.5rem 0;
    margin: 0.125rem 0 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: .25rem;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    font-weight: 400;
    color: #333;
}

.btn-primary {
    background-color: #5e72e4;
    border-color: #5e72e4;
}

.btn-primary:hover {
    background-color: #324cdd;
    border-color: #324cdd;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
   var originalContent = '';
      function showOrders(option) {
        var ordersContent = document.getElementById('ordersContent');
        if (option === 'daily') {
            ordersContent.innerHTML = '<h5 class="card-title"> Daily Orders</h5><p>{{$dailyOrders}}</p>';
        } else if (option === 'weekly') {
            ordersContent.innerHTML = '<h5 class="card-title"> Weekly Orders</h5><p>{{$weeklyOrders}}</p>';
        } else if (option === 'total') {
            ordersContent.innerHTML = '<h5 class="card-title"> Total Orders</h5><p>{{$totalOrders}}</p>';
        } else if (option === 'dsales') {
            ordersContent.innerHTML = '<h5 class="card-title"> Daily Sales</h5><p>Php {{$dailySales}}</p>';
        } else if (option === 'wsales') {
            ordersContent.innerHTML = '<h5 class="card-title"> Weekly Sales</h5><p>Php {{$weeklySales}}</p>';
        }
        
        
    }
    function showFood(option) {
        var ordersContent = document.getElementById('foodMenuContent');
        if (option === 'items') {
            ordersContent.innerHTML = '<h5 class="card-title"> Food Menu</h5><p>{{$foods}} items</p>';
        } else if (option === 'categories') {
            ordersContent.innerHTML = '<h5 class="card-title"> Food Categories</h5><p>{{$categoriesCount}} different categories</p>';
    }
  }
    function resetOrders() {
        var ordersContent = document.getElementById('ordersContent');
        ordersContent.innerHTML = originalContent;
    }
    function resetMenu() {
        var ordersContent = document.getElementById('foodMenuContent');
        ordersContent.innerHTML = originalContent;
    }

</script>
<script>
    var ctx = document.getElementById('weeklySalesChart').getContext('2d');

    var data = {
        labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT','SUN' ],
        datasets: [{
            label: 'Total Sales',
            data: {!! json_encode($weeklySalesData) !!},
            backgroundColor: 'rgba(94, 114, 228, 0.5)',
            borderColor: 'rgba(94, 114, 228, 1)',
            borderWidth: 1
        }]
    };

    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    };

    var weeklySalesChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
</script>
@endsection


     
