import localforage from 'localforage';

// POS instance
export const posStore = localforage.createInstance({
    name: 'pos_data'
});

// Admin instance
export const adminStore = localforage.createInstance({
    name: 'admin_data'
});

// Sync Queue
export const syncQueue = localforage.createInstance({
    name: 'sync_queue'
});

// Add item to queue
export async function queueItem(queueName, data) {
    const queue = (await syncQueue.getItem(queueName)) || [];
    queue.push(data);
    await syncQueue.setItem(queueName, queue);
}

// Get and clear queue
export async function popQueue(queueName) {
    const queue = (await syncQueue.getItem(queueName)) || [];
    await syncQueue.setItem(queueName, []);
    return queue;
}
