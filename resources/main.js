const sel = (selector, element = document) => {return element.querySelector(selector)};

const on = (eventType, identifier, parentIdentifier, componentName, renderFunction) => {
    const parent = sel(`[x-identifier="${parentIdentifier}"]`);
    parent.addEventListener(eventType, async (e) => {
        let structure = JSON.parse(parent.getAttribute('x-structure'));

        if (e.target.matches(`[x-identifier="${identifier}"]`)) {
            structure.method = renderFunction;
            structure.component = componentName;
            structure.component = componentName;
            await updateComponent(parent, structure);
        }
    });
};

const bind = (identifier, parentIdentifier, componentName, property) => {
    const parent = sel(`[x-identifier="${parentIdentifier}"]`);
    parent.addEventListener('input', async (e) => {
        let element = e.target;
        let structure = JSON.parse(parent.getAttribute('x-structure'));

        if (element.matches(`[x-identifier="${identifier}"]`)) {
            structure.method = null;
            structure.component = componentName;
            structure.params[property] = element.value;
            await updateComponent(parent, structure);
            const input = sel(`[x-identifier="${identifier}"]`);
            input.focus();
            input.selectionStart = input.selectionEnd = input.value.length;
        }
    });
}

const updateComponent = async (parent, structure) => {
    let response = await fetch('/component_rerender', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({ data: JSON.stringify(structure) }),
    });

    response = await response.text();

    const temp = document.createElement('div');
    temp.innerHTML = response;

    parent.innerHTML = temp.firstChild.innerHTML;

    parent.setAttribute('x-structure', temp.firstChild.getAttribute('x-structure'))
}
