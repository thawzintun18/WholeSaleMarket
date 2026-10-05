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
        //IndexedDB Setup
        const DB_NAME = 'WholeSaleMarketDB';
        const DB_VERSION = 1;

        let db = null;


        // ======================================
        // Open IndexedDB
        // ======================================

        const request = indexedDB.open(
            DB_NAME,
            DB_VERSION
        );


        request.onupgradeneeded = function(event) {

            db = event.target.result;


            // ================================
            // Crops Store
            // ================================

            if (!db.objectStoreNames.contains('crops')) {

                const cropStore =
                    db.createObjectStore(
                        'crops', {
                            keyPath: 'local_id'
                        }
                    );

                cropStore.createIndex(
                    'client_uuid',
                    'client_uuid', {
                        unique: true
                    }
                );

                cropStore.createIndex(
                    'sync_status',
                    'sync_status', {
                        unique: false
                    }
                );
            }


            // ================================
            // Sync Queue
            // ================================

            if (!db.objectStoreNames.contains('sync_queue')) {

                const syncStore =
                    db.createObjectStore(
                        'sync_queue', {
                            keyPath: 'id',
                            autoIncrement: true
                        }
                    );

                syncStore.createIndex(
                    'status',
                    'status', {
                        unique: false
                    }
                );

                syncStore.createIndex(
                    'client_uuid',
                    'client_uuid', {
                        unique: false
                    }
                );
            }
        };


        request.onsuccess = function(event) {

            db = event.target.result;

            console.log(
                'IndexedDB Connected'
            );

            // Page ဖွင့်တဲ့အချိန်မှာလည်း
            // pending data ရှိရင် sync စစ်မယ်
            if (navigator.onLine) {

                syncOfflineCrops();
            }
        };


        request.onerror = function(event) {

            console.error(
                'IndexedDB Error:',
                event.target.error
            );
        };


        //Form Submit ကို ဖမ်းမယ်
        document
            .getElementById('cropForm')
            .addEventListener(
                'submit',
                async function(event) {

                    event.preventDefault();


                    // ==================================
                    // Form Data
                    // ==================================

                    const cropData = {

                        crop_name: document.getElementById(
                            'crop_name'
                        ).value.trim(),

                        commission_amount: document.getElementById(
                            'commission_amount'
                        ).value,

                        unit: document.getElementById(
                            'unit'
                        ).value,

                        quantity_per_basket: document.getElementById(
                            'quantity_per_basket'
                        ).value
                    };


                    // ==================================
                    // Check Online / Offline
                    // ==================================

                    if (navigator.onLine) {

                        console.log(
                            'ONLINE → Laravel'
                        );

                        await createCropOnline(
                            cropData
                        );

                    } else {

                        console.log(
                            'OFFLINE → IndexedDB'
                        );

                        // const isUnique =
                        //     await checkOfflineCropNameUnique(
                        //         cropData.crop_name
                        //     );

                        // if (!isUnique) {

                        //     Swal.fire({
                        //         icon: 'warning',
                        //         title: 'သတိပြုရန်',
                        //         text: 'ဤသီးနှံအမည်ကို ထည့်သွင်းပြီးသားဖြစ်ပါသည်။',
                        //         confirmButtonText: 'အိုကေ'
                        //     });

                        //     return;
                        // }

                        await createCropOffline(
                            cropData
                        );
                    }

                }
            );

        //IndexedDB မှာ Crop Name ရှိ/မရှိစစ်မယ့် Function
        // function checkOfflineCropNameUnique(cropName) {

        //     return new Promise(function(resolve, reject) {

        //         const transaction =
        //             db.transaction(
        //                 'crops',
        //                 'readonly'
        //             );

        //         const store =
        //             transaction.objectStore(
        //                 'crops'
        //             );

        //         const request =
        //             store.getAll();


        //         request.onsuccess =
        //             function() {

        //                 const crops =
        //                     request.result;


        //                 const exists =
        //                     crops.some(function(crop) {

        //                         return (
        //                             crop.crop_name
        //                             .trim()
        //                             .toLowerCase() ===
        //                             cropName
        //                             .trim()
        //                             .toLowerCase()
        //                         );

        //                     });


        //                 // ရှိပြီးသားဆို false
        //                 // မရှိသေးရင် true

        //                 resolve(!exists);

        //             };


        //         request.onerror =
        //             function() {

        //                 reject(
        //                     request.error
        //                 );

        //             };

        //     });

        // }

        //Online ဖြစ်ရင် Laravel Controller ကိုပို့မယ်
        async function createCropOnline(data) {
            try {

                // console.log(data);


                const response =
                    await fetch(
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


                if (response.ok) {

                    Swal.fire({
                        icon: 'success',
                        title: 'အောင်မြင်ပါသည်',
                        text: 'သီးနှံ ထည့်သွင်းပြီးပါပြီ။',
                        confirmButtonText: 'အိုကေ'
                    });


                    document
                        .getElementById('cropForm')
                        .reset();


                    return;
                }


                // Laravel validation error
                if (response.status === 422) {

                    const errors =
                        await response.json();

                    console.log(errors);

                    Swal.fire({
                        icon: 'warning',
                        title: 'သတိပြုရန်',
                        text: 'ထည့်သွင်းထားသော Data ကို ပြန်လည်စစ်ဆေးပါ။',
                        confirmButtonText: 'အိုကေ'
                    });

                    return;
                }


                throw new Error(
                    'Server Error'
                );


            } catch (error) {

                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'ချိတ်ဆက်မှု မအောင်မြင်ပါ',
                    text: 'Server နှင့် ချိတ်ဆက်ရာတွင် ပြဿနာရှိနေပါသည်။',
                    confirmButtonText: 'အိုကေ'
                });
            }
        }


        //Offline ဖြစ်ရင် IndexedDB ထဲ Create
        function createCropOffline(data) {
            return new Promise(
                function(resolve, reject) {

                    const transaction =
                        db.transaction(
                            [
                                'crops',
                                'sync_queue'
                            ],
                            'readwrite'
                        );


                    const cropStore =
                        transaction.objectStore(
                            'crops'
                        );


                    const syncStore =
                        transaction.objectStore(
                            'sync_queue'
                        );


                    // =================================
                    // Generate UUID
                    // =================================

                    const clientUuid =
                        crypto.randomUUID();


                    const localId =
                        clientUuid;


                    // =================================
                    // Crop Data
                    // =================================

                    const crop = {

                        local_id: localId,

                        client_uuid: clientUuid,

                        crop_name: data.crop_name,

                        commission_amount: data.commission_amount,

                        unit: data.unit,

                        quantity_per_basket: data.quantity_per_basket,

                        sync_status: 'pending',

                        created_at: new Date().toISOString()
                    };


                    // =================================
                    // Save Crop
                    // =================================

                    cropStore.add(crop);


                    // =================================
                    // Add Sync Queue
                    // =================================

                    syncStore.add({

                        entity: 'crops',

                        action: 'create',

                        client_uuid: clientUuid,

                        data: crop,

                        status: 'pending',

                        created_at: new Date().toISOString()
                    });


                    transaction.oncomplete =
                        function() {

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


                    transaction.onerror =
                        function() {

                            console.error(
                                transaction.error
                            );

                            reject(
                                transaction.error
                            );
                        };

                }
            );
        }

        //Internet ပြန်ရရင် Auto Sync
        window.addEventListener(
            'online',
            function() {

                console.log(
                    'Internet ပြန်ရပါပြီ။'
                );


                // User ဘာမှနှိပ်စရာမလို
                syncOfflineCrops();
            }
        );


        //Pending Crop တွေ ရှာမယ်
        function getPendingCrops() {
            return new Promise(
                function(resolve, reject) {

                    const transaction =
                        db.transaction(
                            'sync_queue',
                            'readonly'
                        );


                    const store =
                        transaction.objectStore(
                            'sync_queue'
                        );


                    const request =
                        store.getAll();


                    request.onsuccess =
                        function() {

                            const pending =
                                request.result.filter(
                                    function(item) {

                                        return (
                                            item.entity ===
                                            'crops' &&
                                            item.status ===
                                            'pending'
                                        );
                                    }
                                );


                            resolve(pending);
                        };


                    request.onerror =
                        function() {

                            reject(
                                request.error
                            );
                        };

                }
            );
        }


        //JavaScript က Laravel API ကို Auto ပို့မယ်
        async function syncOfflineCrops() {
            // Internet မရှိရင် မလုပ်
            if (!navigator.onLine) {

                return;
            }


            try {

                // ===========================
                // Get Pending Data
                // ===========================

                const pending =
                    await getPendingCrops();


                if (pending.length === 0) {

                    console.log(
                        'Sync လုပ်စရာ Data မရှိပါ။'
                    );

                    return;
                }


                console.log(
                    pending.length +
                    ' crops syncing...'
                );


                // ===========================
                // Send to Laravel
                // ===========================

                const response =
                    await fetch(
                        "{{ route('api.sync.crops') }}", {
                            method: 'POST',

                            headers: {

                                'Content-Type': 'application/json',

                                'Accept': 'application/json',

                                'Authorization': 'Bearer ' +
                                    localStorage.getItem(
                                        'api_token'
                                    )
                            },

                            body: JSON.stringify({

                                items: pending
                            })
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'Sync API Error'
                    );
                }


                const result =
                    await response.json();


                // ===========================
                // Update IndexedDB
                // ===========================

                if (result.success) {

                    await markCropsAsSynced(
                        result.results
                    );


                    console.log(
                        'Crop Sync Success'
                    );


                    Swal.fire({
                        icon: 'success',
                        title: 'အောင်မြင်ပါသည်',
                        text: 'အင်တာနက်မရှိချိန်တွင် သိမ်းဆည်းထားသော သီးနှံအချက်အလက်များကို အလိုအလျောက် ပေးပို့သိမ်းဆည်းပြီးပါပြီ။',
                        confirmButtonText: 'အိုကေ'
                    });
                }


            } catch (error) {

                console.error(
                    'Sync Error:',
                    error
                );

                /*
                |--------------------------------------------------------------------------
                | Internet ပြန်ပြတ်သွားရင်
                | Data ကို pending အတိုင်းထားမယ်
                |--------------------------------------------------------------------------
                */

            }
        }


        //Sync ပြီးရင် IndexedDB မှာ synced ပြောင်းမယ်
        function markCropsAsSynced(results) {
            return new Promise(
                function(resolve, reject) {

                    const transaction =
                        db.transaction(
                            [
                                'crops',
                                'sync_queue'
                            ],
                            'readwrite'
                        );


                    const cropStore =
                        transaction.objectStore(
                            'crops'
                        );


                    const syncStore =
                        transaction.objectStore(
                            'sync_queue'
                        );


                    // ============================
                    // Get all local crops
                    // ============================

                    const cropRequest =
                        cropStore.getAll();


                    cropRequest.onsuccess =
                        function() {

                            const crops =
                                cropRequest.result;


                            results.forEach(
                                function(result) {

                                    if (
                                        result.status !==
                                        'success'
                                    ) {
                                        return;
                                    }


                                    crops.forEach(
                                        function(crop) {

                                            if (
                                                crop.client_uuid ===
                                                result.client_uuid
                                            ) {

                                                crop.sync_status =
                                                    'synced';

                                                cropStore.put(
                                                    crop
                                                );
                                            }
                                        }
                                    );

                                }
                            );
                        };


                    // ============================
                    // Update Sync Queue
                    // ============================

                    const queueRequest =
                        syncStore.getAll();


                    queueRequest.onsuccess =
                        function() {

                            const items =
                                queueRequest.result;


                            results.forEach(
                                function(result) {

                                    if (
                                        result.status !==
                                        'success'
                                    ) {
                                        return;
                                    }


                                    items.forEach(
                                        function(item) {

                                            if (
                                                item.client_uuid ===
                                                result.client_uuid
                                            ) {

                                                item.status =
                                                    'synced';

                                                item.synced_at =
                                                    new Date()
                                                    .toISOString();

                                                syncStore.put(
                                                    item
                                                );
                                            }
                                        }
                                    );

                                }
                            );
                        };


                    transaction.oncomplete =
                        function() {

                            resolve(true);
                        };


                    transaction.onerror =
                        function() {

                            reject(
                                transaction.error
                            );
                        };

                }
            );
        }
    </script>
@endsection
