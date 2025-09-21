// resources/js/bootstrap.js
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ضيف CSRF للطلبات المحمية (موجودة في <head> بالـ Blade)
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// (اختياري) Interceptors لتوحيد الأخطاء/اللودينج
// window.axios.interceptors.response.use(
//   (res) => res,
//   (err) => {
//     console.error(err?.response || err);
//     return Promise.reject(err);
//   }
// );