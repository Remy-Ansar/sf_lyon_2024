import { sendVisibilityRequest } from './_requestVisibility'

const inputs = document.querySelectorAll('input[data-switch-article]');

inputs.forEach((item) => {
    item.addEventListener('change', (e) => {
        const id = e.target.dataset.switchArticle;

        sendVisibilityRequest(`/admin/articles/${id}/switch`, e.currentTarget.nextElementSibling);
    });
});