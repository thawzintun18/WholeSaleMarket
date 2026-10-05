<?php
namespace App\Http\Controllers\WholeSaleMarket;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CropSyncController extends Controller
{
    //sync
    // public function sync(Request $request)
    // {

    //     $request->validate([
    //         'items' => [
    //             'required',
    //             'array',
    //         ],
    //     ]);

    //     $results = [];

    //     foreach ($request->items as $item) {

    //         try {

    //             DB::transaction(function () use ($item) {

    //                 $data =
    //                     $item['data'];

    //                 // ==========================
    //                 // Duplicate Check
    //                 // ==========================

    //                 $existing =
    //                 Crop::where(
    //                     'client_uuid',
    //                     $data['client_uuid']
    //                 )->first();

    //                 // ==========================
    //                 // Create only if not exists
    //                 // ==========================

    //                 if (! $existing) {

    //                     Crop::create([

    //                         'client_uuid'         =>
    //                         $data['client_uuid'],

    //                         'crop_name'           =>
    //                         $data['crop_name'],

    //                         'commission_amount'   =>
    //                         $data[
    //                             'commission_amount'
    //                         ],

    //                         'unit'                =>
    //                         $data['unit'],

    //                         'quantity_per_basket' =>
    //                         $data[
    //                             'quantity_per_basket'
    //                         ],
    //                     ]);
    //                 }

    //             });

    //             $results[] = [

    //                 'client_uuid' =>
    //                 $item['client_uuid'],

    //                 'status'      =>
    //                 'success',
    //             ];

    //         } catch (\Throwable $e) {

    //             $results[] = [

    //                 'client_uuid' =>
    //                 $item['client_uuid'],

    //                 'status'      =>
    //                 'failed',

    //                 'message'     =>
    //                 $e->getMessage(),
    //             ];
    //         }
    //     }

    //     return response()->json([

    //         'success' =>
    //         true,

    //         'results' =>
    //         $results,
    //     ]);
    // }

    public function sync(Request $request)
    {
        $request->validate([
            'items' => [
                'required',
                'array',
            ],
        ]);

        $results = [];

        foreach ($request->items as $item) {

            try {

                DB::transaction(function () use ($item) {

                    $data = $item['data'];

                    $action = $item['action'] ?? 'create';

                    // ==========================================
                    // CREATE
                    // ==========================================

                    if ($action === 'create') {

                        // -------------------------------
                        // Duplicate Check
                        // -------------------------------

                        $existing = Crop::where(
                            'client_uuid',
                            $data['client_uuid']
                        )->first();

                        // client_uuid အရင်ရှိပြီးသား
                        if ($existing) {

                            return;
                        }

// =========================================
// Crop Name Unique Check
// =========================================

                        $duplicateCrop = Crop::where(
                            'crop_name',
                            $data['crop_name']
                        )->first();

                        if ($duplicateCrop) {

                            throw new \Exception(
                                'ဤသီးနှံအမည်ကို ထည့်သွင်းပြီးသားဖြစ်ပါသည်။'
                            );

                        }

                        // -------------------------------
                        // Create Only If Not Exists
                        // -------------------------------

                        if (! $existing) {

                            Crop::create([

                                'client_uuid'         =>
                                $data['client_uuid'],

                                'crop_name'           =>
                                $data['crop_name'],

                                'commission_amount'   =>
                                $data['commission_amount'],

                                'unit'                =>
                                $data['unit'],

                                'quantity_per_basket' =>
                                $data['quantity_per_basket'],

                            ]);

                        }

                    }

                    // ==========================================
                    // UPDATE
                    // ==========================================

                    elseif ($action === 'update') {

                        // -------------------------------
                        // Find Crop By client_uuid
                        // -------------------------------

                        $crop = Crop::where(
                            'client_uuid',
                            $data['client_uuid']
                        )->first();

                        // -------------------------------
                        // Crop မတွေ့ရင် Error
                        // -------------------------------

                        if (! $crop) {

                            throw new \Exception(
                                'Update လုပ်ရန် Crop မတွေ့ပါ။'
                            );

                        }

                        // -------------------------------
                        // Update Crop
                        // -------------------------------

                        $crop->update([

                            'crop_name'           =>
                            $data['crop_name'],

                            'commission_amount'   =>
                            $data['commission_amount'],

                            'unit'                =>
                            $data['unit'],

                            'quantity_per_basket' =>
                            $data['quantity_per_basket'],

                        ]);

                    }

                    // ==========================================
                    // Unknown Action
                    // ==========================================

                    else {

                        throw new \Exception(
                            'Unknown sync action: ' . $action
                        );

                    }

                });

                // ==========================================
                // Success Result
                // ==========================================

                $results[] = [

                    'client_uuid' =>
                    $item['client_uuid'],

                    'status'      =>
                    'success',

                    'action'      =>
                    $item['action'] ?? 'create',

                ];

            }

            // ==============================================
            // Failed
            // ==============================================

             catch (\Throwable $e) {

                $results[] = [

                    'client_uuid' =>
                    $item['client_uuid'],

                    'status'      =>
                    'failed',

                    'action'      =>
                    $item['action'] ?? 'create',

                    'message'     =>
                    $e->getMessage(),

                ];

            }

        }

        // ==============================================
        // Response
        // ==============================================

        return response()->json([

            'success' =>
            true,

            'results' =>
            $results,

        ]);
    }

}
