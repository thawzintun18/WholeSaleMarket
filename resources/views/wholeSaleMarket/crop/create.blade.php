@extends('wholeSaleMarket.layout.main')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-flower1"></i></span>
                <div>
                    <h1 class="h3 mb-1">🌱 သီးနှံအသစ်ထည့်သွင်းရန်</h1>
                    <div class=" d-flex mt-3">
                        <a href="{{ route('WholeSaleMarket#dashboard') }}" class=" mx-2">
                            <small>ပင်မစာမျက်နှာ</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <a href="{{ route('Crop#list') }}" class=" mx-2">
                            <small>သီးနှံစာရင်း</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <p class=" mx-2">သီးနှံအသစ်ထည့်သွင်းရန်</p>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">

            <div class="crop-form-card">

                <!-- Header -->
                <div class="crop-form-header">

                    <div class=" d-flex justify-content-between">
                        <h5>
                            <i class="bi bi-flower1 me-2"></i>
                            သီးနှံအသစ်ထည့်သွင်းရန်
                        </h5>
                        <div class="">
                            <a href="{{ route('Crop#list') }}"
                                class=" btn btn-sm btn-outline-dark text-white border-white">နောက်သို့</a>
                        </div>
                    </div>

                </div>


                <!-- Form -->
                <div class="crop-form-body">

                    <form id="cropForm" action="{{ route('Crop#create') }}" method="POST">

                        @csrf

                        <div class="row g-4">

                            <!-- Crop Name -->
                            <div class="col-12 col-md-6">

                                <label for="crop_name" class="form-label">
                                    သီးနှံအမည်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-flower1"></i>
                                    </span>

                                    <input type="text" name="crop_name" id="crop_name" value="{{ old('crop_name') }}"
                                        class="form-control myanmar-input @error('crop_name') is-invalid @enderror"
                                        placeholder="ဥပမာ - ဆန်၊ ပြောင်း၊ နှမ်း">

                                </div>

                                @error('crop_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Commission Amount -->
                            <div class="col-12 col-md-6">

                                <label for="commission_amount" class="form-label">
                                    ပွဲခ
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-cash-stack"></i>
                                    </span>

                                    <input type="number" name="commission_amount" id="commission_amount"
                                        value="{{ old('commission_amount') }}" min="0"
                                        class="form-control @error('commission_amount') is-invalid @enderror"
                                        placeholder="ဥပမာ - 500">

                                </div>

                                @error('commission_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Quantity Per Basket -->
                            <div class="col-12 col-md-6">

                                <label for="quantity_per_basket" class="form-label">
                                    တစ်တင်းပါ အရေအတွက်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-box-seam"></i>
                                    </span>

                                    <input type="number" name="quantity_per_basket" id="quantity_per_basket"
                                        value="{{ old('quantity_per_basket') }}" min="1"
                                        class="form-control @error('quantity_per_basket') is-invalid @enderror"
                                        placeholder="ဥပမာ - 20">

                                </div>

                                @error('quantity_per_basket')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <!-- Unit -->
                            <div class="col-12 col-md-6">

                                <label for="unit" class="form-label">
                                    ယူနစ်
                                    <span class="required">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-rulers"></i>
                                    </span>

                                    <select name="unit" id="unit"
                                        class="form-select @error('unit') is-invalid @enderror">

                                        <option value="" disabled {{ old('unit') ? '' : 'selected' }}>
                                            ယူနစ်ရွေးချယ်ပါ
                                        </option>

                                        <option value="viss" {{ old('unit') == 'viss' ? 'selected' : '' }}>
                                            ပိဿာ
                                        </option>

                                        <option value="pound" {{ old('unit') == 'pound' ? 'selected' : '' }}>
                                            ပေါင်
                                        </option>

                                    </select>

                                </div>

                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                        </div>


                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4 button-group">

                            <button type="submit" class="btn-create">
                                <i class="bi bi-plus-lg me-1"></i>
                                သီးနှံထည့်သွင်းမည်
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


    </div>
@endsection

@section('OnlineOffline')
    <script>
        // =========================================================
        // IndexedDB Setup
        // =========================================================

        // const DB_NAME = 'WholeSaleMarketDB';
        // const DB_VERSION = 3;

        // let db = null;

        // DB ဖွင့်နေချိန်မှာ request များစွာ မဖွင့်အောင်
        // let dbPromise = null;


        // =========================================================
        // Open / Initialize IndexedDB
        // =========================================================

        // function openDatabase() {

        //     // DB ရှိပြီးသားဆိုရင်
        //     if (db) {
        //         return Promise.resolve(db);
        //     }


        //     // DB ဖွင့်နေပြီးသားဆိုရင်
        //     if (dbPromise) {
        //         return dbPromise;
        //     }


        //     dbPromise = new Promise(function(resolve, reject) {

        //         console.log(
        //             'IndexedDB ကို ဖွင့်နေပါတယ်...'
        //         );


        //         const request =
        //             indexedDB.open(
        //                 DB_NAME,
        //                 DB_VERSION
        //             );


        //         // =================================================
        //         // Database Upgrade
        //         // =================================================

        //         request.onupgradeneeded =
        //             function(event) {

        //                 const database =
        //                     event.target.result;


        //                 // =========================================
        //                 // Crops Store
        //                 // =========================================

        //                 if (
        //                     !database.objectStoreNames
        //                     .contains('crops')
        //                 ) {

        //                     const cropStore =
        //                         database.createObjectStore(
        //                             'crops', {
        //                                 keyPath: 'local_id'
        //                             }
        //                         );


        //                     cropStore.createIndex(
        //                         'client_uuid',
        //                         'client_uuid', {
        //                             unique: true
        //                         }
        //                     );


        //                     cropStore.createIndex(
        //                         'sync_status',
        //                         'sync_status', {
        //                             unique: false
        //                         }
        //                     );


        //                     console.log(
        //                         'crops store created'
        //                     );
        //                 }


        //                 // =========================================
        //                 // Sync Queue Store
        //                 // =========================================

        //                 if (
        //                     !database.objectStoreNames
        //                     .contains('sync_queue')
        //                 ) {

        //                     const syncStore =
        //                         database.createObjectStore(
        //                             'sync_queue', {
        //                                 keyPath: 'id',
        //                                 autoIncrement: true
        //                             }
        //                         );


        //                     syncStore.createIndex(
        //                         'status',
        //                         'status', {
        //                             unique: false
        //                         }
        //                     );


        //                     syncStore.createIndex(
        //                         'client_uuid',
        //                         'client_uuid', {
        //                             unique: false
        //                         }
        //                     );


        //                     console.log(
        //                         'sync_queue store created'
        //                     );
        //                 }
        //             };


        //         // =================================================
        //         // Database Success
        //         // =================================================

        //         request.onsuccess =
        //             function(event) {

        //                 db =
        //                     event.target.result;


        //                 console.log(
        //                     'IndexedDB Connected'
        //                 );


        //                 // DB connection ပိတ်သွားရင်
        //                 db.onclose =
        //                     function() {

        //                         console.warn(
        //                             'IndexedDB connection closed'
        //                         );

        //                         db = null;
        //                         dbPromise = null;
        //                     };


        //                 // DB error
        //                 db.onerror =
        //                     function(event) {

        //                         console.error(
        //                             'IndexedDB Runtime Error:',
        //                             event.target.error
        //                         );
        //                     };


        //                 resolve(db);
        //             };


        //         // =================================================
        //         // Database Error
        //         // =================================================

        //         request.onerror =
        //             function(event) {

        //                 console.error(
        //                     'IndexedDB Open Error:',
        //                     event.target.error
        //                 );


        //                 db = null;
        //                 dbPromise = null;


        //                 reject(
        //                     event.target.error
        //                 );
        //             };


        //         // =================================================
        //         // Database Blocked
        //         // =================================================

        //         request.onblocked =
        //             function() {

        //                 console.warn(
        //                     'IndexedDB Open Blocked'
        //                 );
        //             };

        //     });


        //     return dbPromise;
        // }


        // =========================================================
        // Make Sure DB Is Ready
        // =========================================================

        // async function ensureDB() {

        //     // DB ရှိပြီးသား
        //     if (db) {
        //         return db;
        //     }


        //     // DB မရှိသေးရင် ဖွင့်မယ်
        //     const database =
        //         await openDatabase();


        //     if (!database) {

        //         throw new Error(
        //             'IndexedDB connection မရပါ'
        //         );
        //     }


        //     return database;
        // }



        // =========================================================
        // Page Load
        // =========================================================

        // document.addEventListener(
        //     'DOMContentLoaded',
        //     async function() {

        //         try {

        //             await ensureDB();


        //             console.log(
        //                 'IndexedDB Ready'
        //             );


        //             // Page ဖွင့်တဲ့အချိန် Online ဖြစ်ပြီး
        //             // pending data ရှိရင် sync
        //             if (navigator.onLine) {

        //                 await syncOfflineCrops();
        //             }


        //         } catch (error) {

        //             console.error(
        //                 'IndexedDB initialization failed:',
        //                 error
        //             );
        //         }

        //     }
        // );



        // =========================================================
        // Form Submit
        // =========================================================

        document
            .getElementById('cropForm')
            .addEventListener(
                'submit',
                async function(event) {

                    event.preventDefault();


                    // =============================================
                    // Form Data
                    // =============================================

                    const cropData = {

                        crop_name: document
                            .getElementById('crop_name')
                            .value
                            .trim(),

                        commission_amount: document
                            .getElementById(
                                'commission_amount'
                            )
                            .value,

                        unit: document
                            .getElementById('unit')
                            .value,

                        quantity_per_basket: document
                            .getElementById(
                                'quantity_per_basket'
                            )
                            .value
                    };


                    // =============================================
                    // Online
                    // =============================================

                    if (navigator.onLine) {

                        console.log(
                            'ONLINE → Laravel'
                        );


                        await createCropOnline(
                            cropData
                        );


                        return;
                    }


                    // =============================================
                    // Offline
                    // =============================================

                    console.log(
                        'OFFLINE → IndexedDB'
                    );


                    try {

                        await createCropOffline(
                            cropData
                        );

                    } catch (error) {

                        console.error(
                            'Offline Create Error:',
                            error
                        );


                        Swal.fire({
                            icon: 'error',
                            title: 'သိမ်းဆည်း၍ မရပါ',
                            text: 'Offline Data သိမ်းဆည်းရာတွင် ပြဿနာရှိနေပါသည်။',
                            confirmButtonText: 'အိုကေ'
                        });

                    }

                }
            );



        // =========================================================
        // Create Crop Online
        // =========================================================

        // async function createCropOnline(data) {

        //     try {

        //         const response =
        //             await fetch(
        //                 "{{ route('Crop#create') }}", {
        //                     method: 'POST',

        //                     headers: {

        //                         'Content-Type': 'application/json',

        //                         'Accept': 'application/json',

        //                         'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //                     },

        //                     body: JSON.stringify({

        //                         crop_name: data.crop_name,

        //                         commission_amount: data.commission_amount,

        //                         unit: data.unit,

        //                         quantity_per_basket: data.quantity_per_basket
        //                     })
        //                 }
        //             );


        //         // =============================================
        //         // Success
        //         // =============================================

        //         if (response.ok) {

        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'အောင်မြင်ပါသည်',
        //                 text: 'သီးနှံ ထည့်သွင်းပြီးပါပြီ။',
        //                 confirmButtonText: 'အိုကေ'
        //             });


        //             document
        //                 .getElementById('cropForm')
        //                 .reset();


        //             return;
        //         }


        //         // =============================================
        //         // Validation Error
        //         // =============================================

        //         if (response.status === 422) {

        //             const errors =
        //                 await response.json();


        //             console.log(
        //                 'Validation Errors:',
        //                 errors
        //             );


        //             Swal.fire({
        //                 icon: 'warning',
        //                 title: 'သတိပြုရန်',
        //                 text: 'ထည့်သွင်းထားသော Data ကို ပြန်လည်စစ်ဆေးပါ။',
        //                 confirmButtonText: 'အိုကေ'
        //             });


        //             return;
        //         }


        //         throw new Error(
        //             'Server Error'
        //         );


        //     } catch (error) {

        //         console.error(
        //             'Online Create Error:',
        //             error
        //         );


        //         Swal.fire({
        //             icon: 'error',
        //             title: 'ချိတ်ဆက်မှု မအောင်မြင်ပါ',
        //             text: 'Server နှင့် ချိတ်ဆက်ရာတွင် ပြဿနာရှိနေပါသည်။',
        //             confirmButtonText: 'အိုကေ'
        //         });
        //     }
        // }

        //v1
        async function createCropOnline(data) {

            try {

                const response = await fetch(
                    "{{ route('Crop#create') }}", {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({

                            crop_name: data.crop_name,

                            commission_amount: data.commission_amount,

                            unit: data.unit,

                            quantity_per_basket: data.quantity_per_basket
                        })
                    }
                );


                // =====================================================
                // Validation Error
                // =====================================================

                if (response.status === 422) {

                    const result =
                        await response.json();

                    console.log(
                        'Validation Errors:',
                        result
                    );

                    await Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: result.message ||
                            'ထည့်သွင်းထားသော Data ကို ပြန်လည်စစ်ဆေးပါ။',
                        confirmButtonText: 'အိုကေ'
                    });

                    return false;
                }


                // =====================================================
                // Server Error
                // =====================================================

                if (!response.ok) {

                    const errorText =
                        await response.text();

                    console.error(
                        'Server Error:',
                        errorText
                    );

                    throw new Error(
                        'Server Error: ' +
                        response.status
                    );
                }


                // =====================================================
                // Success Response
                // =====================================================

                const result =
                    await response.json();


                console.log(
                    'Laravel Create Response:',
                    result
                );


                if (!result.success) {

                    throw new Error(
                        result.message ||
                        'Crop Create မအောင်မြင်ပါ။'
                    );
                }


                // =====================================================
                // Laravel မှ ပြန်လာတဲ့ Crop
                // =====================================================

                const crop =
                    result.data;


                if (!crop) {

                    throw new Error(
                        'Laravel Response ထဲမှာ Crop Data မပါပါ။'
                    );
                }


                console.log(
                    '✅ Created Server Crop:',
                    crop
                );


                // =====================================================
                // IndexedDB ထဲ Save
                // =====================================================

                await saveOnlineCropToIndexedDB(
                    crop,
                    data
                );


                // =====================================================
                // Success Message
                // =====================================================

                await Swal.fire({
                    icon: 'success',
                    title: 'အောင်မြင်ပါသည်',
                    text: 'သီးနှံ ထည့်သွင်းပြီးပါပြီ။',
                    confirmButtonText: 'အိုကေ'
                });


                // =====================================================
                // Reset Form
                // =====================================================

                const cropForm =
                    document.getElementById(
                        'cropForm'
                    );

                if (cropForm) {
                    cropForm.reset();
                }


                return true;


            } catch (error) {

                console.error(
                    'Online Create Error:',
                    error
                );


                await Swal.fire({
                    icon: 'error',
                    title: 'ချိတ်ဆက်မှု မအောင်မြင်ပါ',
                    text: error.message ||
                        'Server နှင့် ချိတ်ဆက်ရာတွင် ပြဿနာရှိနေပါသည်။',
                    confirmButtonText: 'အိုကေ'
                });


                return false;
            }
        }


        // Online Create ပြီးရင် IndexedDB ထဲသိမ်းမယ့် function

        async function saveOnlineCropToIndexedDB(
            serverCrop,
            originalData
        ) {

            const database =
                await ensureDB();


            if (!database) {

                throw new Error(
                    'IndexedDB connection မရပါ။'
                );
            }


            return new Promise(function(
                resolve,
                reject
            ) {

                const transaction =
                    database.transaction(
                        'crops',
                        'readwrite'
                    );


                const store =
                    transaction.objectStore(
                        'crops'
                    );


                // =====================================================
                // Laravel မှာ client_uuid မပြန်ရင်
                // original data မှာရှိတာကိုသုံး
                // မရှိရင် အသစ် generate လုပ်
                // =====================================================

                const clientUuid =
                    serverCrop.client_uuid ||
                    originalData.client_uuid ||
                    crypto.randomUUID();


                // =====================================================
                // Server ID
                // =====================================================

                const serverId =
                    Number(
                        serverCrop.id ||
                        serverCrop.server_id
                    );


                if (!serverId) {

                    transaction.abort();

                    reject(
                        new Error(
                            'Server ID မရရှိပါ။'
                        )
                    );

                    return;
                }


                // =====================================================
                // IndexedDB Crop Record
                // =====================================================

                const cropRecord = {

                    // IndexedDB primary key
                    local_id: clientUuid,

                    // Laravel MySQL ID
                    server_id: serverId,

                    // UUID
                    client_uuid: clientUuid,

                    crop_name: serverCrop.crop_name ||
                        originalData.crop_name,

                    commission_amount: serverCrop.commission_amount ||
                        originalData.commission_amount,

                    unit: serverCrop.unit ||
                        originalData.unit,

                    quantity_per_basket: serverCrop.quantity_per_basket ||
                        originalData.quantity_per_basket,

                    sync_status: 'synced',

                    sync_result: 'success',

                    synced_at: new Date().toISOString(),

                    created_at: serverCrop.created_at ||
                        new Date().toISOString(),

                    updated_at: new Date().toISOString()
                };


                console.log(
                    '💾 Saving Online Crop to IndexedDB:',
                    cropRecord
                );


                store.put(
                    cropRecord
                );


                // =====================================================
                // Transaction Complete
                // =====================================================

                transaction.oncomplete =
                    function() {

                        console.log(
                            '✅ Online Created Crop → IndexedDB Saved'
                        );

                        resolve(
                            cropRecord
                        );
                    };


                // =====================================================
                // Error
                // =====================================================

                transaction.onerror =
                    function() {

                        console.error(
                            '❌ IndexedDB Save Error:',
                            transaction.error
                        );

                        reject(
                            transaction.error
                        );
                    };


                transaction.onabort =
                    function() {

                        reject(
                            transaction.error ||
                            new Error(
                                'IndexedDB Transaction Aborted'
                            )
                        );
                    };

            });
        }



        // =========================================================
        // Create Crop Offline
        // =========================================================

        async function createCropOffline(data) {

            // =============================================
            // DB Ready ဖြစ်အောင်လုပ်
            // =============================================

            const database =
                await ensureDB();


            if (!database) {

                throw new Error(
                    'IndexedDB is null'
                );
            }


            return new Promise(
                function(resolve, reject) {

                    try {

                        // =========================================
                        // Transaction
                        // =========================================

                        const transaction =
                            database.transaction(
                                [
                                    'crops',
                                    'sync_queue'
                                ],
                                'readwrite'
                            );


                        // =========================================
                        // Object Stores
                        // =========================================

                        const cropStore =
                            transaction.objectStore(
                                'crops'
                            );


                        const syncStore =
                            transaction.objectStore(
                                'sync_queue'
                            );


                        // =========================================
                        // Generate UUID
                        // =========================================

                        const clientUuid =
                            crypto.randomUUID();


                        const localId =
                            clientUuid;


                        // =========================================
                        // Crop Data
                        // =========================================

                        const crop = {

                            local_id: localId,

                            client_uuid: clientUuid,

                            crop_name: data.crop_name,

                            commission_amount: data.commission_amount,

                            unit: data.unit,

                            quantity_per_basket: data.quantity_per_basket,

                            sync_status: 'pending',

                            created_at: new Date()
                                .toISOString()
                        };


                        // =========================================
                        // Save Crop
                        // =========================================

                        cropStore.add(
                            crop
                        );


                        // =========================================
                        // Add Sync Queue
                        // =========================================

                        syncStore.add({

                            entity: 'crops',

                            action: 'create',

                            client_uuid: clientUuid,

                            data: crop,

                            status: 'pending',

                            created_at: new Date()
                                .toISOString()
                        });


                        // =========================================
                        // Transaction Complete
                        // =========================================

                        transaction.oncomplete =
                            function() {

                                console.log(
                                    'Offline Crop Saved:',
                                    crop
                                );


                                Swal.fire({

                                    icon: 'info',

                                    title: 'အင်တာနက်ချိတ်ဆက်မှု မရှိပါ',

                                    text: 'သီးနှံအချက်အလက်ကို အင်တာနက်မလိုဘဲ သိမ်းဆည်းထားပါပြီ။ အင်တာနက်ပြန်လည်ရရှိသည်နှင့် အလိုအလျောက် ချိတ်ဆက်၍ အချက်အလက်များကို ပေးပို့သွားပါမည်။',

                                    confirmButtonText: 'အိုကေ'
                                });


                                document
                                    .getElementById(
                                        'cropForm'
                                    )
                                    .reset();


                                resolve(true);
                            };


                        // =========================================
                        // Transaction Error
                        // =========================================

                        transaction.onerror =
                            function() {

                                console.error(
                                    'Transaction Error:',
                                    transaction.error
                                );


                                reject(
                                    transaction.error
                                );
                            };


                        // =========================================
                        // Transaction Abort
                        // =========================================

                        transaction.onabort =
                            function() {

                                console.error(
                                    'Transaction Aborted:',
                                    transaction.error
                                );


                                reject(
                                    transaction.error ||
                                    new Error(
                                        'Transaction aborted'
                                    )
                                );
                            };


                    } catch (error) {

                        console.error(
                            'IndexedDB Transaction Error:',
                            error
                        );


                        reject(error);
                    }

                }
            );
        }



        // =========================================================
        // Internet ပြန်ရရင် Auto Sync
        // =========================================================

        // window.addEventListener(
        //     'online',
        //     async function() {

        //         console.log(
        //             'Internet ပြန်ရပါပြီ။'
        //         );


        //         try {

        //             // DB ready ဖြစ်အောင်စောင့်
        //             await ensureDB();


        //             // Auto Sync
        //             await syncOfflineCrops();


        //         } catch (error) {

        //             console.error(
        //                 'Online Sync Error:',
        //                 error
        //             );
        //         }

        //     }
        // );



        // =========================================================
        // Get Pending Crops
        // =========================================================

        // async function getPendingCrops() {

        //     const database =
        //         await ensureDB();


        //     return new Promise(
        //         function(resolve, reject) {

        //             try {

        //                 const transaction =
        //                     database.transaction(
        //                         'sync_queue',
        //                         'readonly'
        //                     );


        //                 const store =
        //                     transaction.objectStore(
        //                         'sync_queue'
        //                     );


        //                 const request =
        //                     store.getAll();


        //                 request.onsuccess =
        //                     function() {

        //                         const pending =
        //                             request.result.filter(
        //                                 function(item) {

        //                                     return (
        //                                         item.entity ===
        //                                         'crops' &&

        //                                         item.status ===
        //                                         'pending'
        //                                     );

        //                                 }
        //                             );


        //                         resolve(
        //                             pending
        //                         );
        //                     };


        //                 request.onerror =
        //                     function() {

        //                         reject(
        //                             request.error
        //                         );
        //                     };


        //             } catch (error) {

        //                 reject(error);
        //             }

        //         }
        //     );
        // }



        // =========================================================
        // Sync Offline Crops
        // =========================================================

        // async function syncOfflineCrops() {

        //     // =============================================
        //     // Internet မရှိရင် မလုပ်
        //     // =============================================

        //     if (!navigator.onLine) {

        //         console.log(
        //             'Offline → Sync မလုပ်ပါ'
        //         );

        //         return;
        //     }


        //     try {

        //         // =============================================
        //         // DB Ready
        //         // =============================================

        //         await ensureDB();


        //         // =============================================
        //         // Get Pending Data
        //         // =============================================

        //         const pending =
        //             await getPendingCrops();


        //         if (pending.length === 0) {

        //             console.log(
        //                 'Sync လုပ်စရာ Data မရှိပါ။'
        //             );

        //             return;
        //         }


        //         console.log(
        //             pending.length +
        //             ' crops syncing...'
        //         );


        //         // =============================================
        //         // Send Laravel API
        //         // =============================================

        //         const response =
        //             await fetch(
        //                 "{{ route('api.sync.crops') }}", {
        //                     method: 'POST',

        //                     headers: {

        //                         'Content-Type': 'application/json',

        //                         'Accept': 'application/json',

        //                         'Authorization': 'Bearer ' +
        //                             localStorage.getItem(
        //                                 'api_token'
        //                             )
        //                     },

        //                     body: JSON.stringify({

        //                         items: pending
        //                     })
        //                 }
        //             );


        //         // =============================================
        //         // API Error
        //         // =============================================

        //         if (!response.ok) {

        //             throw new Error(
        //                 'Sync API Error: ' +
        //                 response.status
        //             );
        //         }


        //         // =============================================
        //         // JSON Result
        //         // =============================================

        //         const result =
        //             await response.json();


        //         console.log(
        //             'Sync API Result:',
        //             result
        //         );


        //         // =============================================
        //         // Update IndexedDB
        //         // =============================================

        //         if (result.success) {

        //             await markCropsAsSynced(
        //                 result.results
        //             );


        //             console.log(
        //                 'Crop Sync Success'
        //             );


        //             // ==========================================
        //             // Count Result
        //             // ==========================================

        //             const results =
        //                 result.results || [];


        //             const successItems =
        //                 results.filter(
        //                     function(item) {

        //                         return (
        //                             item.status ===
        //                             'success'
        //                         );

        //                     }
        //                 );


        //             const duplicateItems =
        //                 results.filter(
        //                     function(item) {

        //                         return (
        //                             item.status ===
        //                             'duplicate'
        //                         );

        //                     }
        //                 );


        //             const alreadySyncedItems =
        //                 results.filter(
        //                     function(item) {

        //                         return (
        //                             item.status ===
        //                             'already_synced'
        //                         );

        //                     }
        //                 );


        //             const failedItems =
        //                 results.filter(
        //                     function(item) {

        //                         return (
        //                             item.status ===
        //                             'failed'
        //                         );

        //                     }
        //                 );


        //             // ==========================================
        //             // Success
        //             // ==========================================

        //             if (
        //                 successItems.length > 0
        //             ) {

        //                 Swal.fire({

        //                     icon: 'success',

        //                     title: 'အောင်မြင်ပါသည်',

        //                     text: successItems.length +
        //                         ' ခုသော သီးနှံအချက်အလက်များကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။',

        //                     confirmButtonText: 'အိုကေ'
        //                 });
        //             }


        //             // ==========================================
        //             // Duplicate
        //             // ==========================================
        //             else if (
        //                 duplicateItems.length > 0
        //             ) {

        //                 Swal.fire({

        //                     icon: 'warning',

        //                     title: 'သီးနှံအမည် ထပ်နေပါသည်',

        //                     text: 'သီးနှံအချက်အလက်သည် Database တွင် ရှိပြီးသားဖြစ်သောကြောင့် သိမ်းဆည်းခြင်းမပြုပါ။',

        //                     confirmButtonText: 'အိုကေ'
        //                 });
        //             }


        //             // ==========================================
        //             // Already Synced
        //             // ==========================================
        //             else if (
        //                 alreadySyncedItems.length > 0
        //             ) {

        //                 console.log(
        //                     alreadySyncedItems.length +
        //                     ' crops already synced'
        //                 );
        //             }


        //             // ==========================================
        //             // Failed
        //             // ==========================================

        //             if (
        //                 failedItems.length > 0
        //             ) {

        //                 console.warn(
        //                     'Some crops failed to sync:',
        //                     failedItems
        //                 );
        //             }

        //         }


        //     } catch (error) {

        //         console.error(
        //             'Sync Error:',
        //             error
        //         );

        //         /*
        //          * Internet ပြန်ပြတ်သွားရင်
        //          * Data ကို pending အတိုင်းထားမယ်
        //          */
        //     }
        // }



        // =========================================================
        // Mark Crops As Synced
        // =========================================================

        // async function markCropsAsSynced(results) {

        //     const database =
        //         await ensureDB();


        //     return new Promise(
        //         function(resolve, reject) {

        //             try {

        //                 const transaction =
        //                     database.transaction(
        //                         [
        //                             'crops',
        //                             'sync_queue'
        //                         ],
        //                         'readwrite'
        //                     );


        //                 const cropStore =
        //                     transaction.objectStore(
        //                         'crops'
        //                     );


        //                 const syncStore =
        //                     transaction.objectStore(
        //                         'sync_queue'
        //                     );


        //                 // =========================================
        //                 // Get Local Crops
        //                 // =========================================

        //                 const cropRequest =
        //                     cropStore.getAll();


        //                 cropRequest.onsuccess =
        //                     function() {

        //                         const crops =
        //                             cropRequest.result;


        //                         results.forEach(
        //                             function(result) {

        //                                 // Success / Duplicate /
        //                                 // Already Synced
        //                                 if (
        //                                     result.status !==
        //                                     'success' &&

        //                                     result.status !==
        //                                     'duplicate' &&

        //                                     result.status !==
        //                                     'already_synced'
        //                                 ) {

        //                                     return;
        //                                 }


        //                                 crops.forEach(
        //                                     function(crop) {

        //                                         if (
        //                                             crop.client_uuid ===
        //                                             result.client_uuid
        //                                         ) {

        //                                             crop.sync_status =
        //                                                 'synced';


        //                                             crop.sync_result =
        //                                                 result.status;


        //                                             crop.synced_at =
        //                                                 new Date()
        //                                                 .toISOString();


        //                                             cropStore.put(
        //                                                 crop
        //                                             );
        //                                         }

        //                                     }
        //                                 );

        //                             }
        //                         );

        //                     };


        //                 cropRequest.onerror =
        //                     function() {

        //                         reject(
        //                             cropRequest.error
        //                         );
        //                     };


        //                 // =========================================
        //                 // Update Sync Queue
        //                 // =========================================

        //                 const queueRequest =
        //                     syncStore.getAll();


        //                 queueRequest.onsuccess =
        //                     function() {

        //                         const items =
        //                             queueRequest.result;


        //                         results.forEach(
        //                             function(result) {

        //                                 if (
        //                                     result.status !==
        //                                     'success' &&

        //                                     result.status !==
        //                                     'duplicate' &&

        //                                     result.status !==
        //                                     'already_synced'
        //                                 ) {

        //                                     return;
        //                                 }


        //                                 items.forEach(
        //                                     function(item) {

        //                                         if (
        //                                             item.client_uuid ===
        //                                             result.client_uuid
        //                                         ) {

        //                                             item.status =
        //                                                 'synced';


        //                                             item.sync_result =
        //                                                 result.status;


        //                                             item.synced_at =
        //                                                 new Date()
        //                                                 .toISOString();


        //                                             syncStore.put(
        //                                                 item
        //                                             );
        //                                         }

        //                                     }
        //                                 );

        //                             }
        //                         );

        //                     };


        //                 queueRequest.onerror =
        //                     function() {

        //                         reject(
        //                             queueRequest.error
        //                         );
        //                     };


        //                 // =========================================
        //                 // Transaction Complete
        //                 // =========================================

        //                 transaction.oncomplete =
        //                     function() {

        //                         resolve(
        //                             true
        //                         );
        //                     };


        //                 // =========================================
        //                 // Transaction Error
        //                 // =========================================

        //                 transaction.onerror =
        //                     function() {

        //                         reject(
        //                             transaction.error
        //                         );
        //                     };


        //                 // =========================================
        //                 // Transaction Abort
        //                 // =========================================

        //                 transaction.onabort =
        //                     function() {

        //                         reject(
        //                             transaction.error ||
        //                             new Error(
        //                                 'Transaction aborted'
        //                             )
        //                         );
        //                     };

        //             } catch (error) {

        //                 reject(error);
        //             }

        //         }
        //     );
        // }
    </script>
@endsection
