<?php

use App\Http\Controllers\GuestController;
use App\Http\Controllers\RestaurantGalleryController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderMenuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\RestaurantMembershipController;
use App\Http\Controllers\GuestTypeController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\URL;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * administrator routes
 * */
Auth::routes(['verify' => true]);

Route::get('/', [ReservationController::class, 'index']);

Route::get('/subscription_expired', function () {
    return view('Admin.User.subscription_expired');
})->middleware(['auth'])->name('subscription_expired');

Route::get('/dashboard', function () {
    return Route('dashboard');
})->middleware(['auth', 'Restaurant', 'CheckSubscription'])->name('dashboard');

Route::get('');

Route::group(['middleware' => ['auth', 'Restaurant', 'CheckSubscription']], function() {
    // administrator routes
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('package_manager', PackageController::class);
    Route::get('manage_restaurants', [AdminController::class, 'manage_restaurants'])->middleware(['auth'])->name('manage_restaurants');
    Route::post('add_user', [RestaurantController::class, 'add_user'])->middleware(['auth'])->name('add_user');
    Route::get('Restaurant_users', [RestaurantController::class, 'Restaurant_users'])->middleware(['auth'])->name('Restaurant_users');
    Route::resource('my_gallery', RestaurantGalleryController::class);
    Route::get('add_gallery_image', [RestaurantGalleryController::class, 'add_image'])->middleware(['auth']);
    Route::get('make_featured/{id}', [AdminController::class, 'make_featured'])->middleware(['auth'])->name('featured');
    Route::resource('dashboard', DashboardController::class)->middleware(['auth']);
    Route::resource('membership', RestaurantMembershipController::class)->middleware(['auth']);
    Route::get('donut_data', [DashboardController::class, 'donut_data'])->middleware(['auth'])->name('donut_data');
    Route::post('dashboard_sort_date', [DashboardController::class, 'dashboard_sort_date'])->middleware(['auth'])->name('dashboard_sort_date');
    Route::get('add_type', [AdminController::class, 'add_type'])->middleware(['auth'])->name('add_type');
    Route::post('store_type', [AdminController::class, 'store_type'])->middleware(['auth'])->name('store_type');
    Route::resource('accounts', AccountController::class)->middleware(['auth']);
    Route::resource('guest_types', GuestTypeController::class)->middleware(['auth']);
    Route::resource('administrator', AdminController::class)->middleware(['auth']);
    Route::get('get_restaurant_users/{id}', [AdminController::class, 'get_restaurant_users'])->middleware(['auth']);
    Route::get('clients', [AdminController::class, 'clients'])->middleware(['auth'])->name('clients');
    Route::delete('delete_restaurant/{id}', [AdminController::class])->name('delete_restaurant');
    Route::get('/report/{id}', [AdminController::class, 'report'])->middleware(['auth']);
    Route::get('reports', [AdminController::class, 'my_reports'])->middleware(['auth']);
    Route::get('restaurant_type', [AdminController::class, 'restaurant_type'])->middleware(['auth'])->name('restaurant_type');
    Route::post('update_restaurant', [AdminController::class, 'update_restaurant'])->middleware(['auth'])->name('update_restaurant');
    Route::resource('tables', TableController::class)->middleware(['auth']);
    Route::get('my_tables/{id}', [TableController::class, 'index'])->middleware(['auth'])->name('my_tables');
    Route::get('create_table/{id}', [TableController::class, 'create_table'])->middleware(['auth'])->name('create_table');
    Route::get('grid/{id}', [ReservationController::class, 'grid'])->middleware(['auth'])->name('grid');
    Route::get('grid_data/{id}', [ReservationController::class, 'grid_data'])->middleware(['auth'])->name('grid_data');
    Route::get('get_restaurant/{id}', [RestaurantController::class, 'get_restaurant'])->middleware(['auth'])->name('get_restaurant');
    Route::post('get_guests', [GuestController::class, 'get_guests'])->middleware(['auth'])->name('get_guests');
    Route::get('get_all_guests/{id}', [GuestController::class, 'get_all_guests'])->middleware(['auth'])->name('get_all_guests');
    Route::post('find_guest', [GuestController::class, 'find_guest'])->middleware(['auth'])->name('find_guest');
    Route::post('update_reservation_status', [ReservationController::class, 'update_reservation_status'])->middleware(['auth'])->name('update_reservation_status');
    Route::get('restaurant_dashboard/{id}', [DashboardController::class, 'restaurant_dashboard'])->name('restaurant_dashboard');
    Route::get('table_reservations/{id}', [ReservationController::class, 'table_reservations'])->middleware(['auth'])->name('table_reservations');
    Route::get('guests/{id}', [GuestController::class, 'guests'])->middleware(['auth'])->name('guests');
    Route::post('get_reservations', [GuestController::class, 'get_reservations'])->middleware(['auth'])->name('get_reservations');
    Route::post('get_upcoming', [GuestController::class, 'get_upcoming'])->middleware(['auth'])->name('get_upcoming');
    Route::post('get_cancelled', [GuestController::class, 'get_cancelled'])->middleware(['auth'])->name('get_cancelled');
    Route::post('get_denied', [GuestController::class, 'get_denied'])->middleware(['auth'])->name('get_denied');
    Route::post('get_cover', [GuestController::class, 'get_cover'])->middleware(['auth'])->name('get_cover');
    Route::post('get_total_spend', [GuestController::class, 'get_total_spend'])->middleware(['auth'])->name('get_total_spend');
    Route::get('reservation_graph_data', [DashboardController::class, 'reservation_graph_data']);
    Route::get('revenues_graph_data', [DashboardController::class, 'revenues_graph_data']);
    Route::get('restaurant_reservation_graph_data/{id}', [DashboardController::class, 'restaurant_reservation_graph_data']);
    Route::get('restaurant_revenues_graph_data/{id}', [DashboardController::class, 'restaurant_revenues_graph_data']);
    Route::get('edit_table_layout/{id}', [TableController::class, 'edit_table_layout'])->name('edit_table_layout');
    Route::patch('update_table_layout', [TableController::class, 'update_table_layout'])->name('update_table_layout');
    Route::get('get_table_positions/{id}', [TableController::class, 'get_table_positions'])->name('get_table_positions');
    Route::post('get_history', [TableController::class, 'get_history'])->name('get_history');
    Route::get('get_booked_tables', [TableController::class, 'get_booked_tables'])->name('get_booked_tables');
    Route::get('booked_tables/{id}', [TableController::class, 'booked_tables'])->name('booked_tables');
    Route::get('send-email', [SendEmailController::class, 'sendEmail']);
    Route::delete('delete_user/{id}', [RestaurantController::class, 'delete_user'])->middleware(['auth'])->name('delete_user');
    Route::put('edit_user/{user_id}', [RestaurantController::class, 'edit_user'])->middleware(['auth'])->name('edit_user');
    Route::get('show_memberships', [RestaurantMembershipController::class, 'show_memberships'])->middleware(['auth'])->name('show_memberships');
    Route::get('delete_membership/{id}', [RestaurantMembershipController::class, 'delete_membership'])->middleware(['auth'])->name('delete_membership'); 
    Route::get('show_guest_types', [GuestTypeController::class, 'show_guest_types'])->middleware(['auth'])->name('show_guest_types');
    Route::get('delete_guest/{id}', [GuestTypeController::class, 'delete_guest'])->middleware(['auth'])->name('delete_guest');
    Route::get('get_reservation/{id}', [ReservationController::class, 'get_reservation'])->name('get_reservation');
    //meeting routes goes here
    Route::get('get_restaurant_type/{id}', [RestaurantController::class, 'get_restaurant_type'])->name('get_restaurant_type');
    Route::get('get_guest_type/{id}', [RestaurantController::class, 'get_guest_type'])->name('get_guest_type');
    Route::get('get_memberships/{id}', [RestaurantController::class, 'get_memberships'])->name('get_memberships');
    Route::get('get_free_tables/{id}', [RestaurantController::class, 'get_free_tables'])->name('get_free_tables');
});
/**
 * administrator routes end
 *
 */

require __DIR__.'/auth.php';
Route::get('/', [ReservationController::class, 'index']);
Route::get('get_all_restaurants', [ReservationController::class, 'get_all_restaurants'])->name('get_all_restaurants');
Route::get('reserve_restaurant/{id}', [ReservationController::class, 'reserve_restaurant'])->name('reserve_restaurant');
Route::post('check_restaurant', [ReservationController::class, 'check_restaurant'])->name('check_restaurant');
Route::resource('reservations', ReservationController::class);
Route::post('check_availability', [ReservationController::class, 'check_availability'])->name('check_availability');
Route::post('book_table', [ReservationController::class, 'book_table'])->name('book_table');
Route::post('book_restaurant', [ReservationController::class, 'book_restaurant'])->name('book_restaurant');
Route::resource('guest', GuestController::class);
Route::get('get_coordinates/{id}', [ReservationController::class, 'get_coordinates']);
Route::get('all_restaurants', [ReservationController::class, 'all_restaurants'])->name('all_restaurants');
Route::get('booking_history/{id}', [ReservationController::class, 'booking_history'])->name('booking_history');
Route::get('create_reservation/{id}', [ReservationController::class, 'create_reservation'])->middleware(['auth'])->name('create_reservation');
Route::get('sort_by_date', [ReservationController::class, 'sort_by_date'])->middleware(['auth'])->name('sort_by_date');


/**
 * reservation routes end
 *
 */

URL::forceScheme('https');


