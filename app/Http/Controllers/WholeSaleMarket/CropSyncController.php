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

    //                 $data = $item['data'];

    //                 $action = $item['action'] ?? 'create';

    //                 // ==========================================
    //                 // CREATE
    //                 // ==========================================

    //                 if ($action === 'create') {

    //                     // -------------------------------
    //                     // Duplicate Check
    //                     // -------------------------------

    //                     $existing = Crop::where(
    //                         'client_uuid',
    //                         $data['client_uuid']
    //                     )->first();

    //                     // client_uuid အရင်ရှိပြီးသား
    //                     if ($existing) {

    //                         return;
    //                     }

    //                     // =========================================
    //                     // Crop Name Unique Check
    //                     // =========================================

    //                     $duplicateCrop = Crop::where(
    //                         'crop_name',
    //                         $data['crop_name']
    //                     )->first();

    //                     if ($duplicateCrop) {

    //                         throw new \Exception(
    //                             'ဤသီးနှံအမည်ကို ထည့်သွင်းပြီးသားဖြစ်ပါသည်။'
    //                         );

    //                     }

    //                     // -------------------------------
    //                     // Create Only If Not Exists
    //                     // -------------------------------

    //                     if (! $existing) {

    //                         Crop::create([

    //                             'client_uuid'         =>
    //                             $data['client_uuid'],

    //                             'crop_name'           =>
    //                             $data['crop_name'],

    //                             'commission_amount'   =>
    //                             $data['commission_amount'],

    //                             'unit'                =>
    //                             $data['unit'],

    //                             'quantity_per_basket' =>
    //                             $data['quantity_per_basket'],

    //                         ]);

    //                     }

    //                 }

    //                 // ==========================================
    //                 // UPDATE
    //                 // ==========================================

    //                 elseif ($action === 'update') {

    //                     // -------------------------------
    //                     // Find Crop By client_uuid
    //                     // -------------------------------

    //                     $crop = Crop::where(
    //                         'client_uuid',
    //                         $data['client_uuid']
    //                     )->first();

    //                     // -------------------------------
    //                     // Crop မတွေ့ရင် Error
    //                     // -------------------------------

    //                     if (! $crop) {

    //                         throw new \Exception(
    //                             'Update လုပ်ရန် Crop မတွေ့ပါ။'
    //                         );

    //                     }

    //                     // -------------------------------
    //                     // Update Crop
    //                     // -------------------------------

    //                     $crop->update([

    //                         'crop_name'           =>
    //                         $data['crop_name'],

    //                         'commission_amount'   =>
    //                         $data['commission_amount'],

    //                         'unit'                =>
    //                         $data['unit'],

    //                         'quantity_per_basket' =>
    //                         $data['quantity_per_basket'],

    //                     ]);

    //                 }

    //                 // ==========================================
    //                 // Unknown Action
    //                 // ==========================================

    //                 else {

    //                     throw new \Exception(
    //                         'Unknown sync action: ' . $action
    //                     );

    //                 }

    //             });

    //             // ==========================================
    //             // Success Result
    //             // ==========================================

    //             $results[] = [

    //                 'client_uuid' =>
    //                 $item['client_uuid'],

    //                 'status'      =>
    //                 'success',

    //                 'action'      =>
    //                 $item['action'] ?? 'create',

    //             ];

    //         }

    //         // ==============================================
    //         // Failed
    //         // ==============================================

    //          catch (\Throwable $e) {

    //             $results[] = [

    //                 'client_uuid' =>
    //                 $item['client_uuid'],

    //                 'status'      =>
    //                 'failed',

    //                 'action'      =>
    //                 $item['action'] ?? 'create',

    //                 'message'     =>
    //                 $e->getMessage(),

    //             ];

    //         }

    //     }

    //     // ==============================================
    //     // Response
    //     // ==============================================

    //     return response()->json([

    //         'success' =>
    //         true,

    //         'results' =>
    //         $results,

    //     ]);
    // }

    // now
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

                $result = DB::transaction(function () use ($item) {

                    $data = $item['data'];

                    $action =
                    $item['action'] ?? 'create';

                    // ==========================================
                    // CREATE
                    // ==========================================

                    if ($action === 'create') {

                        // --------------------------------------
                        // 1. client_uuid duplicate check
                        // --------------------------------------

                        $existing =
                        Crop::where(
                            'client_uuid',
                            $data['client_uuid']
                        )->first();

                        // --------------------------------------
                        // Already synced
                        // --------------------------------------

                        if ($existing) {

                            return [

                                'status' =>
                                'already_synced',

                            ];

                        }

                        // --------------------------------------
                        // 2. crop_name duplicate check
                        // --------------------------------------

                        $duplicateCrop =
                        Crop::where(
                            'crop_name',
                            $data['crop_name']
                        )->first();

                        // --------------------------------------
                        // Duplicate Crop Name
                        // --------------------------------------

                        if ($duplicateCrop) {

                            return [

                                'status'  =>
                                'duplicate',

                                'message' =>
                                'ဤသီးနှံအမည်ကို Database တွင် ရှိပြီးသားဖြစ်ပါသည်။',

                            ];

                        }

                        // --------------------------------------
                        // 3. Create
                        // --------------------------------------

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

                        return [

                            'status' =>
                            'success',

                        ];

                    }

                    // ==========================================
                    // UPDATE
                    // ==========================================

                    // if ($action === 'update') {

                    //     // ==========================================
                    //     // Find Crop
                    //     // ==========================================

                    //     $crop = null;

                    //     // ==========================================
                    //     // First: server_id
                    //     // ==========================================

                    //     if (
                    //         isset($data['server_id']) &&
                    //         ! empty($data['server_id'])
                    //     ) {

                    //         $crop = Crop::find(
                    //             $data['server_id']
                    //         );

                    //     }

                    //     // ==========================================
                    //     // Second: client_uuid
                    //     // ==========================================

                    //     if (! $crop) {

                    //         if (
                    //             isset($data['client_uuid']) &&
                    //             ! empty($data['client_uuid'])
                    //         ) {

                    //             $crop =
                    //             Crop::where(
                    //                 'client_uuid',
                    //                 $data['client_uuid']
                    //             )->first();

                    //         }

                    //     }

                    //     // ==========================================
                    //     // Not Found
                    //     // ==========================================

                    //     if (! $crop) {

                    //         throw new \Exception(
                    //             'Update လုပ်ရန် သီးနှံမတွေ့ပါ။'
                    //         );

                    //     }

                    //     // ==========================================
                    //     // Unique Crop Name Check
                    //     // ==========================================

                    //     $duplicateCrop =
                    //     Crop::where(
                    //         'crop_name',
                    //         $data['crop_name']
                    //     )
                    //         ->where(
                    //             'id',
                    //             '!=',
                    //             $crop->id
                    //         )
                    //         ->first();

                    //     if ($duplicateCrop) {

                    //         return [

                    //             'status'      =>
                    //             'duplicate',

                    //             'client_uuid' =>
                    //             $data['client_uuid'] ?? null,

                    //             'server_id'   =>
                    //             $crop->id,

                    //             'message'     =>
                    //             'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

                    //         ];

                    //     }

                    //     // ==========================================
                    //     // Update Crop
                    //     // ==========================================

                    //     $crop->update([

                    //         'crop_name'           =>
                    //         $data['crop_name'],

                    //         'commission_amount'   =>
                    //         $data['commission_amount'],

                    //         'unit'                =>
                    //         $data['unit'],

                    //         'quantity_per_basket' =>
                    //         $data['quantity_per_basket'],

                    //     ]);

                    //     // ==========================================
                    //     // Return Success
                    //     // ==========================================

                    //     return [

                    //         'status'      =>
                    //         'success',

                    //         'client_uuid' =>
                    //         $data['client_uuid'] ?? null,

                    //         'server_id'   =>
                    //         $crop->id,

                    //     ];

                    // }

                    // v1
                    if ($action === 'update') {

                        // ==========================================
                        // Validate Update Data
                        // ==========================================

                        if (
                            empty($data['crop_name']) ||
                            ! isset($data['commission_amount']) ||
                            empty($data['unit']) ||
                            ! isset($data['quantity_per_basket'])
                        ) {

                            return [

                                'status'      =>
                                'failed',

                                'client_uuid' =>
                                $data['client_uuid'] ?? null,

                                'server_id'   =>
                                $data['server_id'] ?? null,

                                'message'     =>
                                'Update Data မပြည့်စုံပါ။',
                            ];
                        }

                        // ==========================================
                        // Find Crop
                        // ==========================================

                        $crop = null;

                        // ==========================================
                        // First → server_id
                        // ==========================================

                        if (
                            isset($data['server_id']) &&
                            ! empty($data['server_id'])
                        ) {

                            $crop =
                            Crop::find(
                                $data['server_id']
                            );
                        }

                        // ==========================================
                        // Second → client_uuid
                        // ==========================================

                        if (! $crop) {

                            if (
                                isset($data['client_uuid']) &&
                                ! empty($data['client_uuid'])
                            ) {

                                $crop =
                                Crop::where(
                                    'client_uuid',
                                    $data['client_uuid']
                                )->first();
                            }
                        }

                        // ==========================================
                        // Crop မတွေ့ရင်
                        // ==========================================

                        if (! $crop) {

                            return [

                                'status'      =>
                                'failed',

                                'client_uuid' =>
                                $data['client_uuid'] ?? null,

                                'server_id'   =>
                                $data['server_id'] ?? null,

                                'message'     =>
                                'Update လုပ်ရန် သီးနှံမတွေ့ပါ။',
                            ];
                        }

                        // ==========================================
                        // Duplicate Crop Name
                        // ==========================================

                        $duplicateCrop =
                        Crop::where(
                            'crop_name',
                            $data['crop_name']
                        )
                            ->where(
                                'id',
                                '!=',
                                $crop->id
                            )
                            ->first();

                        if ($duplicateCrop) {

                            return [

                                'status'      =>
                                'duplicate',

                                'client_uuid' =>
                                $data['client_uuid'] ?? null,

                                'server_id'   =>
                                $crop->id,

                                'message'     =>
                                'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',
                            ];
                        }

                        // ==========================================
                        // Update Crop
                        // ==========================================

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

                        // ==========================================
                        // Refresh
                        // ==========================================

                        $crop->refresh();

                        // ==========================================
                        // Success
                        // ==========================================

                        return [

                            'status'      =>
                            'success',

                            'client_uuid' =>
                            $data['client_uuid'] ?? null,

                            'server_id'   =>
                            $crop->id,

                            'message'     =>
                            'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',
                        ];
                    }

                    // ==========================================
                    // Unknown Action
                    // ==========================================

                    throw new \Exception(
                        'Unknown sync action: ' . $action
                    );

                });

                // ==========================================
                // Result
                // ==========================================

                $results[] = [

                    'client_uuid' =>
                    $item['client_uuid'],

                    'status'      =>
                    $result['status'],

                    'message'     =>
                    $result['message'] ?? null,

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

                    'message'     =>
                    $e->getMessage(),

                ];

            }

        }

        return response()->json([

            'success' =>
            true,

            'results' =>
            $results,

        ]);
    }

}
