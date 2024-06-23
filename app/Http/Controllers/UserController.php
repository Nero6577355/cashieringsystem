<?php

namespace App\Http\Controllers;

use App\Models\AddCashier;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    // public function index(User $model)
    // {
    //     return view('users.index', ['users' => $model->paginate(15)]);
    // }

    public function index()
    {
        $addcashiers = AddCashier::paginate(2); // Paginate the query results with 10 items per page
        return view('pages.table', compact('addcashiers'));
    }

  public function destroy(Request $request, $encryptedId)
{
    try {
        // Decrypt the ID
        $id = Crypt::decrypt($encryptedId);
        
        // Find the addcashier record
        $addcashier = AddCashier::findOrFail($id);

        // Delete the addcashier record
        $addcashier->delete();

        // Check if the request is AJAX
        if ($request->ajax()) {
            // Return a JSON response
            return response()->json(['success' => 'Cashier deleted successfully!'], 200);
        }

        // If not AJAX, return a redirect response
        return redirect()->back()->with('delete_message', 'Cashier deleted successfully!');
    } catch (\Exception $e) {
        // If an exception occurs, return a JSON error response
        return response()->json(['error' => 'Failed to delete cashier.'], 500);
    }
}

public function update(Request $request, $encryptedId)
{
    $id = Crypt::decrypt($encryptedId); // Decrypt the ID
    $addcashier = AddCashier::findOrFail($id); // Find the addcashier record
    
    $fullName = trim("{$request['first_name']} {$request['middle_name']} {$request['last_name']}");
    
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255', 
        'email' => 'required|email|unique:addcashier,email,'.$addcashier->id,
        'password' => ['nullable', 'string', 'min:8', 'confirmed',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
    ], [
        'password.regex' => 'The password must contain at least one capital letter, one small letter, one number, one special character, and have a minimum of 8 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
    ]);

    // Hash the password if it's not empty
    if (!empty($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    } else {
        // Remove the password field from the validated data if it's empty
        unset($validatedData['password']);
    }

    try {
        $addcashier->update($validatedData); // Update the addcashier record
        return redirect()->back()->with('edit_message', 'Cashier updated successfully.'); // Redirect back with a success message
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['edit_errormessage' => 'Failed to update cashier. Please try again.']); // Redirect back with an error message
    }
}
    public function search(Request $request)
{
    $search = $request->get('search');
    $addcashiers = AddCashier::where('name', 'LIKE', "%$search%")
                        ->where('roles', '!=', 'manager')
                        ->paginate(10); // Adjust the pagination limit as needed

    return view('pages.table', compact('addcashiers'));
}

}