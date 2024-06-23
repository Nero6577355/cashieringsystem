<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class ItemController extends Controller
{
    
    public function index(){
        $categories = FoodCategory::all();
        return view('pages/additem', compact('categories'));
    }
    public function showAddItemPage()
    {
        $foods = Food::paginate(3);
        return view('pages/additem', compact('foods'));
    }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'category' => 'required|exists:categories,id',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'number_of_items' => 'required|integer',// Add validation for number_of_items
    ]);

    $existingFood = Food::where('name', $request->name)
                        ->where('category_id', $request->category)
                        ->first();

    if ($existingFood) {
        return redirect()->back()->with('error', 'Food item already exists.');
    }

    $photoPath = $request->file('photo')->store('photos', 'public');

    Food::create([
        'name' => $request->name,
        'price' => $request->price,
        'category_id' => $request->category,
        'photo' => $photoPath,
        'number_of_items' => $request->number_of_items, // Include number_of_items here
    ]);

    return redirect()->back()->with('success', 'Food item added successfully.');
}

    public function getFoodItems($id)
    {
        $foodItems = Food::where('category_id', $id)->get();
        return response()->json($foodItems);
    }
    public function edit($id)
    {
        $food = Food::findOrFail($id);
        return view('pages.additem', compact('food'));
    }
    public function update(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
    
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'number_of_items' => 'required|integer',
                // Add more validation rules as needed
            ]);
    
            $food = Food::findOrFail($decryptedId);
    
            // Update the fields that are not related to the photo
            $food->name = $validatedData['name'];
            $food->price = $validatedData['price'];
            $food->number_of_items = $validatedData['number_of_items'];
    
            // Check if a new photo is provided
            if ($request->hasFile('photo')) {
                // Delete the old photo
                Storage::disk('public')->delete($food->photo);
    
                // Store the new photo
                $photoPath = $request->file('photo')->store('photos', 'public');
                $food->photo = $photoPath;
            }
    
            // Save the changes
            $food->save();
    
            return redirect()->route('pages.additem')->with('success', 'Food item updated successfully');
        } catch (DecryptException $e) {
            return redirect()->route('pages.additem')->with('error', 'Invalid food ID');
        } catch (\Exception $e) {
            return redirect()->route('pages.additem')->with('error', 'An error occurred while updating the food item');
        }
    }
    public function destroy($id)
    {
        try {
            $decryptedId = decrypt($id);
    
            $food = Food::findOrFail($decryptedId);
            $food->delete();
    
            return redirect()->route('pages.additem')->with('success', 'Food item deleted successfully');
        } catch (DecryptException $e) {
            return redirect()->route('pages.additem')->with('error', 'Invalid food ID');
        } catch (\Exception $e) {
            return redirect()->route('pages.additem')->with('error', 'An error occurred while deleting the food item');
        }
    }
        

}
