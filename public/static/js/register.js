function register(event, actionLocation) {
    event.preventDefault();
    document.getElementById('reg-processor').style.display = "block";
    let error_box = document.getElementById('reg-error-box');
    let loader = document.getElementById('reg-loader');

    error_box.style.display = "none";
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
                error_box.style.display = "block";
                error_box.innerText = response.data["error"];
                console.log(response)
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}
