import {Controller} from "@hotwired/stimulus";
import {fetchOptions} from "../js/common";

/* stimulusFetch: 'lazy' */
export default class Ajax extends Controller<HTMLAnchorElement> {
  static values = {
    method: String,
  };

  declare methodValue: string;

  sendAjax(event: Event): void {
    event.preventDefault();

    fetch(this.element.href, {
      ...fetchOptions,
      method: this.methodValue,
    })
      .then((r) => {
        if (r.status === 200) {
          return r.json();
        }
        throw new Error("La requête à échouée !");
      })
      .then(() => {
        // TODO: Toast
        // TODO: recharger sans recharger la page
        window.location.reload();
      })
      .catch((reason) => {
        console.error(reason);
      });
  }

  askForConfirmation(event: Event): void {
    event.preventDefault();

    if (window.confirm("êtes-vous sûre ?")) {
      this.sendAjax(event);
    }
  }
}
