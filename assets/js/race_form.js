import '../styles/app.scss';
import {get, post, del} from './ajax';

window.addEventListener('DOMContentLoaded', (event) => {

    const trackChoice = document.querySelector('#race_event_track');
    const maxCarSlots = document.querySelector('#race_event_serverOptions_maxCarSlots');
    trackChoice.addEventListener('change', (event) => {
        get('/api/settings/max-car-slots?trackId=' + trackChoice.value)
            .then(response => response.json())
            .then(data => {
                maxCarSlots.value = data.slots;
            })

    });

    const overridePassCheck = document.querySelector('#race_event_serverOptions_overridePassword');
    const passwordField = document.querySelector('#race_event_serverOptions_password');

    togglePassword(overridePassCheck, passwordField);

    overridePassCheck.addEventListener('change', (event) => {
        togglePassword(overridePassCheck, passwordField);
    });


    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addEntryFormToCollection)
        });

    document
        .querySelectorAll('ul.entries li')
        .forEach((entry) => {
            addEntryFormDeleteLink(entry)
        });


    function addEntryFormToCollection(e) {
        e.preventDefault();
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
        const quantityInput = document.querySelector('#race_event_addEntries_quantity');

        let quantity = parseInt(quantityInput.value);
        let maxSlots = parseInt(maxCarSlots.value);
        let existingForms = collectionHolder.children.length;


        if ((quantity + existingForms) > maxSlots) {
            quantity = maxSlots - existingForms;
        }

        for (let i = 0; i < quantity; i++) {
            const item = document.createElement('li');
            item.classList.add('card', 'mt-3', 'border-warning');

            const cardHeader = document.createElement('div');
            cardHeader.classList.add('card-header');
            const headerText = document.createElement('h5');
            headerText.innerText = 'New Entry';
            cardHeader.appendChild(headerText);
            item.appendChild(cardHeader);

            const cardBody = document.createElement('div');
            cardBody.classList.add('card-body');
            item.appendChild(cardBody);

            cardBody.innerHTML = collectionHolder
                .dataset
                .prototype
                .replace(
                    /__name__/g,
                    collectionHolder.dataset.index
                );

            collectionHolder.appendChild(item);

            collectionHolder.dataset.index++;
            addEntryFormDeleteLink(item);
        }


    }

    function addEntryFormDeleteLink(item) {
        const removeFormButton = document.createElement('button');
        const itemHeader = item.querySelector('.card-header');
        removeFormButton.classList.add('btn', 'btn-sm', 'btn-danger', 'ml-auto');
        removeFormButton.innerText = 'Delete';
        itemHeader.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            let entryId = item.querySelector('.card-body').getAttribute('data-index');
            console.log(entryId)
            if ("" !== entryId) {
                del('/admin/entries/' + entryId)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
            item.remove();
        });
    }

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