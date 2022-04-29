/**
 * Script to create the add remove fields
 */

window.onload = () => {
    const formContainer = document.querySelectorAll('.json_key_value_form');
    for (let i = 0; i < formContainer.length; i++) {
        const prototype = formContainer[i].getAttribute('data-prototype');
        let itemNumber = 0;
        if (formContainer[i].children.length <= 0) {
            // There is no initial data returned from Server, so we will create it
            formContainer[i].innerHTML = prototype.replaceAll('__name__', '0');
        } else {
            // Index starts from 0 not 1
            itemNumber = formContainer[i].children.length - 1;
        }
        // We create the Add button
        const addButton = document.createElement('button');
        addButton.classList.add('btn', 'btn-primary');
        addButton.setAttribute('type', 'button')
        addButton.innerText = 'Add';
        addButton.addEventListener('click', () => {
            console.log(itemNumber)
            addElement(formContainer[i], itemNumber);
            itemNumber++;
        })
        formContainer[i].appendChild(addButton);
    }
}

function getValue(el) {
    return getRow(el).children[1];
}

function getKey(el) {
    return getRow(el).children[0];
}

function getRow(container) {
    return container
}

function addElement(container, index = 0) {
    const cloned = container.children[0].cloneNode(true);
    const labelKey = cloned.children[0].children[0]
    const inputKey = cloned.children[0].children[1]
    inputKey.value = ''
    const inputKeyId = inputKey.getAttribute('id').replace(/\d/g, index + '')
    labelKey.setAttribute('for', inputKeyId)
    const labelValue = cloned.children[1].children[0]
    const inputValue = cloned.children[1].children[1]
    inputValue.value = ''
    const inputValueId = inputValue.getAttribute('id').replace(/\d/g, index + '')
    labelValue.setAttribute('for', inputValueId)

    container.insertBefore(cloned, container.children[container.children.length - 1]);
}

// TODO
function removeElement() {

}