function removeCollectionForm(e) {
    e.currentTarget.closest('li').remove();
}

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');
    item.classList.add('col-md-5');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    const btnRemove = document.createElement('button')
    btnRemove.setAttribute('type', 'button');
    btnRemove.classList.add('btn', 'btn-danger', 'text-light', 'btn-remove-collection')
    btnRemove.innerHTML = '<i class="bi bi-x-octagon-fill"></i>';

    item.prepend(btnRemove);

    collectionHolder.appendChild(item);

    btnRemove.addEventListener('click', removeCollectionForm);

    collectionHolder.dataset.index++;
};



document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

document.querySelectorAll('.btn-remove-collection')
    .forEach(btnRemove => {
        btnRemove.addEventListener('click', removeCollectionForm);
    });