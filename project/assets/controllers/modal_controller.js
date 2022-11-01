import { Controller } from '@hotwired/stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    static targets = ['modal', 'modalBody', 'modalContent', 'content'];

    openModal(event) {
        const modal = new Modal(this.modalTarget);
        this.modalBodyTarget.innerHTML = event.currentTarget.dataset.modalBody;
        modal.show();
    }
}