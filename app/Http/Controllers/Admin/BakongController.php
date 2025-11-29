<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Merchantbakongkhqr;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BakongController extends Controller
{
    public function bakong()
    {
        // $bakong = Merchantbakongkhqr::first();
        return view('admin.bakong.index');
    }
    public function viewkhqr()
    {
        $bakong = Merchantbakongkhqr::first();
        return view('admin.bakong.viewkhqr', compact('bakong'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Store only non-null values
        $merchant = Merchantbakongkhqr::createNonNull($data);

        return response()->json([
            'success' => true,
            'data' => $merchant,
        ]);
    }
    
    public function saveDecodedData(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'merchantType' => 'nullable|string',
                'bakongAccountID' => 'nullable|string',
                'transactionCurrency' => 'nullable|string',
                'countryCode' => 'nullable|string',
                'merchantName' => 'nullable|string',
                'merchantCity' => 'nullable|string',
                'crc' => 'nullable|string',
            ]);

            // Ensure only one record exists by updating or creating based on a unique condition
            $merchant = Merchantbakongkhqr::updateOrCreate(
                ['merchantType' => $validated['merchantType'] ?? 'default'], // Use a unique field or fallback
                $validated
            );

            return response()->json([
                'success' => true,
                'message' => 'Decoded data saved successfully!',
                'data' => $merchant
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error saving decoded data: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   
}
