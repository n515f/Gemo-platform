// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  // اجعل الوضع الداكن عبر إضافة class على <html> (نفس ما نفعله في layouts.site)
  darkMode: 'class',

  // الملفات التي يفحصها Tailwind لاستخراج الـ classes المستخدمة
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views//*.blade.php',
    './resources/js//*.js', // (لـ Alpine أو أي JS يولّد/يتلاعب بالـ classes)
  ],

  theme: {
    extend: {
      fontFamily: {
        // استخدم الخطوط التي تعتمدها فعليًا في الواجهة
        sans: ['Cairo', 'Inter', ...defaultTheme.fontFamily.sans],
      },
      // بإمكانك توسيع الألوان/الظلال هنا إذا احتجت لاحقًا
    },
  },

  plugins: [
    forms, // تحسين نماذج Tailwind
  ],
}