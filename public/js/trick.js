document.addEventListener('DOMContentLoaded', function () {
    const collectionHolder = document.querySelector('.form-collection');
    const addButton = document.querySelector('.add-image');
    const prototype = collectionHolder.dataset.prototype;

    // Function to add a remove button to a form group
    function addRemoveButton(imageForm) {
        const removeButton = imageForm.querySelector('.remove-image');
        if (!removeButton) {
            const button = document.createElement('button');
            button.type = 'button';
            button.classList.add('btn', 'btn-danger', 'remove-image');
            button.textContent = 'Supprimer';
            imageForm.appendChild(button);
            button.addEventListener('click', function () {
                imageForm.remove();
            });
        }
    }

    addButton.addEventListener('click', function () {
        console.log("la");
        const newForm = document.createElement('div');
        newForm.classList.add('form-group');
        newForm.innerHTML = prototype.replace(/__name__/g, collectionHolder.children.length);
        collectionHolder.appendChild(newForm);
        addRemoveButton(newForm);
    });

    collectionHolder.querySelectorAll('.form-group').forEach(addRemoveButton);
});