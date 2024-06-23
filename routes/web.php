

<?php


use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TakeOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/takeorders', function () {
	return view('pages.takeorders');
})->name('takeorders');


Route::get('/show', [WeatherController::class, 'showWeatherData'])->name('show');
Route::get('/show1', [WeatherController::class, 'showWeatherData'])->name('show1');

Route::get('/table', 'App\Http\Controllers\HomeController@index')->middleware('manager');
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();
Route::group(['middleware' => 'auth'], function () {
	Route::get('/table', [UserController::class, 'index']);
	//Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::get('/search', [UserController::class, 'search'])->name('table.search');
	//Route::get('/addcategory', [FoodController::class, 'index'])->name('foodcategories.index');
	Route::get('/takeorders', [TakeOrderController::class, 'index'])->name('pages.takeorders');
	Route::post('/orders', [TakeOrderController::class, 'store'])->name('orders.store');
	Route::post('/foods/{food}/decreaseQuantity', [TakeOrderController::class, 'decreaseQuantity'])->name('foods.decreaseQuantity');
	Route::get('/additem', [ItemController::class, 'showAddItemPage'])->name('pages.additem');
	//Route::get('profile', ['as' => 'profile.showProfile', 'uses' => 'App\Http\Controllers\ProfileController@showProfile']);
	//Route::get('/profile', 'ProfileController@showProfile')->name('profile.edit');
	Route::get('/sales/daily', [HomeController::class, 'getDailySales'])->name('sales.daily');
	Route::get('/sales/weekly', [HomeController::class, 'getWeeklySales'])->name('sales.weekly');

	//Route::get('/addcategory', [FoodController::class, 'index']);
	//Route::post('/food', 'FoodController@store')->name('food.store');
	//Route::put('/table/{user}', 'UserController@update')->name('table.update');
	Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('additem.edit');
	Route::put('/update/{id}', [ItemController::class, 'update'])->name('additem.update');
	Route::delete('/delete/{id}', [ItemController::class, 'destroy'])->name('additem.delete');
	Route::delete('/table/{id}', [UserController::class, 'destroy'])->name('table.destroy');
	Route::put('/table/{id}', [UserController::class, 'update'])->name('table.update');
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@show']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
	// Route::get('/additem', [FoodController::class, 'store'])->name('pages.additem');
	Route::get('/cashiers/create', [CashierController::class, 'create'])->name('cashiers.create');
	Route::post('/cashiers', [CashierController::class, 'store'])->name('cashiers.store');
	Route::post('/check-email', [CashierController::class, 'checkEmail'])->name('register.check-email');
	Route::get('/send-email', [EmailController::class, 'showEmailForm']);
    Route::post('/send-email', [EmailController::class, 'sendEmail']);
	Route::post('/generate-order-details-pdf', [TakeOrderController::class, 'generateOrderDetailsPdf']);
	Route::post('/generate-order-details-pdf', [ProfileController::class, 'generateOrderDetailsPdf'])->name('generate.order.details.pdf');
	Route::post('/generate-order-details-pdf', [TransactionController::class, 'generateOrderDetailsPdf'])->name('generate.order.details.pdf');
	Route::get('pages/transaction', [TransactionController::class, 'show'])->name('pages.transaction');
	//Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('send-email');
	Route::post('/generate-otp', [OTPController::class, 'generateOTP']);
	Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);	
	Route::delete('/addcategory/{id}', [FoodController::class, 'destroy'])->name('addcategory.destroy');
	Route::patch('/edit/{order}/pay', [ProfileController::class, 'pay'])->name('edit.pay');
	//Route::get('/orders/{order}', [TakeOrderController::class, 'view'])->name('orders.view');
	//Route::get('/profile/{order}/edit', [ProfileController::class, 'view'])->name('profile.edit');
	Route::patch('/profile/edit/{order}/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');
	Route::patch('/profile/edit/{order}/cancelpayment', [ProfileController::class, 'cancelPayment'])->name('profile.orders.cancelpayment');
	Route::get('/orders/{order}/items', [ProfileController::class, 'getOrderItems']);
});

Route::get('/register', function () {
	return view('pages.register');
})->name('register');

Route::get('/show-table', 'AccessController@showTable')->name('table');
Route::post('/ajax-login', [LoginController::class, 'ajaxLogin'])->name('ajax.login');
Route::post('/login', [LoginController::class, 'verify_login_email'])->name('google.authorization');

// Route::get('/add-item', [FoodController::class, 'create'])->name('add.item.form');
// Route::post('/add-item', [FoodController::class, 'store'])->name('add.item');
Route::post('/addcategory', [FoodController::class, 'addCategory'])->name('pages.addcategory');
Route::post('/additem', [ItemController::class, 'store'])->name('pages.additem');
Route::get('/get-food-items/{categoryId}', 'FoodController@getFoodItems')->name('get.food.items');
//Route::get('/additem', [FoodController::class, 'addItem'])->name('pages.additem');
//Route::get('/additem', 'FoodController@addItem')->name('additem');


//Route::get('/categories/selection', [FoodController::class, 'showCategoriesSelection'])->name('show.categories.selection');




Route::post('/food', [FoodController::class, 'store'])->name('food.store');