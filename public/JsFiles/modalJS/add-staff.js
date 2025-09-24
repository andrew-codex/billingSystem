function openAddStaff(forceReset = true) {
    const modal = document.querySelector(".add-staff");
    const form = document.getElementById("staffForm");


    form.querySelectorAll(".error-message").forEach(el => {
        if (el.id !== "email-error") el.remove();
    });


    const emailError = document.getElementById("email-error");
    if (emailError) emailError.textContent = "";

    form.querySelectorAll(".invalid").forEach(el => el.classList.remove("invalid"));

    modal.classList.add('active');
}


function closedAddStaff() {
    const modal = document.querySelector(".add-staff");
    const form = document.getElementById("staffForm");

    modal.classList.remove('active');

   
    form.querySelectorAll("input, select").forEach(input => {
        if (input.type === "select-one") {
            input.selectedIndex = 0;
        } else {
            input.value = "";
        }
    });

 
    form.querySelectorAll(".error-message").forEach(el => el.remove());
    form.querySelectorAll(".invalid").forEach(el => el.classList.remove("invalid"));
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("staffForm");
    const emailInput = document.getElementById("staff-email");
    const passwordInput = form.querySelector("input[name='password']");
    const phoneInput = document.getElementById("phone");
    const emailError = document.getElementById("email-error");

    function showError(input, message) {
        let group = input.closest(".form-group");
        let errorMsg = group.querySelector(".error-message");
        if (!errorMsg) {
            errorMsg = document.createElement("p");
            errorMsg.classList.add("error-message");
            group.appendChild(errorMsg);
        }
        errorMsg.textContent = message;
        input.classList.add("invalid");
    }

    function clearError(input) {
        const group = input.closest(".form-group");
        const errorMsg = group.querySelector(".error-message");
        if (errorMsg && errorMsg.id !== "email-error") errorMsg.remove();
        input.classList.remove("invalid");
    }

    function validateInput(input) {
        const value = input.value.trim();
        clearError(input);

        
        if (input === emailInput) {
            if (value === "") {
                emailError.textContent = "";
                input.classList.remove("invalid");
                return false;
            }
               

            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!pattern.test(value)) {
                emailError.textContent = "Please enter a valid email address.";
                emailError.style.color = "red";
                input.classList.add("invalid");
                return false;
            }

            fetch(`/staff/check-email?email=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        emailError.textContent = "Email already exists.";
                        emailError.style.color = "red";
                        input.classList.add("invalid");
                    } else {
                        emailError.textContent = "";
                        input.classList.remove("invalid");
                    }
                })
                .catch(() => {
                    emailError.textContent = "Error checking email.";
                    emailError.style.color = "red";
                    input.classList.add("invalid");
                });
        }

     
        if (input === passwordInput) {
            if (value === "") return true; 

            const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
            if (!pattern.test(value)) {
                showError(
                    input,
                    "Password must be at least 8 characters, include an uppercase letter, a number, and a special character (@$!%*?&)."
                );
                return false;
            }
        }

        if (input === phoneInput) {
            if (value === "") return true; 

            const pattern = /^[0-9]+$/;
            if (!pattern.test(value)) {
                showError(input, "Phone number must contain only digits.");
                return false;
            }
        }

        return true;
    }

   
    phoneInput.addEventListener("input", () => {
        phoneInput.value = phoneInput.value.replace(/\D/g, "");
        validateInput(phoneInput);
    });

    [emailInput, passwordInput, phoneInput].forEach(input => {
        input.addEventListener("blur", () => validateInput(input));
        input.addEventListener("input", () => validateInput(input));
    });

    form.addEventListener("submit", e => {
        let valid = true;
        [emailInput, passwordInput, phoneInput].forEach(input => {
            if (!validateInput(input)) valid = false;
        });
        if (!valid) e.preventDefault();
    });
});
