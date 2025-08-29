
// offline-sync.js
// Uses localforage (IndexedDB wrapper) to store queue & local data
const SYNC_QUEUE_KEY = 'justartech_sync_queue_v1';
const LOCAL_STORE = 'justartech_local_store_v1';
const SYNC_ENDPOINT = '/sync/push'; // route we'll create in Laravel

localforage.config({ name: 'justartech' });

// helper to get queue
async function getQueue() {
  return (await localforage.getItem(SYNC_QUEUE_KEY)) || [];
}
async function setQueue(q) { return localforage.setItem(SYNC_QUEUE_KEY, q); }

// enqueue an operation
async function enqueueOperation(op) {
  const queue = await getQueue();
  queue.push(op);
  await setQueue(queue);
  // Try immediate sync if online
  if (navigator.onLine) {
    drainQueue();
  }
}

// drain queue: POST batch to server
let _draining = false;
async function drainQueue() {
  if (_draining) return;
  _draining = true;
  try {
    let queue = await getQueue();
    if (!queue.length) { _draining = false; return; }

    // Send in batches (e.g. 20 ops)
    const batch = queue.slice(0, 20);
    const res = await fetch(SYNC_ENDPOINT, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({ operations: batch })
    });

    if (!res.ok) {
      // server error - don't clear queue, try later
      console.error('Sync HTTP error', res.status);
      _draining = false;
      return;
    }

    const payload = await res.json();
    // payload: { results: [{ temp_id, model, success, server_id, error }, ...] }
    // Remove processed operations from queue if success/handled
    const remaining = queue.slice(batch.length);
    // For successful ones, update local store mapping
    for (const r of payload.results || []) {
      if (r.success && r.temp_id) {
        // store mapping for client use:
        await localforage.setItem(`mapping:${r.model}:${r.temp_id}`, r.server_id);
      } else if (r.error) {
        console.warn('Sync error for', r, 'leave to retry or inspect');
      }
    }
    await setQueue(remaining);
    // Continue if more
    if (remaining.length) {
      setTimeout(drainQueue, 200);
    }
  } catch (err) {
    console.error('Sync drain failed', err);
  } finally { _draining = false; }
}

// Watch for online
window.addEventListener('online', () => {
  console.log('Went online, draining sync queue...');
  drainQueue();
});

// Optional: periodic retry every 30s if online
setInterval(() => {
  if (navigator.onLine) drainQueue();
}, 30_000);

// Example helper to create an operation and persist locally
async function createSaleOperation(saleData) {
  // saleData: { items: [...], total: 1000, payment_method: 'mpesa', mpesa_info: {...} }
  const tempId = 't_' + Date.now() + '_' + Math.random().toString(36).slice(2,9);
  const op = {
    model: 'sale',
    action: 'create',
    temp_id: tempId,
    data: saleData,
    client_uuid: 'browser-' + (localStorage.getItem('client_uuid') || (function(){ const id = 'c_' + Date.now() + '_' + Math.random().toString(36).slice(2,8); localStorage.setItem('client_uuid', id); return id; })()),
    ts: new Date().toISOString()
  };
  // Save locally in local store for fast UI availability
  const localKey = `${LOCAL_STORE}:sale:${tempId}`;
  await localforage.setItem(localKey, op.data);

  // enqueue operation
  await enqueueOperation(op);
  return tempId;
}

// Expose helper
window.JUSTART_SYNC = {
  enqueueOperation,
  createSaleOperation,
  drainQueue
};
