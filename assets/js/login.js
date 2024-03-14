import '../styles/app.scss';
import {post} from './ajax';

window.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#get_access').addEventListener('click', function (event) {
        const form = this.closest('form');
        const formData = new FormData(form);
        const inputs = Object.fromEntries(formData.entries());
        const note = document.querySelector('#form_successful');

         post(`/request_password`, inputs, form)
            .then(response => {
                if (response.ok) {
                    form.style.display = 'none';
                    note.style.display = 'block';
                }
            });
    });
});
