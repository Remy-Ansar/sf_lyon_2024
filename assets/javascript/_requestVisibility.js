export async function sendVisibilityRequest(url, label = null) {
    const response = await fetch(url);
    const data = await response.json();

    if (response.ok) {


        label.innerText = data.enable ? 'Actif' : 'Non actif';
    } else {
        const flash = document.createElement('div');
        flash.classList.add('alert', 'alert-danger')
        flash.setAttribute('role', 'alert');
        flash.setAttribute('id', 'alert-visibility');


        flash.innerHTML = `
            <i class="bi bi-exclamation-octagon-fill"></i>
            ${data.message}
        `;

        const main = document.querySelector('main');

        if (main.querySelector('#alert-visibility')) {
            main.querySelector('#alert-visibility').remove();
        }

        main.prepend(flash);

    }
}