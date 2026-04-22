
var registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function(e) {
        var login = document.getElementById("regLogin").value.trim();
        var email = document.getElementById("regEmail").value.trim();
        var haslo = document.getElementById("regHaslo").value;
        var hasloPowtorz = document.getElementById("regHasloPowtorz").value;
        var isValid = true;

       
        clearErrors();

        
        if (login.length < 3) {
            showFieldError("regLoginError", "Login musi mieć min. 3 znaki.");
            isValid = false;
        }

        
        if (!isValidEmail(email)) {
            showFieldError("regEmailError", "Podaj prawidłowy adres email.");
            isValid = false;
        }

        
        if (haslo.length < 6) {
            showFieldError("regHasloError", "Hasło musi mieć min. 6 znaków.");
            isValid = false;
        }

        
        if (haslo !== hasloPowtorz) {
            showFieldError("regHasloPowtorzError", "Hasła nie są identyczne.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
}


var addProductForm = document.getElementById("addProductForm");

if (addProductForm) {
    addProductForm.addEventListener("submit", function(e) {
        var nazwa = document.getElementById("prodNazwa").value.trim();
        var opis = document.getElementById("prodOpis").value.trim();
        var cena = document.getElementById("prodCena").value;
        var isValid = true;

        clearProductErrors();

        if (nazwa.length < 2) {
            showFieldError("prodNazwaError", "Nazwa musi mieć min. 2 znaki.");
            isValid = false;
        }

        if (opis.length > 500) {
            showFieldError("prodOpisError", "Opis jest za długi (max 500 znaków).");
            isValid = false;
        }

        if (!cena || parseFloat(cena) <= 0) {
            showFieldError("prodCenaError", "Podaj cenę większą od 0.");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
}


function isValidEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function showFieldError(elementId, message) {
    var el = document.getElementById(elementId);
    if (el) {
        el.textContent = message;
    }
}

function clearErrors() {
    var errors = document.querySelectorAll("#registerForm .field-error");
    for (var i = 0; i < errors.length; i++) {
        errors[i].textContent = "";
    }
}

function clearProductErrors() {
    var errors = document.querySelectorAll("#addProductForm .field-error");
    for (var i = 0; i < errors.length; i++) {
        errors[i].textContent = "";
    }
}


function confirmCzyKupic(nazwa) {
    return confirm("Czy na pewno chcesz kupić produkt: \"" + nazwa + "\"?");
}


var searchInput = document.getElementById("searchInput");

if (searchInput) {
    searchInput.addEventListener("input", function() {
        var filter = this.value.toLowerCase().trim();
        var rows = document.querySelectorAll("#productsTable tbody tr.product-row");

        for (var i = 0; i < rows.length; i++) {
            var name = rows[i].getAttribute("data-name");
            if (name.indexOf(filter) !== -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
}