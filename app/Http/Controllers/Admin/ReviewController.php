<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ReviewController extends Controller
{
    // List all reviews with user and product info
  public function index()
{
    try {
        // Log a message before fetching the reviews
        Log::info('Fetching reviews from the database.');

        // Fetch reviews along with their associated user and product data
        $reviews = Review::with('user', 'product')->get();

        // Log the number of reviews fetched
        Log::info('Number of reviews fetched: ' . $reviews);

        // Return the data as a JSON response
        return response()->json($reviews, 200);
    } catch (\Exception $e) {
        // Log the error message
        Log::error('Error fetching reviews: ' . $e->getMessage());

        // Return a JSON error response
        return response()->json(['error' => 'Failed to fetch reviews'], 500);
    }
}





    // Update a review's rating, comment, or status
   public function update(Request $request, $id)
{
    $review = Review::findOrFail($id);

    // Validate the incoming data (including status)
    $validated = $request->validate([
        'rating' => 'sometimes|required|integer|min:1|max:5',
        'review' => 'sometimes|nullable|string',
        'status' => 'sometimes|in:pending,approved,rejected,featured', // Add 'featured' status
    ]);

    // Update the review with the validated data
    $review->update($validated);

    return response()->json($review);
}


    // Delete a review
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(null, 204);
    }
}
