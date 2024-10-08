const validation = new JustValidate("#signup");

validation
    .addField("#name", [
        {
            rule: ""
        }
    ])  
    .addField("#email", [
        {
            rule: ""
        },
        {
            rule: "email"
        },
        {
            validator: (value) => () => {
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(json) {
                            return json.available;
                        });
            },
            errorMessage: "email already taken"
        }
    ])  
    .addField("#password", [
        {
            rule: ""
        },
        {
            rule: "password"
        }
    ])  
    .addField("#password_confirmation", [
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords should match"
        }
    ])  
    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });
