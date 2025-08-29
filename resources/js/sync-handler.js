import axios from 'axios';
import { popQueue } from './offline-storage';

export async function syncData() {
    if (!navigator.onLine) return;

    // Sync Sales
    const salesQueue = await popQueue('sales_queue');
    if (salesQueue.length > 0) {
        try {
            await axios.post('/sync/sales', { sales: salesQueue });
            console.log(`✅ Synced ${salesQueue.length} sales`);
        } catch (err) {
            console.error('❌ Sales sync failed', err);
        }
    }

    // Sync Admin Updates
    const updateQueue = await popQueue('update_queue');
    if (updateQueue.length > 0) {
        try {
            await axios.post('/sync/updates', { updates: updateQueue });
            console.log(`✅ Synced ${updateQueue.length} updates`);
        } catch (err) {
            console.error('❌ Update sync failed', err);
        }
    }
}

// Auto sync on reconnect
window.addEventListener('online', syncData);
document.addEventListener('DOMContentLoaded', syncData);
