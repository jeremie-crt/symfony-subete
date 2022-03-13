const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
    const item = document.createElement('li');

    item.innerHTML = collectionHolder.dataset.prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );
    //Add delete btn to the item element
    let btn = document.createElement('button')
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
    btn.className = 'btn btn-sm btn-danger btn-delete'
    btn.style.marginTop = '-26px';
    item.insertAdjacentElement('beforeend', btn);

    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

    btnDeleteEvent()
};

document.querySelectorAll('.add_item_link')
    .forEach(btn => btn.addEventListener("click", addFormToCollection));

const btnDeleteEvent = () => {
    document.querySelectorAll('.btn-delete')
        .forEach(btn => btn.addEventListener('click', (e) => {
            e.preventDefault();
            let element = e.target.tagName;
            //Manage the click with the svg
            if(element === 'path') {
                e.target.parentNode.parentNode.parentNode.remove();
            } else if(element === 'svg') {
                e.target.parentNode.parentNode.remove();
            } else {
                e.target.parentNode.remove();
            }
        }))
}
