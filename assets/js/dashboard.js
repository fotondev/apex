
import "../styles/dashboard.scss";
import {get, post} from './ajax';

window.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#create_event').addEventListener('click', function (event) {
        get(`/race-event`)
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
            })
            .then(data => {
                const eventId = data.data.id;
                window.location.href = '/race-event/' + eventId;
            });
    });
});
