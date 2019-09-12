function register(event, actionLocation) {
    event.preventDefault();
    document.getElementById('reg-processor').style.display = "block";
    let loader = document.getElementById('reg-loader');

    toggle("error-box", "display", "none");
    resetErroredInput();
    loader.style.display = "initial";

    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    const form = new FormData();
    // TODO: get element by id into an array
    form.append('name', document.getElementById('name').value);
    form.append('email', document.getElementById('email').value);
    form.append('username', document.getElementById('username').value);
    form.append('password', document.getElementById('password').value);


    axios.post(actionLocation, form)
        .then(function (response) {
            if (response.data['status'] === 'success') {
                document.getElementById('main-container').innerHTML = response.data['result'];
            }
            else {
                loader.style.display = "none";
                let errors = response.data["errors"];

                for (let key in errors) {
                    displayError(key, errors[key]);
                }

                console.log(response)
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

function displayError(key, error) {
    console.log(key);
    console.log(error);
    let box = document.getElementById(key + "-errbox");

    document.getElementById(key).className += " errored-input";
    box.style.display = "block";
    box.innerText = error;
}

function toggle(className, property, displayState) {
    let elements = document.getElementsByClassName(className);

    for (let i = 0; i < elements.length; i++) {
        elements[i].style = property + ":" + displayState;
    }
}

function resetErroredInput() {
    let elements = document.getElementsByClassName("errored-input");

    for (let i = 0; i < elements.length; i++) {
        elements[i].classList.remove("errored-input");
    }

}

