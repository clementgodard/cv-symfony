import '../../styles/admin/liste.scss';

const deleteButtons = document.querySelectorAll('.js-confirm, .js-ajax');

deleteButtons.forEach((button) => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        if (button.classList.contains('js-ajax') || confirm('êtes-vous sûr ?')) {
            if (button instanceof HTMLAnchorElement) {
                const url: string = button.getAttribute('href');
                const method: string = button.dataset.method;

                if (url !== null && url !== '') {
                    fetch(url, {
                        method: method ?? 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then((r) => {
                        if (r.status === 200) {
                            return r.json();
                        } else {
                            console.error(r);
                        }
                    })
                    .then((data) => {
                        if (typeof data === 'string') {
                            // TODO: Toast
                            // alert(data);
                            location.reload();
                        } else {
                            console.error(data);
                            alert('Pas normal');
                        }
                    })
                }
            }
        }
    });
});
