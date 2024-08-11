const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


var translations = {
  "en": {
    "No data available in table": "No data available in table",
    "No matching records found": "No matching records found",
    "to" : "to",
    "from" : "from",
    "Submit" : "Submit",
    "Cancel" : "Cancel",
    "Ok" : "Ok",
    "Do you really want to delete this Category?": "Do you really want to delete this Category?",
    "Do you really want to delete this Coupon?": "Do you really want to delete this Coupon?",
    "Do you really want to delete this Plan?": "Do you really want to delete this Plan?",
    "Do you really want to delete this Blog?": "Do you really want to delete this Blog?",
    "Do you really want to delete this Article?": "Do you really want to delete this Article?",
    "Do you really want to delete this Publication?" : "Do you really want to delete this Publication?",
    "Do you really want to delete this Product?": "Do you really want to delete this Product?",
    "Do you really want to delete this SubCategory?": "Do you really want to delete this SubCategory?",
    "Do you really want to Pay For This Plan?" : "Do you really want to Pay For This Plan?",
    "No Plan Selected" : "No Plan Selected",
    "Please select a plan before proceeding." : "Please select a plan before proceeding.",
    "Drag and drop files here or click to upload" : "Drag and drop files here or click to upload",
    "month" : "month",
    "today" : "today",
    "week" : "week",
    "day" : "day",
    "Add Event" : "Add Event",
    "Do you really want to delete this Photo?" : "Do you really want to delete this Photo?",
    "Successfully copied the URL!" : "Successfully copied the URL!",
    "Copy" : "Copy",
    "Just Now" : "Just Now",
    "Error" : "Error"


  },
  "ar": {
    "No data available in table": "لا تتوفر بيانات في الجدول",
    "No matching records found": "لم يتم العثور على سجلات مطابقة",
    "to" : "من",
    "from" : "إلى",
    "Submit" : "موافق",
    "Cancel" : "إلغاء",
    "Ok" : "حسنا",
    "Do you really want to delete this Category?": "هل ترغب حقًا في حذف هذا التصنيف؟",
    "Do you really want to delete this Coupon?": "هل ترغب حقًا في حذف هذا الكوبون؟",
    "Do you really want to delete this Plan?": "هل ترغب حقًا في حذف هذه الخطة؟",
    "Do you really want to delete this Blog?": "هل ترغب حقًا في حذف هذه المدونة؟",
    "Do you really want to delete this Article?": "هل ترغب حقًا في حذف هذا المقال؟",
    "Do you really want to delete this Publication?" : "هل ترغب حقًا في حذف هذا المنشور؟",
    "Do you really want to delete this Product?": "هل ترغب حقًا في حذف هذا المنتج؟",
    "Do you really want to delete this SubCategory?": "هل ترغب حقًا في حذف هذه الفئة؟",
    "Do you really want to Pay For This Plan?" : "هل ترغب حقًا في شراء هذه الخطة؟",
    "No Plan Selected" : "لم يتم اختيار أي خطة",
    "Please select a plan before proceeding." : "يرجى اختيار خطة قبل المتابعة",
    "Drag and drop files here or click to upload" : "اسحب وأفلت الملفات هنا أو انقر للتحميل أو إضغط للرفع",
    "month" : "شهر",
    "today" : "اليوم",
    "week" : "أسبوع",
    "day" : "يوم",
    "Add Event" : "إضافة حدث",
    "Do you really want to delete this Photo?" : "هل ترغب حقًا في حذف هذه الصورة",
    "Successfully copied the URL!" : "تم نسخ الرابط بنجاح",
    "Copy" : "نسخ",
    "Just Now" : "الأن",
    "Error" : "خطأ"

  }
};


function __(key,lang) {
  if (translations.hasOwnProperty(lang) && translations[lang].hasOwnProperty(key)) {
    return translations[lang][key];
  } else {
    return key;
  }
}
