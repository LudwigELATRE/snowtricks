document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form[name="comment"]');
    const contentInput = form.querySelector('#comment_content');
    const errorMessage = document.createElement('div');
    errorMessage.style.color = 'red';

    form.addEventListener('submit', function(event) {
        const contentLength = contentInput.value.length;
        if (contentLength < 5 || contentLength > 1000) {
            event.preventDefault();
            errorMessage.innerText = 'Votre commentaire doit comporter entre 5 et 1000 caractères.';
            if (!contentInput.nextElementSibling) {
                contentInput.after(errorMessage);
            }
        }
    });
});