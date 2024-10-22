import { Controller } from '@hotwired/stimulus';

/**
 * In your html element add data-controller="start".
 * Its html content will automatically be replaced by "Hello"
 * Example : <span data-controller="start">Good bye</span>
 */
export default class extends Controller {

    connect() {
        this.element.innerHTML = 'hello'
    }
}