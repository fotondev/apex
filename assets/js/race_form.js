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


})