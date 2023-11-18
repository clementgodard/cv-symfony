import "../../styles/admin/liste.scss";

const deleteButtons = document.querySelectorAll(".js-confirm, .js-ajax");

deleteButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    if (
      button.classList.contains("js-ajax") ||
      window.confirm("êtes-vous sûr ?")
    ) {
      if (button instanceof HTMLAnchorElement) {
        const url: string = button.getAttribute("href");
        const { method } = button.dataset;

        if (url !== null && url !== "") {
          fetch(url, {
            method: method ?? "GET",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          })
            .then((r) => {
              if (r.status === 200) {
                return r.json();
              }
              throw new Error("La requête à échouée !");
            })
            .then((data) => {
              if (typeof data === "string") {
                // TODO: Toast
                window.location.reload();
              } else {
                throw new Error(data);
              }
            })
            .catch((reason) => {
              console.error(reason);
            });
        }
      }
    }
  });
});
