@extends('wholeSaleMarket.layout.main')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon"><i class="bi bi-flower1"></i></span>
                <div>
                    <h1 class="h3 mb-1">🌱 သီးနှံအချက်အလက်များကိုပြင်ဆင်ရန်</h1>
                    <div class=" d-flex mt-3">
                        <a href="{{ route('WholeSaleMarket#dashboard') }}" class=" mx-2">
                            <small>ပင်မစာမျက်နှာ</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <a href="{{ route('Crop#list') }}" class=" mx-2">
                            <small>သီးနှံစာရင်း</small>
                        </a>
                        <i class="bi bi-arrow-right font-weight-bolder"></i>
                        <p class=" mx-2">သီးနှံအချက်အလက်များကိုပြင်ဆင်ရန်</p>

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
                            သီးနှံအချက်အလက်များကိုပြင်ဆင်ရန်
                        </h5>
                        <div class="">
                            <a href="{{ route('Crop#list') }}"
                                class=" btn btn-sm btn-outline-dark text-white border-white">နောက်သို့</a>
                        </div>
                    </div>

                </div>


                <!-- Form -->
                <div class="crop-form-body">

                    <form id="editCropForm" action="" method="">

                        @csrf

                        {{-- <input type="hidden" id="crop_id" value="{{ $crop->id }}"> --}}

                        <input type="hidden" id="edit_crop_id" value="{{ $crop->id }}" name="crop_id">

                        <input type="hidden" id="edit_server_id" value="{{ $crop->id }}" name="server_id">

                        <input type="hidden" id="edit_client_uuid" value="{{ $crop->client_uuid }}" name="client_uuid">

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

                                    <input type="text" name="crop_name" id="edit_crop_name"
                                        value="{{ old('crop_name', $crop->crop_name) }}"
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

                                    <input type="number" name="commission_amount" id="edit_commission_amount"
                                        value="{{ old('commission_amount', $crop->commission_amount) }}" min="0"
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

                                    <input type="number" name="quantity_per_basket" id="edit_quantity_per_basket"
                                        value="{{ old('quantity_per_basket', $crop->quantity_per_basket) }}" min="1"
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

                                    <select name="unit" id="edit_unit"
                                        class="form-select @error('unit') is-invalid @enderror">

                                        <option value="" disabled {{ old('unit') ? '' : 'selected' }}>
                                            ယူနစ်ရွေးချယ်ပါ
                                        </option>

                                        <option value="viss" {{ old('unit', $crop->unit) == 'viss' ? 'selected' : '' }}>
                                            ပိဿာ
                                        </option>

                                        <option value="pound" {{ old('unit', $crop->unit) == 'pound' ? 'selected' : '' }}>
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
                                သီးနှံအချက်အလက်များကိုပြင်ဆင်မည်
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
        // const DB_NAME = 'WholeSaleMarketDB';
        // const DB_VERSION = 3;

        // let db = null;

        // DB ဖွင့်နေချိန်မှာ request များစွာ မဖွင့်အောင်
        // let dbPromise = null;

        // async function openDatabaseUpdate() {

        //     if (db) {
        //         return db;
        //     }

        //     if (dbPromise) {
        //         return dbPromise;
        //     }

        //     dbPromise = new Promise((resolve, reject) => {

        //         const request = indexedDB.open(DB_NAME, DB_VERSION);

        //         request.onsuccess = function(event) {

        //             db = event.target.result;

        //             console.log('IndexedDB Connected');

        //             resolve(db);
        //         };

        //         request.onerror = function(event) {

        //             console.error(
        //                 'IndexedDB Error:',
        //                 event.target.error
        //             );

        //             dbPromise = null;

        //             reject(event.target.error);
        //         };

        //     });

        //     return dbPromise;
        // }

        // =========================================================
        // Ensure IndexedDB Connection
        // =========================================================

        // async function ensureDBUpdate() {

        //     // DB ဖွင့်ပြီးသားဆိုရင်
        //     if (db) {
        //         return db;
        //     }

        //     // DB မဖွင့်ရသေးရင် Open လုပ်မယ်
        //     return await openDatabaseUpdate();
        // }

        //form submit
        document.addEventListener('DOMContentLoaded', function() {

            const editForm =
                document.getElementById('editCropForm');

            if (!editForm) {

                console.error(
                    'editCropForm မတွေ့ပါ။'
                );

                return;
            }


            editForm.addEventListener('submit', async function(event) {

                event.preventDefault();

                try {

                    const form = editForm;


                    // ==========================================
                    // Get Data
                    // ==========================================

                    const data = {

                        server_id: form.querySelector(
                            '#edit_server_id'
                        )?.value.trim() || null,

                        client_uuid: form.querySelector(
                            '#edit_client_uuid'
                        )?.value.trim() || null,

                        crop_name: form.querySelector(
                            '#edit_crop_name'
                        )?.value.trim() || '',

                        commission_amount: form.querySelector(
                            '#edit_commission_amount'
                        )?.value || '',

                        unit: form.querySelector(
                            '#edit_unit'
                        )?.value || '',

                        quantity_per_basket: form.querySelector(
                            '#edit_quantity_per_basket'
                        )?.value || ''

                    };


                    console.log(
                        '========== CROP UPDATE =========='
                    );

                    console.log(
                        'UPDATE DATA:',
                        data
                    );


                    // ==========================================
                    // Validation
                    // ==========================================

                    if (!data.server_id) {

                        throw new Error(
                            'Server ID မရှိပါ။'
                        );
                    }


                    if (!data.client_uuid) {

                        throw new Error(
                            'Client UUID မရှိပါ။'
                        );
                    }


                    if (!data.crop_name) {

                        await Swal.fire({
                            icon: 'warning',
                            title: 'သတိပြုရန်',
                            text: 'သီးနှံအမည် ထည့်ပေးပါ။',
                            confirmButtonText: 'အိုကေ'
                        });

                        return;
                    }


                    if (data.commission_amount === '') {

                        await Swal.fire({
                            icon: 'warning',
                            title: 'သတိပြုရန်',
                            text: 'ပွဲခ ထည့်ပေးပါ။',
                            confirmButtonText: 'အိုကေ'
                        });

                        return;
                    }


                    if (!data.unit) {

                        await Swal.fire({
                            icon: 'warning',
                            title: 'သတိပြုရန်',
                            text: 'ယူနစ် ရွေးချယ်ပါ။',
                            confirmButtonText: 'အိုကေ'
                        });

                        return;
                    }


                    if (data.quantity_per_basket === '') {

                        await Swal.fire({
                            icon: 'warning',
                            title: 'သတိပြုရန်',
                            text: 'တစ်တင်းတွင်ပါဝင်သော အရေအတွက် ထည့်ပေးပါ။',
                            confirmButtonText: 'အိုကေ'
                        });

                        return;
                    }


                    // ==========================================
                    // ONLINE
                    // ==========================================

                    if (navigator.onLine) {

                        console.log(
                            '🌐 ONLINE → Laravel Update'
                        );

                        await updateCropOnline(data);

                        return;
                    }


                    // ==========================================
                    // OFFLINE
                    // ==========================================

                    console.log(
                        '📴 OFFLINE → IndexedDB Update'
                    );

                    await updateCropOffline(data);


                } catch (error) {

                    console.error(
                        'Crop Update Error:',
                        error
                    );

                    Swal.fire({
                        icon: 'error',
                        title: 'Update မအောင်မြင်ပါ',
                        text: error.message ||
                            'Update ပြုလုပ်ရာတွင် ပြဿနာရှိနေပါသည်။',
                        confirmButtonText: 'အိုကေ'
                    });

                }

            });

        });

        async function updateCropOnline(data) {

            try {

                console.log(
                    '🌐 Sending Crop Update:',
                    data
                );


                const response = await fetch(
                    "/WholeSaleMarket/Crop/update/" +
                    data.server_id, {

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


                const result =
                    await response.json();


                console.log(
                    'Laravel Update Response:',
                    result
                );


                // ==========================================
                // Validation Error
                // ==========================================

                if (response.status === 422) {

                    let message =
                        result.message ||
                        'Data ကို ပြန်လည်စစ်ဆေးပါ။';


                    if (result.errors) {

                        message =
                            Object.values(
                                result.errors
                            )
                            .flat()
                            .join('\n');

                    }


                    await Swal.fire({

                        icon: 'warning',

                        title: 'သတိပြုရန်',

                        text: message,

                        confirmButtonText: 'အိုကေ'

                    });

                    return false;
                }


                // ==========================================
                // Other Error
                // ==========================================

                if (!response.ok) {

                    throw new Error(
                        result.message ||
                        'Server Update Error'
                    );

                }


                // ==========================================
                // Success
                // ==========================================

                if (result.success) {

                    console.log(
                        '✅ Laravel Crop Update Success'
                    );


                    // IndexedDB ထဲက data ကို
                    // server_id နဲ့ရှာပြီး update

                    await updateLocalCropAfterOnlineUpdate(
                        data
                    );


                    await Swal.fire({

                        icon: 'success',

                        title: 'အောင်မြင်ပါသည်',

                        text: result.message ||
                            'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

                        confirmButtonText: 'အိုကေ'

                    });


                    location.reload();

                    return true;
                }


                throw new Error(
                    result.message ||
                    'Update မအောင်မြင်ပါ။'
                );


            } catch (error) {

                console.error(
                    'Online Crop Update Error:',
                    error
                );


                await Swal.fire({

                    icon: 'error',

                    title: 'Update မအောင်မြင်ပါ',

                    text: error.message ||
                        'Server နှင့် ဆက်သွယ်၍မရပါ။',

                    confirmButtonText: 'အိုကေ'

                });

                return false;
            }
        }

        async function updateLocalCropAfterOnlineUpdate(data) {

            const database =
                await ensureDBUpdate();


            if (!database) {

                throw new Error(
                    'IndexedDB connection မရပါ။'
                );

            }


            return new Promise(
                function(resolve, reject) {

                    const transaction =
                        database.transaction(
                            'crops',
                            'readwrite'
                        );


                    const store =
                        transaction.objectStore(
                            'crops'
                        );


                    // ==========================================
                    // server_id index
                    // ==========================================

                    const serverIndex =
                        store.index('server_id');


                    const request =
                        serverIndex.get(
                            Number(data.server_id)
                        );


                    request.onsuccess =
                        function() {

                            const crop =
                                request.result;


                            if (!crop) {

                                console.log(
                                    'IndexedDB မှာ server_id = ' +
                                    data.server_id +
                                    ' Crop မတွေ့ပါ။'
                                );

                                // IndexedDB မှာ မရှိလည်း
                                // Laravel update က အောင်မြင်ပြီးသားဖြစ်လို့
                                // Error မပေးဘဲ complete လုပ်မယ်

                                return;
                            }


                            console.log(
                                'IndexedDB Crop Found:',
                                crop
                            );


                            // ======================================
                            // Update Local Data
                            // ======================================

                            crop.server_id =
                                Number(data.server_id);

                            crop.client_uuid =
                                data.client_uuid;

                            crop.crop_name =
                                data.crop_name;

                            crop.commission_amount =
                                data.commission_amount;

                            crop.unit =
                                data.unit;

                            crop.quantity_per_basket =
                                data.quantity_per_basket;


                            // ======================================
                            // Sync Status
                            // ======================================

                            crop.sync_status =
                                'synced';

                            crop.sync_result =
                                'success';

                            crop.updated_at =
                                new Date().toISOString();


                            // ======================================
                            // Save
                            // ======================================

                            store.put(crop);

                        };


                    request.onerror =
                        function() {

                            reject(
                                request.error
                            );

                        };


                    transaction.oncomplete =
                        function() {

                            console.log(
                                '✅ IndexedDB Local Crop Updated'
                            );

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


        // =========================================================
        // OFFLINE CROP UPDATE
        // local_id မသုံးပါ
        // server_id → client_uuid fallback
        // =========================================================

        async function updateCropOffline(data) {

            const database = await ensureDBUpdate();

            if (!database) {
                throw new Error('IndexedDB connection မရပါ။');
            }

            return new Promise(function(resolve, reject) {

                let transaction;

                try {

                    transaction = database.transaction(
                        ['crops', 'sync_queue'],
                        'readwrite'
                    );

                    const cropStore =
                        transaction.objectStore('crops');

                    const syncStore =
                        transaction.objectStore('sync_queue');


                    // =====================================================
                    // server_id နဲ့ရှာမယ်
                    // =====================================================

                    const serverIndex =
                        cropStore.index('server_id');

                    const cropRequest =
                        serverIndex.get(Number(data.server_id));


                    cropRequest.onsuccess = function() {

                        let crop = cropRequest.result;


                        // =================================================
                        // server_id နဲ့ မတွေ့ရင် client_uuid နဲ့ရှာမယ်
                        // =================================================

                        if (!crop) {

                            console.log(
                                'server_id = ' +
                                data.server_id +
                                ' နဲ့ Crop မတွေ့ပါ။'
                            );

                            console.log(
                                'client_uuid နဲ့ ပြန်ရှာနေပါတယ်...'
                            );


                            if (!data.client_uuid) {

                                transaction.abort();

                                reject(
                                    new Error(
                                        'Client UUID မရှိပါ။'
                                    )
                                );

                                return;
                            }


                            const uuidIndex =
                                cropStore.index('client_uuid');


                            const uuidRequest =
                                uuidIndex.get(
                                    data.client_uuid
                                );


                            uuidRequest.onsuccess =
                                function() {

                                    crop =
                                        uuidRequest.result;


                                    if (!crop) {

                                        console.error(
                                            'IndexedDB မှာ Crop မတွေ့ပါ။'
                                        );

                                        transaction.abort();

                                        reject(
                                            new Error(
                                                'Offline Update လုပ်ရန် IndexedDB မှာ Crop မတွေ့ပါ။'
                                            )
                                        );

                                        return;
                                    }


                                    console.log(
                                        '📦 Crop ကို client_uuid နဲ့တွေ့ပါပြီ:',
                                        crop
                                    );


                                    // ⭐ data ပါရမယ်
                                    updateCropRecord(
                                        crop,
                                        cropStore,
                                        syncStore,
                                        data
                                    );
                                };


                            uuidRequest.onerror =
                                function() {

                                    transaction.abort();

                                    reject(
                                        uuidRequest.error
                                    );
                                };


                            return;
                        }


                        // =================================================
                        // server_id နဲ့တွေ့ပြီ
                        // =================================================

                        console.log(
                            '📦 Crop ကို server_id နဲ့တွေ့ပါပြီ:',
                            crop
                        );


                        // ⭐ ဒီနေရာမှာလည်း data ပါရမယ်
                        updateCropRecord(
                            crop,
                            cropStore,
                            syncStore,
                            data
                        );
                    };


                    cropRequest.onerror =
                        function() {

                            transaction.abort();

                            reject(
                                cropRequest.error
                            );
                        };


                    // =====================================================
                    // Transaction Complete
                    // =====================================================

                    transaction.oncomplete =
                        async function() {

                            console.log(
                                '✅ Offline Crop Update Saved'
                            );


                            await Swal.fire({

                                icon: 'info',

                                title: 'အင်တာနက်ချိတ်ဆက်မှု မရှိပါ',

                                text: 'သီးနှံအချက်အလက်ကို Offline အဖြစ် ပြင်ဆင်သိမ်းဆည်းထားပါပြီ။ ' +
                                    'အင်တာနက်ပြန်ရရှိသည်နှင့် Server သို့ အလိုအလျောက် Update လုပ်ပေးပါမည်။',

                                confirmButtonText: 'အိုကေ'

                            });


                            resolve(true);
                        };


                    // =====================================================
                    // Transaction Error
                    // =====================================================

                    transaction.onerror =
                        function() {

                            console.error(
                                '❌ Offline Update Transaction Error:',
                                transaction.error
                            );

                            reject(
                                transaction.error ||
                                new Error(
                                    'Offline Update Transaction Error'
                                )
                            );
                        };


                    // =====================================================
                    // Transaction Abort
                    // =====================================================

                    transaction.onabort =
                        function() {

                            console.error(
                                '❌ Offline Update Transaction Aborted'
                            );
                        };


                } catch (error) {

                    reject(error);
                }
            });
        }

        // =========================================================
        // Update Crop Record + Sync Queue
        // =========================================================

        // =========================================================
        // UPDATE CROP RECORD
        // =========================================================

        function updateCropRecord(
            crop,
            cropStore,
            syncStore,
            data
        ) {

            // =====================================================
            // Debug
            // =====================================================

            console.log(
                '========== updateCropRecord =========='
            );

            console.log(
                'Crop:',
                crop
            );

            console.log(
                'Update Data:',
                data
            );


            // =====================================================
            // Data မပါရင် ချက်ချင်းရပ်
            // =====================================================

            if (!data) {

                throw new Error(
                    'updateCropRecord() ထဲကို update data မရောက်ပါ။'
                );
            }


            if (!data.server_id) {

                throw new Error(
                    'Update Data ထဲမှာ server_id မရှိပါ။'
                );
            }


            // =====================================================
            // Client UUID
            // =====================================================

            const clientUuid =
                crop.client_uuid ||
                data.client_uuid;


            if (!clientUuid) {

                throw new Error(
                    'Client UUID မရှိပါ။'
                );
            }


            // =====================================================
            // Update Crop
            // =====================================================

            crop.server_id =
                Number(data.server_id);

            crop.client_uuid =
                clientUuid;

            crop.crop_name =
                data.crop_name;

            crop.commission_amount =
                data.commission_amount;

            crop.unit =
                data.unit;

            crop.quantity_per_basket =
                data.quantity_per_basket;

            crop.sync_status =
                'pending';

            crop.sync_result =
                null;

            crop.synced_at =
                null;

            crop.updated_at =
                new Date().toISOString();


            console.log(
                '📝 Updated Local Crop:',
                crop
            );


            // =====================================================
            // Save Crop
            // =====================================================

            cropStore.put(crop);


            // =====================================================
            // Sync Queue ရှာမယ်
            // =====================================================

            const queueIndex =
                syncStore.index('client_uuid');


            const queueRequest =
                queueIndex.getAll(clientUuid);


            queueRequest.onsuccess =
                function() {

                    const queueItems =
                        queueRequest.result;


                    const existingUpdate =
                        queueItems.find(
                            function(item) {

                                return (
                                    item.entity === 'crops' &&
                                    item.action === 'update' &&
                                    item.status === 'pending'
                                );
                            }
                        );


                    // =================================================
                    // Queue Data
                    // =================================================

                    const queueData = {

                        entity: 'crops',

                        action: 'update',

                        client_uuid: clientUuid,

                        server_id: Number(data.server_id),

                        data: {

                            server_id: Number(data.server_id),

                            client_uuid: clientUuid,

                            crop_name: data.crop_name,

                            commission_amount: data.commission_amount,

                            unit: data.unit,

                            quantity_per_basket: data.quantity_per_basket
                        },

                        status: 'pending',

                        updated_at: new Date().toISOString()
                    };


                    // =================================================
                    // Existing Queue
                    // =================================================

                    if (existingUpdate) {

                        queueData.id =
                            existingUpdate.id;

                        syncStore.put(
                            queueData
                        );

                        console.log(
                            '🔄 Existing Sync Queue Updated:',
                            queueData
                        );

                    }

                    // =================================================
                    // New Queue
                    // =================================================
                    else {

                        syncStore.add(
                            queueData
                        );

                        console.log(
                            '➕ New Sync Queue Added:',
                            queueData
                        );
                    }
                };


            queueRequest.onerror =
                function() {

                    console.error(
                        '❌ Sync Queue Error:',
                        queueRequest.error
                    );
                };
        }


        // window.addEventListener(
        //     'online',
        //     async function() {

        //         console.log(
        //             '🌐 Internet ပြန်ရပါပြီ။'
        //         );


        //         try {

        //             await ensureDBUpdate();

        //             await syncOfflineCropsUpdate();


        //         } catch (error) {

        //             console.error(
        //                 'Online Sync Error:',
        //                 error
        //             );

        //         }

        //     }
        // );


        // async function syncOfflineCropsUpdate() {

        //     if (!navigator.onLine) {

        //         console.log(
        //             '📴 Offline → Sync မလုပ်ပါ'
        //         );

        //         return;
        //     }


        //     try {

        //         await ensureDBUpdate();


        //         const pending =
        //             await getPendingCropsUpdate();


        //         if (
        //             !pending ||
        //             pending.length === 0
        //         ) {

        //             console.log(
        //                 'Sync လုပ်စရာ Data မရှိပါ။'
        //             );

        //             return;
        //         }


        //         console.log(
        //             '🔄 ' +
        //             pending.length +
        //             ' crops syncing...',
        //             pending
        //         );


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


        //         if (!response.ok) {

        //             const errorText =
        //                 await response.text();


        //             console.error(
        //                 'Sync API Error:',
        //                 response.status,
        //                 errorText
        //             );


        //             throw new Error(
        //                 'Sync API Error: ' +
        //                 response.status
        //             );

        //         }


        //         const result =
        //             await response.json();


        //         console.log(
        //             'Sync API Result:',
        //             result
        //         );


        //         if (result.success) {

        //             await markCropsAsSyncedUpdate(
        //                 result.results || []
        //             );


        //             console.log(
        //                 '✅ Crop Sync Success'
        //             );

        //             // =============================================
        //             // Sync Result
        //             // =============================================

        //             const results = result.results || [];

        //             const successItems = results.filter(item =>
        //                 item.status === 'success'
        //             );


        //             console.log(
        //                 '✅ Success Items:',
        //                 successItems
        //             );


        //             // =============================================
        //             // Update Success
        //             // =============================================

        //             if (successItems.length > 0) {

        //                 Swal.fire({

        //                     icon: 'success',

        //                     title: 'Update အောင်မြင်ပါသည်',

        //                     text: successItems.length +
        //                         ' ခုသော သီးနှံအချက်အလက်များကို Database ထဲသို့ အောင်မြင်စွာ Update ပြုလုပ်ပြီးပါပြီ။',

        //                     confirmButtonText: 'အိုကေ'

        //                 });

        //             }


        //         }


        //     } catch (error) {

        //         console.error(
        //             '❌ Sync Error:',
        //             error
        //         );

        //     }
        // }


        // async function getPendingCropsUpdate() {

        //     const database =
        //         await ensureDBUpdate();


        //     return new Promise(
        //         function(resolve, reject) {

        //             const transaction =
        //                 database.transaction(
        //                     'sync_queue',
        //                     'readonly'
        //                 );


        //             const store =
        //                 transaction.objectStore(
        //                     'sync_queue'
        //                 );


        //             const request =
        //                 store.getAll();


        //             request.onsuccess =
        //                 function() {

        //                     const pending =
        //                         request.result.filter(
        //                             function(item) {

        //                                 return (

        //                                     item.entity ===
        //                                     'crops' &&

        //                                     item.status ===
        //                                     'pending'

        //                                 );

        //                             }
        //                         );


        //                     resolve(pending);

        //                 };


        //             request.onerror =
        //                 function() {

        //                     reject(
        //                         request.error
        //                     );

        //                 };

        //         }
        //     );
        // }


        // async function markCropsAsSyncedUpdate(results) {

        //     const database =
        //         await ensureDBUpdate();


        //     return new Promise(
        //         function(resolve, reject) {

        //             const transaction =
        //                 database.transaction(
        //                     [
        //                         'crops',
        //                         'sync_queue'
        //                     ],
        //                     'readwrite'
        //                 );


        //             const cropStore =
        //                 transaction.objectStore(
        //                     'crops'
        //                 );


        //             const syncStore =
        //                 transaction.objectStore(
        //                     'sync_queue'
        //                 );


        //             // ==========================================
        //             // Get Crops
        //             // ==========================================

        //             const cropRequest =
        //                 cropStore.getAll();


        //             cropRequest.onsuccess =
        //                 function() {

        //                     const crops =
        //                         cropRequest.result;


        //                     results.forEach(
        //                         function(result) {

        //                             if (

        //                                 result.status !==
        //                                 'success' &&

        //                                 result.status !==
        //                                 'duplicate' &&

        //                                 result.status !==
        //                                 'already_synced'

        //                             ) {

        //                                 return;

        //                             }


        //                             crops.forEach(
        //                                 function(crop) {

        //                                     if (

        //                                         crop.client_uuid ===
        //                                         result.client_uuid

        //                                     ) {

        //                                         crop.sync_status =
        //                                             'synced';


        //                                         crop.sync_result =
        //                                             result.status;


        //                                         if (
        //                                             result.server_id
        //                                         ) {

        //                                             crop.server_id =
        //                                                 result.server_id;

        //                                         }


        //                                         if (
        //                                             result.updated_at
        //                                         ) {

        //                                             crop.updated_at =
        //                                                 result.updated_at;

        //                                         }


        //                                         crop.synced_at =
        //                                             new Date()
        //                                             .toISOString();


        //                                         cropStore.put(
        //                                             crop
        //                                         );

        //                                     }

        //                                 }
        //                             );

        //                         }
        //                     );

        //                 };


        //             // ==========================================
        //             // Sync Queue
        //             // ==========================================

        //             const queueRequest =
        //                 syncStore.getAll();


        //             queueRequest.onsuccess =
        //                 function() {

        //                     const items =
        //                         queueRequest.result;


        //                     results.forEach(
        //                         function(result) {

        //                             if (

        //                                 result.status !==
        //                                 'success' &&

        //                                 result.status !==
        //                                 'duplicate' &&

        //                                 result.status !==
        //                                 'already_synced'

        //                             ) {

        //                                 return;

        //                             }


        //                             items.forEach(
        //                                 function(item) {

        //                                     if (

        //                                         item.client_uuid ===
        //                                         result.client_uuid &&

        //                                         item.status ===
        //                                         'pending'

        //                                     ) {

        //                                         item.status =
        //                                             'synced';


        //                                         item.sync_result =
        //                                             result.status;


        //                                         if (
        //                                             result.server_id
        //                                         ) {

        //                                             item.server_id =
        //                                                 result.server_id;

        //                                         }


        //                                         item.synced_at =
        //                                             new Date()
        //                                             .toISOString();


        //                                         syncStore.put(
        //                                             item
        //                                         );

        //                                     }

        //                                 }
        //                             );

        //                         }
        //                     );

        //                 };


        //             transaction.oncomplete =
        //                 function() {

        //                     console.log(
        //                         '✅ IndexedDB Sync Status Updated'
        //                     );


        //                     resolve(true);

        //                 };


        //             transaction.onerror =
        //                 function() {

        //                     reject(
        //                         transaction.error
        //                     );

        //                 };


        //             transaction.onabort =
        //                 function() {

        //                     reject(

        //                         transaction.error ||
        //                         new Error(
        //                             'Transaction aborted'
        //                         )

        //                     );

        //                 };

        //         }
        //     );
        // }
    </script>
@endsection
