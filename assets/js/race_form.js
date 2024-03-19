import '../styles/app.scss';
import {get} from './ajax';

window.addEventListener('DOMContentLoaded', (event) => {

    const trackChoice = document.querySelector('#race_event_track');
    const maxCarSlots = document.querySelector('#race_event_serverOptions_maxCarSlots');
    trackChoice.addEventListener('change', (event) => {
        get('/api/settings/max-car-slots?trackId=' + trackChoice.value)
            .then(response => response.json())
            .then(data => {
                document.querySelector('#race_event_serverOptions_maxCarSlots').value = data.slots;
            })

    });

    const overridePassCheck = document.querySelector('#race_event_serverOptions_overridePassword');
    const passwordField = document.querySelector('#race_event_serverOptions_password');

    togglePassword(overridePassCheck, passwordField);

    overridePassCheck.addEventListener('change', (event) => {
        togglePassword(overridePassCheck, passwordField);
    });


    var addEntryButton = document.getElementById('add-entry');
    addEntryButton.addEventListener('click', function (event) {
        event.preventDefault();



    });


    function togglePassword(checkbox, passwordField) {
        if (checkbox.checked) {
            passwordField.removeAttribute('disabled');
            passwordField.style.display = 'block';
        } else {
            passwordField.setAttribute('disabled', 'disabled');
            passwordField.style.display = 'none';
            passwordField.value = '';
        }
    }


})