(function ($) {
    document.addEventListener('DOMContentLoaded', function () {
        const buttonPlus = document.querySelector('.button-plus');
        buttonPlus.addEventListener('click', addStudentSection);

        function addStudentSection() {
            const studentSection = document.querySelector('.student-section');
            const newStudentSection = studentSection.cloneNode(true);
            const repeatableStudentSection = document.getElementById('repeatable-student-section');

            newStudentSection.querySelectorAll('input').forEach(input => {
                input.value = '';
            });

            repeatableStudentSection.insertAdjacentElement('beforebegin', newStudentSection);
        }
    });
})(jQuery);
