import '../../styles/admin/liste.scss';

const deleteButtons = document.querySelectorAll('.js-confirm');

deleteButtons.forEach((button) => {
    button.addEventListener('click', (e) => {
        if (!confirm('êtes-vous sûr ?')) {
            e.preventDefault();
        }
    });
});
