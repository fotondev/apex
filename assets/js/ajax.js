const ajax = async (url, method = 'get', data = {}, domElement = null) => {
    method = method.toLowerCase();

    let options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }
    options.body = JSON.stringify(data)

    try {
        const response = await fetch(url, options);

        if (domElement) {
            clearValidationErrors(domElement);
        }

        if (!response.ok) {
            if (response.status === 422) {
                const errors = await response.json();
                handleValidationErrors(errors, domElement);
            } else if (response.status === 404) {
                window.location = '/404'
            }
        }

        return response;
    } catch (error) {
        console.error(error);
    }
}

const get = (url, data) => ajax(url, 'get', data);
const post = (url, data, domElement) => ajax(url, 'post', data, domElement);
const del = (url, data) => ajax(url, 'delete', data);

function handleValidationErrors(errors, domElement) {
    for (const name in errors.errors) {
        const element = domElement.querySelector(`[name="${name}"]`)

        element.classList.add('is-invalid')

        const errorDiv = document.createElement('div')

        errorDiv.classList.add('invalid-message')
        errorDiv.textContent = errors.errors[name]

        element.parentNode.append(errorDiv)
    }
}

function clearValidationErrors(domElement) {
    domElement.querySelectorAll('.is-invalid').forEach(function (element) {
        element.classList.remove('is-invalid')

        element.parentNode.querySelectorAll('.invalid-message').forEach(function (e) {
            e.remove()
        })
    })
}

export {
    ajax,
    get,
    post,
    del
}
