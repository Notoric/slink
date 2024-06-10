const expiry_checkbox = document.getElementById('expiry-toggle');

function toggleExpiryElements() {
    const expirydate = document.getElementById('expiry-date');
    const expiryhour = document.getElementById('expiry-hour');
    const expiryminute = document.getElementById('expiry-minute');

    if (expiry_checkbox.checked) {
        expirydate.disabled = false;
        expiryhour.disabled = false;
        expiryminute.disabled = false;
    } else {
        expirydate.disabled = true;
        expiryhour.disabled = true;
        expiryminute.disabled = true;
    }
}

expiry_checkbox.addEventListener('change', toggleExpiryElements);

toggleExpiryElements();

const destination_button = document.getElementById('destination_clipboard');
const url_button = document.getElementById('url_clipboard');

function clipboardButton(event) {
    event.preventDefault();
    let button = event.target;
    let parent = button.parentElement;
    let url = parent.getAttribute('href');
    navigator.clipboard.writeText(url).then(() => {
        button.textContent = '✔️';
        setTimeout(() => {
            button.textContent = '🔗';
        }, 2000);
    });
}

destination_button.addEventListener('click', event => clipboardButton(event));
url_button.addEventListener('click', event => clipboardButton(event));