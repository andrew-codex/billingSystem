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
    const emailInput = document.getElementById("staff-email");
    const phoneInput = document.getElementById("phone");
    const emailError = document.getElementById("email-error");
    const phoneError = document.getElementById("phone-error");
    const form = document.getElementById("staffForm");

    function showError(input, errorElement, message) {
        input.classList.add("invalid");
        errorElement.textContent = message;
        errorElement.style.color = "red";
    }

    function clearError(input, errorElement) {
        input.classList.remove("invalid");
        errorElement.textContent = "";
    }

    function validatePhone() {
        const value = phoneInput.value.trim();
        phoneInput.value = value.replace(/\D/g, "").slice(0, 11);

        if (phoneInput.value === "") {
            clearError(phoneInput, phoneError);
            return false;
        }

        const pattern = /^[0-9]{10,11}$/;
        if (!pattern.test(phoneInput.value)) {
            showError(phoneInput, phoneError, "Must be 09XXXXXXXXX.");
            return false;
        }

        clearError(phoneInput, phoneError);
        return true;
    }

    let emailTimeout;
    async function validateEmail() {
        const value = emailInput.value.trim();
        clearError(emailInput, emailError);

        if (value === "") {
            showError(emailInput, emailError, "Email is required.");
            return false;
        }

        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!pattern.test(value)) {
            showError(emailInput, emailError, "Please enter a valid email address.");
            return false;
        }

        if (emailTimeout) clearTimeout(emailTimeout);
        return new Promise(resolve => {
            emailTimeout = setTimeout(() => {
                fetch(`/staff/check-email?email=${encodeURIComponent(value)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.available) {
                            showError(emailInput, emailError, "Email already exists.");
                            resolve(false);
                        } else {
                            clearError(emailInput, emailError);
                            resolve(true);
                        }
                    })
                    .catch(() => {
                        showError(emailInput, emailError, "Error checking email.");
                        resolve(false);
                    });
            }, 500); 
        });
    }

    emailInput.addEventListener("input", validateEmail);
    phoneInput.addEventListener("input", validatePhone);

    form.addEventListener("submit", async e => {
        e.preventDefault();
        const emailValid = await validateEmail();
        const phoneValid = validatePhone();

        if (emailValid && phoneValid) {
            form.submit();
        }
    });
});
