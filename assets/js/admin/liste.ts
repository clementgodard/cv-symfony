import '../../styles/admin/liste.scss';

const deleteButtons = document.querySelectorAll('.js-confirm');

deleteButtons.forEach((button) => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        if (confirm('êtes-vous sûr ?')) {
            const target = e.currentTarget;
            if (target instanceof HTMLAnchorElement) {
                const url: string = target.getAttribute('href');
                const method: string = target.dataset.method;

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
                        if (typeof data === 'string' && data === 'OK') {
                            // TODO: Toast
                            alert('SUPPRIMER !!!');
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
