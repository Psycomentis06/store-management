/**
 * Script to create the add remove fields
 */

window.addEventListener('load', () => {
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
        const [addButtonEl, item] = addButton(formContainer[i], itemNumber)
        formContainer[i].appendChild(addButtonEl);
        itemNumber = item
        // Add Delete button
        for (let j = 0; j < formContainer[i].children.length - 1; j++) {
            const row = formContainer[i].children[j];
            row.appendChild(deleteButton(row))
        }
    }
})

function addButton(container, itemNumber) {
    const addButton = document.createElement('button');
    addButton.classList.add('btn', 'btn-primary');
    addButton.setAttribute('type', 'button')
    addButton.innerText = 'Add';
    addButton.addEventListener('click', () => {
        itemNumber++
        addElement(container, itemNumber);
    })

    return [addButton, itemNumber]
}

function deleteButton(parent) {
    parent.classList.add('align-items-end')
    const delRow = document.createElement('div')
    delRow.classList.add('col-2')
    const delBtn = document.createElement('button');
    delBtn.classList.add('btn', 'btn-danger', 'text-center');
    delBtn.setAttribute('type', 'button')
    delBtn.innerHTML = '<i class="las la-times"></i>';
    delRow.appendChild(delBtn)
    delRow.addEventListener('click', () => {
        parent.remove();
    })
    return delRow;
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
    if (container.children.length === 1) {
        // 1 is the Add button
        container.innerHTML = container.getAttribute('data-prototype').replaceAll('__name__', '0')
        container.appendChild(addButton(container, 0)[0])
        //container.children[0].appendChild(deleteButton(container.children[0]))
    } else {
        const cloned = container.children[0].cloneNode(true);
        cloned.children[2].remove();
        const labelKey = cloned.children[0].children[0]
        const inputKey = cloned.children[0].children[1]
        inputKey.value = ''
        const inputKeyId = inputKey.getAttribute('id').replace(/\d/, index + '')
        labelKey.setAttribute('for', inputKeyId)
        inputKey.setAttribute('id', inputKeyId)
        inputKey.setAttribute('name', inputKey.getAttribute('name').replace(/\d/, index + ''))
        const labelValue = cloned.children[1].children[0]
        const inputValue = cloned.children[1].children[1]
        inputValue.value = ''
        const inputValueId = inputValue.getAttribute('id').replace(/\d/, index + '')
        labelValue.setAttribute('for', inputValueId)
        inputValue.setAttribute('id', inputValueId)
        inputValue.setAttribute('name', inputValue.getAttribute('name').replace(/\d/, index + ''))
        cloned.appendChild(deleteButton(cloned))
        container.insertBefore(cloned, container.children[container.children.length - 1]);
    }
}