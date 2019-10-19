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
                console.log(errors);
                for (let key in errors) {
                    displayError(key, errors[key].message);
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

var strength = {
    0: "Bad",
    1: "Weak",
    2: "Average",
    3: "Good",
    4: "Strong"
};

var color = {
    0: "darkred",
    1: "red",
    2: "orange",
    3: "darkorange",
    4: "green"
}

const password = document.getElementById("password");
var text = document.getElementById('password-strength-text');
var meter = document.getElementById('password-strength-meter');

password.addEventListener('input', function () {
    var val = password.value;
    var result = zxcvbn(val);

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if (val !== "") {
        text.innerHTML = "Strength: <b>" + strength[result.score] + "</b>";
        text.style.color = color[result.score];
    } else {
        text.innerHTML = "";
    }
});

function initStrengthCheck() {
    document.getElementById('password-strength').style.display = "block";
    document.getElementById('password-strength').style.color = "red";

}

function hideStrength() {
    document.getElementById('password-strength').style.display = "none";
}