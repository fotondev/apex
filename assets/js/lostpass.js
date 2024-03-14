import {post} from './ajax';


window.addEventListener('DOMContentLoaded', function () {
    const resetPasswordBtn = document.querySelector('#reset_pass');
    const updatePasswordBtn = document.querySelector('#update_pass');

    if (resetPasswordBtn) {
        resetPasswordBtn.addEventListener('click', function (event) {
            const form = this.closest('form');
            const email = form.querySelector('input[name="email"]').value
            const note = document.querySelector('#form_successful');


            post('/lostpass', email, form)
                .then(response => {
                    if (response.ok) {
                        form.style.display = 'none';
                        note.style.display = 'block';
                    }
                });
        });
    }
    if (updatePasswordBtn) {
        updatePasswordBtn.addEventListener('click', function (event) {
            const form = this.closest('form');
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const note = document.querySelector('#form_successful');

            post('/reset-password', data, form)
                .then(response => {
                    if (response.ok) {
                        form.style.display = 'none';
                        note.style.display = 'block';
                    }
                });
        });
    }

});