// =========================================================
// IndexedDB Setup
// =========================================================

const DB_NAME = 'WholeSaleMarketDB';
const DB_VERSION = 3;

let db = null;

// DB ဖွင့်နေချိန်မှာ request များစွာ မဖွင့်အောင်
let dbPromise = null;


// =========================================================
// Open / Initialize IndexedDB
// =========================================================

function openDatabase() {

    // DB ရှိပြီးသားဆိုရင်
    if (db) {
        return Promise.resolve(db);
    }


    // DB ဖွင့်နေပြီးသားဆိုရင်
    if (dbPromise) {
        return dbPromise;
    }


    dbPromise = new Promise(function (resolve, reject) {

        console.log(
            'IndexedDB ကို ဖွင့်နေပါတယ်...'
        );


        const request =
            indexedDB.open(
                DB_NAME,
                DB_VERSION
            );


        // =================================================
        // Database Upgrade
        // =================================================

        request.onupgradeneeded =
            function (event) {

                const database =
                    event.target.result;


                // =========================================
                // Crops Store
                // =========================================

                if (
                    !database.objectStoreNames
                        .contains('crops')
                ) {

                    const cropStore =
                        database.createObjectStore(
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


                    console.log(
                        'crops store created'
                    );
                }


                // =========================================
                // Sync Queue Store
                // =========================================

                if (
                    !database.objectStoreNames
                        .contains('sync_queue')
                ) {

                    const syncStore =
                        database.createObjectStore(
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


                    console.log(
                        'sync_queue store created'
                    );
                }
            };


        // =================================================
        // Database Success
        // =================================================

        request.onsuccess =
            function (event) {

                db =
                    event.target.result;


                console.log(
                    'IndexedDB Connected'
                );


                // DB connection ပိတ်သွားရင်
                db.onclose =
                    function () {

                        console.warn(
                            'IndexedDB connection closed'
                        );

                        db = null;
                        dbPromise = null;
                    };


                // DB error
                db.onerror =
                    function (event) {

                        console.error(
                            'IndexedDB Runtime Error:',
                            event.target.error
                        );
                    };


                resolve(db);
            };


        // =================================================
        // Database Error
        // =================================================

        request.onerror =
            function (event) {

                console.error(
                    'IndexedDB Open Error:',
                    event.target.error
                );


                db = null;
                dbPromise = null;


                reject(
                    event.target.error
                );
            };


        // =================================================
        // Database Blocked
        // =================================================

        request.onblocked =
            function () {

                console.warn(
                    'IndexedDB Open Blocked'
                );
            };

    });


    return dbPromise;
}

// =========================================================
// Make Sure DB Is Ready
// =========================================================

async function ensureDB() {

    // DB ရှိပြီးသား
    if (db) {
        return db;
    }


    // DB မရှိသေးရင် ဖွင့်မယ်
    const database =
        await openDatabase();


    if (!database) {

        throw new Error(
            'IndexedDB connection မရပါ'
        );
    }


    return database;
}



// =========================================================
// Page Load
// =========================================================

document.addEventListener(
    'DOMContentLoaded',
    async function () {

        try {

            await ensureDB();


            console.log(
                'IndexedDB Ready'
            );


            // Page ဖွင့်တဲ့အချိန် Online ဖြစ်ပြီး
            // pending data ရှိရင် sync
            if (navigator.onLine) {

                await syncOfflineCrops();
            }


        } catch (error) {

            console.error(
                'IndexedDB initialization failed:',
                error
            );
        }

    }
);

// =========================================================
// Internet ပြန်ရရင် Auto Sync
// =========================================================

window.addEventListener(
    'online',
    async function () {

        console.log(
            'Internet ပြန်ရပါပြီ။'
        );


        try {

            // DB ready ဖြစ်အောင်စောင့်
            await ensureDB();


            // Auto Sync
            await syncOfflineCrops();


        } catch (error) {

            console.error(
                'Online Sync Error:',
                error
            );
        }

    }
);



// =========================================================
// Get Pending Crops
// =========================================================

async function getPendingCrops() {

    const database =
        await ensureDB();


    return new Promise(
        function (resolve, reject) {

            try {

                const transaction =
                    database.transaction(
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
                    function () {

                        const pending =
                            request.result.filter(
                                function (item) {

                                    return (
                                        item.entity ===
                                        'crops' &&

                                        item.status ===
                                        'pending'
                                    );

                                }
                            );


                        resolve(
                            pending
                        );
                    };


                request.onerror =
                    function () {

                        reject(
                            request.error
                        );
                    };


            } catch (error) {

                reject(error);
            }

        }
    );
}



// =========================================================
// Sync Offline Crops
// =========================================================

async function syncOfflineCrops() {

    // =============================================
    // Internet မရှိရင် မလုပ်
    // =============================================

    if (!navigator.onLine) {

        console.log(
            'Offline → Sync မလုပ်ပါ'
        );

        return;
    }


    try {

        // =============================================
        // DB Ready
        // =============================================

        await ensureDB();


        // =============================================
        // Get Pending Data
        // =============================================

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


        // =============================================
        // Send Laravel API
        // =============================================

        const response =
            await fetch(
                window.cropSyncUrl, {
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


        // =============================================
        // API Error
        // =============================================

        if (!response.ok) {

            throw new Error(
                'Sync API Error: ' +
                response.status
            );
        }


        // =============================================
        // JSON Result
        // =============================================

        const result =
            await response.json();


        console.log(
            'Sync API Result:',
            result
        );


        // =============================================
        // Update IndexedDB
        // =============================================

        if (result.success) {

            await markCropsAsSynced(
                result.results
            );


            console.log(
                'Crop Sync Success'
            );


            // ==========================================
            // Count Result
            // ==========================================

            const results =
                result.results || [];


            const successItems =
                results.filter(
                    function (item) {

                        return (
                            item.status ===
                            'success'
                        );

                    }
                );


            const duplicateItems =
                results.filter(
                    function (item) {

                        return (
                            item.status ===
                            'duplicate'
                        );

                    }
                );


            const alreadySyncedItems =
                results.filter(
                    function (item) {

                        return (
                            item.status ===
                            'already_synced'
                        );

                    }
                );


            const failedItems =
                results.filter(
                    function (item) {

                        return (
                            item.status ===
                            'failed'
                        );

                    }
                );


            // ==========================================
            // Success
            // ==========================================

            if (
                successItems.length > 0
            ) {

                Swal.fire({

                    icon: 'success',

                    title: 'အောင်မြင်ပါသည်',

                    text: successItems.length +
                        ' ခုသော သီးနှံအချက်အလက်များကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။',

                    confirmButtonText: 'အိုကေ'
                });
            }


            // ==========================================
            // Duplicate
            // ==========================================
            else if (
                duplicateItems.length > 0
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'သီးနှံအမည် ထပ်နေပါသည်',

                    text: 'သီးနှံအချက်အလက်သည် Database တွင် ရှိပြီးသားဖြစ်သောကြောင့် သိမ်းဆည်းခြင်းမပြုပါ။',

                    confirmButtonText: 'အိုကေ'
                });
            }


            // ==========================================
            // Already Synced
            // ==========================================
            else if (
                alreadySyncedItems.length > 0
            ) {

                console.log(
                    alreadySyncedItems.length +
                    ' crops already synced'
                );
            }


            // ==========================================
            // Failed
            // ==========================================

            if (
                failedItems.length > 0
            ) {

                console.warn(
                    'Some crops failed to sync:',
                    failedItems
                );
            }

        }


    } catch (error) {

        console.error(
            'Sync Error:',
            error
        );

        /*
         * Internet ပြန်ပြတ်သွားရင်
         * Data ကို pending အတိုင်းထားမယ်
         */
    }
}



// =========================================================
// Mark Crops As Synced
// =========================================================

async function markCropsAsSynced(results) {

    const database =
        await ensureDB();


    return new Promise(
        function (resolve, reject) {

            try {

                const transaction =
                    database.transaction(
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


                // =========================================
                // Get Local Crops
                // =========================================

                const cropRequest =
                    cropStore.getAll();


                cropRequest.onsuccess =
                    function () {

                        const crops =
                            cropRequest.result;


                        results.forEach(
                            function (result) {

                                // Success / Duplicate /
                                // Already Synced
                                if (
                                    result.status !==
                                    'success' &&

                                    result.status !==
                                    'duplicate' &&

                                    result.status !==
                                    'already_synced'
                                ) {

                                    return;
                                }


                                crops.forEach(
                                    function (crop) {

                                        if (
                                            crop.client_uuid ===
                                            result.client_uuid
                                        ) {

                                            crop.sync_status =
                                                'synced';


                                            crop.sync_result =
                                                result.status;


                                            crop.synced_at =
                                                new Date()
                                                    .toISOString();


                                            cropStore.put(
                                                crop
                                            );
                                        }

                                    }
                                );

                            }
                        );

                    };


                cropRequest.onerror =
                    function () {

                        reject(
                            cropRequest.error
                        );
                    };


                // =========================================
                // Update Sync Queue
                // =========================================

                const queueRequest =
                    syncStore.getAll();


                queueRequest.onsuccess =
                    function () {

                        const items =
                            queueRequest.result;


                        results.forEach(
                            function (result) {

                                if (
                                    result.status !==
                                    'success' &&

                                    result.status !==
                                    'duplicate' &&

                                    result.status !==
                                    'already_synced'
                                ) {

                                    return;
                                }


                                items.forEach(
                                    function (item) {

                                        if (
                                            item.client_uuid ===
                                            result.client_uuid
                                        ) {

                                            item.status =
                                                'synced';


                                            item.sync_result =
                                                result.status;


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


                queueRequest.onerror =
                    function () {

                        reject(
                            queueRequest.error
                        );
                    };


                // =========================================
                // Transaction Complete
                // =========================================

                transaction.oncomplete =
                    function () {

                        resolve(
                            true
                        );
                    };


                // =========================================
                // Transaction Error
                // =========================================

                transaction.onerror =
                    function () {

                        reject(
                            transaction.error
                        );
                    };


                // =========================================
                // Transaction Abort
                // =========================================

                transaction.onabort =
                    function () {

                        reject(
                            transaction.error ||
                            new Error(
                                'Transaction aborted'
                            )
                        );
                    };

            } catch (error) {

                reject(error);
            }

        }
    );
}




//for update

async function openDatabaseUpdate() {

    if (db) {
        return db;
    }

    if (dbPromise) {
        return dbPromise;
    }

    dbPromise = new Promise((resolve, reject) => {

        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onsuccess = function (event) {

            db = event.target.result;

            console.log('IndexedDB Connected');

            resolve(db);
        };

        request.onerror = function (event) {

            console.error(
                'IndexedDB Error:',
                event.target.error
            );

            dbPromise = null;

            reject(event.target.error);
        };

    });

    return dbPromise;
}

async function ensureDBUpdate() {

    // DB ဖွင့်ပြီးသားဆိုရင်
    if (db) {
        return db;
    }

    // DB မဖွင့်ရသေးရင် Open လုပ်မယ်
    return await openDatabaseUpdate();
}


window.addEventListener(
    'online',
    async function () {

        console.log(
            '🌐 Internet ပြန်ရပါပြီ။'
        );


        try {

            await ensureDBUpdate();

            await syncOfflineCropsUpdate();


        } catch (error) {

            console.error(
                'Online Sync Error:',
                error
            );

        }

    }
);

async function syncOfflineCropsUpdate() {

    if (!navigator.onLine) {

        console.log(
            '📴 Offline → Sync မလုပ်ပါ'
        );

        return;
    }


    try {

        await ensureDBUpdate();


        const pending =
            await getPendingCropsUpdate();


        if (
            !pending ||
            pending.length === 0
        ) {

            console.log(
                'Sync လုပ်စရာ Data မရှိပါ။'
            );

            return;
        }


        console.log(
            '🔄 ' +
            pending.length +
            ' crops syncing...',
            pending
        );


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

            const errorText =
                await response.text();


            console.error(
                'Sync API Error:',
                response.status,
                errorText
            );


            throw new Error(
                'Sync API Error: ' +
                response.status
            );

        }


        const result =
            await response.json();


        console.log(
            'Sync API Result:',
            result
        );


        if (result.success) {

            await markCropsAsSyncedUpdate(
                result.results || []
            );


            console.log(
                '✅ Crop Sync Success'
            );

            // =============================================
            // Sync Result
            // =============================================

            const results = result.results || [];

            const successItems = results.filter(item =>
                item.status === 'success'
            );


            console.log(
                '✅ Success Items:',
                successItems
            );


            // =============================================
            // Update Success
            // =============================================

            if (successItems.length > 0) {

                Swal.fire({

                    icon: 'success',

                    title: 'Update အောင်မြင်ပါသည်',

                    text: successItems.length +
                        ' ခုသော သီးနှံအချက်အလက်များကို Database ထဲသို့ အောင်မြင်စွာ Update ပြုလုပ်ပြီးပါပြီ။',

                    confirmButtonText: 'အိုကေ'

                });

            }


        }


    } catch (error) {

        console.error(
            '❌ Sync Error:',
            error
        );

    }
}


async function getPendingCropsUpdate() {

    const database =
        await ensureDBUpdate();


    return new Promise(
        function (resolve, reject) {

            const transaction =
                database.transaction(
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
                function () {

                    const pending =
                        request.result.filter(
                            function (item) {

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
                function () {

                    reject(
                        request.error
                    );

                };

        }
    );
}


async function markCropsAsSyncedUpdate(results) {

    const database =
        await ensureDBUpdate();


    return new Promise(
        function (resolve, reject) {

            const transaction =
                database.transaction(
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


            // ==========================================
            // Get Crops
            // ==========================================

            const cropRequest =
                cropStore.getAll();


            cropRequest.onsuccess =
                function () {

                    const crops =
                        cropRequest.result;


                    results.forEach(
                        function (result) {

                            if (

                                result.status !==
                                'success' &&

                                result.status !==
                                'duplicate' &&

                                result.status !==
                                'already_synced'

                            ) {

                                return;

                            }


                            crops.forEach(
                                function (crop) {

                                    if (

                                        crop.client_uuid ===
                                        result.client_uuid

                                    ) {

                                        crop.sync_status =
                                            'synced';


                                        crop.sync_result =
                                            result.status;


                                        if (
                                            result.server_id
                                        ) {

                                            crop.server_id =
                                                result.server_id;

                                        }


                                        if (
                                            result.updated_at
                                        ) {

                                            crop.updated_at =
                                                result.updated_at;

                                        }


                                        crop.synced_at =
                                            new Date()
                                                .toISOString();


                                        cropStore.put(
                                            crop
                                        );

                                    }

                                }
                            );

                        }
                    );

                };


            // ==========================================
            // Sync Queue
            // ==========================================

            const queueRequest =
                syncStore.getAll();


            queueRequest.onsuccess =
                function () {

                    const items =
                        queueRequest.result;


                    results.forEach(
                        function (result) {

                            if (

                                result.status !==
                                'success' &&

                                result.status !==
                                'duplicate' &&

                                result.status !==
                                'already_synced'

                            ) {

                                return;

                            }


                            items.forEach(
                                function (item) {

                                    if (

                                        item.client_uuid ===
                                        result.client_uuid &&

                                        item.status ===
                                        'pending'

                                    ) {

                                        item.status =
                                            'synced';


                                        item.sync_result =
                                            result.status;


                                        if (
                                            result.server_id
                                        ) {

                                            item.server_id =
                                                result.server_id;

                                        }


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
                function () {

                    console.log(
                        '✅ IndexedDB Sync Status Updated'
                    );


                    resolve(true);

                };


            transaction.onerror =
                function () {

                    reject(
                        transaction.error
                    );

                };


            transaction.onabort =
                function () {

                    reject(

                        transaction.error ||
                        new Error(
                            'Transaction aborted'
                        )

                    );

                };

        }
    );
}
