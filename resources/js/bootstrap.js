import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // يمكن استبدالها بـ Ably أو Soketi حسب المخدم المستخدم

// تهيئة Axios كـ XMLHttpRequest افتراضي لطلبات AJAX
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// إعداد Pusher (أو Ably/Soketi)
// تأكد من تعيين المكتبة الصحيحة هنا بناءً على المذيع المستخدم
// مثال: window.Ably = Ably; إذا كنت تستخدم Ably
window.Pusher = Pusher;

/**
 * كائن عام لإدارة وظائف الإشعارات في الواجهة الأمامية.
 * يمكن توسيع هذا الكائن أو استيراده من ملف منفصل لزيادة التنظيم.
 */
window.App = window.App || {}; // تأكد من وجود كائن App
window.App.Notifications = {
    /**
     * تضيف إشعارًا جديدًا إلى القائمة المنسدلة للإشعارات في الواجهة الأمامية.
     * @param {object} notification - كائن الإشعار المستلم.
     */
    addNotificationToDropdown: (notification) => {
        console.log('إضافة إشعار إلى القائمة المنسدلة:', notification);
        // منطق إضافة الإشعار إلى الواجهة الأمامية هنا
        // مثال: تحديث DOM أو استخدام إطار عمل مثل Vue/React لتحديث المكونات
        // const dropdownElement = document.getElementById('notifications-dropdown');
        // if (dropdownElement) {
        //     const newItem = document.createElement('li');
        //     newItem.textContent = notification.message || 'إشعار جديد';
        //     dropdownElement.appendChild(newItem);
        // }
    },

    /**
     * تقوم بتحديث عدد الإشعارات غير المقروءة في الواجهة الأمامية.
     */
    updateUnreadCount: () => {
        console.log('تحديث عدد الإشعارات غير المقروءة.');
        // منطق تحديث العدد في الواجهة الأمامية هنا
        // مثال: تحديث عنصر رقمي بجانب أيقونة الجرس
        // const unreadCountElement = document.getElementById('unread-notifications-count');
        // if (unreadCountElement) {
        //     let currentCount = parseInt(unreadCountElement.textContent) || 0;
        //     unreadCountElement.textContent = currentCount + 1;
        // }
    },

    /**
     * تعرض إشعارًا منبثقًا (Toast notification) للمستخدم.
     * @param {string} message - رسالة الإشعار لعرضها.
     */
    showToastNotification: (message) => {
        console.log('عرض إشعار Toast:', message);
        // منطق عرض إشعار منبثق هنا (باستخدام مكتبات مثل Toastr, SweetAlert, أو تصميم مخصص)
        // مثال بسيط (يتطلب CSS إضافي):
        // const toast = document.createElement('div');
        // toast.className = 'toast-notification';
        // toast.textContent = message;
        // document.body.appendChild(toast);
        // setTimeout(() => toast.remove(), 3000); // إزالة الإشعار بعد 3 ثوانٍ
    }
};


// تهيئة Laravel Echo بشكل ديناميكي بناءً على المذيع
const broadcaster = import.meta.env.VITE_BROADCASTER || 'pusher';
const echoConfig = {
    broadcaster: broadcaster,
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    // تعطيل إحصائيات Pusher إذا لم تكن ضرورية
    disableStats: true,
};

// إعدادات خاصة بالمذيعين المختلفين
switch (broadcaster) {
    case 'pusher':
        // إعدادات Pusher الافتراضية جيدة
        // forceTLS: (import.meta.env.VITE_BROADCASTER === 'pusher') ? false : true,
        // إذا كنت تستخدم Pusher، عادةً ما يكون forceTLS: false في البيئات المحلية و true في الإنتاج
        // ولكن يمكن تركه افتراضيًا إذا كان المخدم يدعم ذلك
        break;
    case 'soketi':
    case 'reverb': // إضافة Reverb إذا كنت تستخدمه في Laravel 11+
        echoConfig.wsHost = import.meta.env.VITE_SOKETI_HOST || window.location.hostname;
        echoConfig.wsPort = import.meta.env.VITE_SOKETI_PORT || 6001;
        echoConfig.wssPort = import.meta.env.VITE_SOKETI_PORT || 6001;
        echoConfig.forceTLS = true; // عادةً ما يتم فرض TLS لـ Soketi/Reverb في الإنتاج
        echoConfig.disableStats = true; // تعطيل الإحصائيات إذا لم تكن ضرورية
        break;
    case 'ably':
        // إعدادات Ably قد تختلف وتتطلب تهيئة إضافية أو استخدام مفتاح API مختلف
        // echoConfig.key = import.meta.env.VITE_ABLY_APP_KEY;
        // echoConfig.host = 'realtime-pusher.ably.io';
        echoConfig.forceTLS = true;
        break;
    default:
        console.warn(`مذيع غير معروف: ${broadcaster}. سيتم استخدام الإعدادات الافتراضية.`);
        break;
}


window.Echo = new Echo(echoConfig);

/**
 * دالة مساعدة للتحقق من وجود كائن المستخدم.
 * @returns {boolean} True إذا كان معرف المستخدم متاحًا، وإلا False.
 */
const isUserIdAvailable = () => {
    // التحقق من وجود Laravel و Laravel.user و Laravel.user.id
    return window.Laravel && window.Laravel.user && window.Laravel.user.id;
};

// الاستماع إلى الإشعارات الخاصة بالمستخدم المصادق عليه
if (isUserIdAvailable()) {
    const userId = window.Laravel.user.id;
    const channelName = `App.Models.User.${userId}`; // اسم القناة الافتراضي لإشعارات المستخدم في Laravel

    window.Echo.private(channelName)
        .notification((notification) => {
            console.log('إشعار جديد في الوقت الفعلي:', notification);

            // استدعاء وظائف تحديث الواجهة الأمامية من الكائن العام
            window.App.Notifications.addNotificationToDropdown(notification);
            window.App.Notifications.updateUnreadCount();
            window.App.Notifications.showToastNotification(notification.message);
        })
        .error((error) => {
            console.error('حدث خطأ في قناة الإشعارات الخاصة:', error);
            // هنا يمكنك إرسال الخطأ إلى خدمة مراقبة الأخطاء في بيئة الإنتاج
            // مثال: Sentry.captureException(error);
        });
} else {
    console.warn('Laravel Echo لم يتم تهيئته للاستماع للإشعارات: معرف المستخدم غير متاح.');
    // يمكن هنا عرض رسالة للمطور فقط في وضع التطوير
    // if (import.meta.env.DEV) {
    //     console.log('تأكد من أن المستخدم مسجل الدخول وأن `window.Laravel.user` متاح.');
    // }
}
