/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.less in this case)
import {get} from "./js/ajax";

require('bootstrap')
import './styles/app.scss';
import './js/login.js';
import './js/lostpass';
import './js/race_form.js';
import './js/dashboard.js';



// document.querySelector('#create_quick_race').addEventListener('click', function (event) {
//     get(`/quick-race`)
//         .then(response => {
//             if (response.ok) {
//                 return response.json();
//             }
//         })
//         .then(data => {
//             const eventId = data.data.id;
//             window.location.href = '/quick-race/edit?id='+ eventId;
//         });
// });