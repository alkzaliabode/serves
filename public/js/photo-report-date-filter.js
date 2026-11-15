document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr !== 'function') return;

    document.querySelectorAll('#photo-report-date-filter input[type="date"]').forEach(function (input) {
        flatpickr(input, {
            // Keep the submitted value unambiguous for database filtering.
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            disableMobile: true,
            locale: {
                firstDayOfWeek: 6,
                weekdays: {
                    shorthand: ['أحد', 'إثنين', 'ثلاثاء', 'أربعاء', 'خميس', 'جمعة', 'سبت'],
                    longhand: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']
                },
                months: {
                    shorthand: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                    longhand: ['كانون الثاني', 'شباط', 'آذار', 'نيسان', 'أيار', 'حزيران', 'تموز', 'آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول']
                }
            },
            onReady: function (dates, value, instance) {
                instance.altInput.id = input.id + '_display';
                instance.altInput.placeholder = 'يوم/شهر/سنة';
                instance.altInput.setAttribute('dir', 'ltr');
                document.querySelector('label[for="' + input.id + '"]').htmlFor = instance.altInput.id;
            }
        });
    });
});
