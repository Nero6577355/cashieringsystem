<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodCategory;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;
class FoodController extends Controller
{
    public function index(){
        $categories = FoodCategory::all();
        return view('pages/addcategory', compact('categories'));
    }
    public function addCategory(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255|unique:categories',
            ]);
            // Create the food category in the database
            FoodCategory::create([
                'name' => $request->name,
            ]);
    
            // Flash a success message to the session
            $request->session()->flash('success', 'Food category added successfully.');
    
            return redirect()->back();
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors(['name' => 'The food category already exists.'])->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            // Decrypt the ID
            $decryptedId = decrypt($id);
    
            // Find the category record
            $category = FoodCategory::findOrFail($decryptedId);
    
            // Delete the category record
            $category->delete();
    
            // Redirect back with a success message
            return redirect()->back()->with('delete_message', 'Food Category deleted successfully!');
        } catch (DecryptException $e) {
            // Handle the error if the ID cannot be decrypted
            return redirect()->back()->with('error_message', 'Invalid category ID!');
        }
    }
    
}
