// resources/js/db.js
import { openDB } from "idb";


export const initDB = async () => {
    return await openDB("aqua-reports", 1, {
        upgrade(db) {
            if (!db.objectStoreNames.contains("reports")) {
                db.createObjectStore("reports", {
                    keyPath: "id",
                    autoIncrement: true,
                });
            }
        },
    });
};

export const saveReportOffline = async (report) => {
    const db = await initDB();
    await db.add("reports", {
        ...report,
        synced: false,
        createdAt: new Date(),
    });
};

export const getPendingReports = async () => {
    const db = await initDB();
    return await db.getAll("reports");
};

export const deleteReport = async (id) => {
    const db = await initDB();
    await db.delete("reports", id);
};
