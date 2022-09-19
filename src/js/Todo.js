class Todo {

    apiUrl = '/wp-json/todos/v2/update_todos';
    apiCreateUrl = '/wp-json/todos/v2/create_todos';
    apiDeleteUrl = '/wp-json/todos/v2/delete_todos';

    addPost(e) {
        if(e.keyCode === 13 || e.key === 'Enter') {
            this.addItem();
        }
    }

    updateItem( element ) {

        let id = element.dataset.id;
        let checked = document.getElementById(`checkbox-${id}`).checked;
        let text = document.getElementById(`todo-text-${id}`).innerHTML;

        let data = {
            id,
            checked,
            text
        }

        if( text === '' ) {
            this.sendData(data, 'delete').then(() => this.deleteItem( data ));
        } else {
            this.sendData(data, 'update').then();
        }

    }

    deleteItem( data ) {
        document.getElementById(`line-item-${data.id}`).remove();
    }

    addItem() {
        let text = document.getElementById('newTask').value;

        if( text !== '' ) {

            let esTodos = document.getElementById('es-todos');

            let data = {
                id: new Date().valueOf(),
                checked: false,
                text
            }

            this.sendData(data, 'create').then(r => {
                if( r === 200 ) {
                    let newItem = `
                        <li class="es-task-item" id="line-item-${data.id}">
                            <input type="checkbox" onchange="new Todo().updateItem( this )" data-id="${data.id}" id="checkbox-${data.id}">
                            <label contenteditable="true" data-checked="checked" data-id="${data.id}" id="todo-text-${data.id}" onkeyup="new Todo().updateItem( this )">
                                ${text}
                            </label>
                        </li>
                    `;
                    esTodos.innerHTML += newItem;
                }
            });

        }

    }

    createSubTask( e, element ) {

        console.log(e);

        if(e.keyCode === 13 || e.key === 'Enter') {
            e.preventDefault();
            let id = element.dataset.id;
            let lineItem = document.getElementById(`line-item-${id}`);

            let data = {
                id: new Date().valueOf(),
                checked: false,
                text: 'New Item'
            }

            let newItem = `
                <ul>
                    <li class="es-task-item" id="line-item-${data.id}">
                        <input type="checkbox" onchange="new Todo().updateItem( this )" data-id="${data.id}" id="checkbox-${data.id}">
                        <label contenteditable="true" data-checked="checked" data-id="${data.id}" id="todo-text-${data.id}" onkeyup="new Todo().updateItem( this )">
                            ${data.text}
                        </label>
                    </li>
                </ul>
            `;


            console.log(newItem);

            lineItem.innerHTMl += newItem;

        }
    }

    async sendData( data, route ) {

        let url;

        switch(route) {
            case 'create' :
                url = this.apiCreateUrl;
                break;
            case 'delete' :
                url = this.apiDeleteUrl;
                break;
            default:
                url = this.apiUrl
        }

        try {
            const post = await axios.post(url, data);
            return post.status;
        } catch (err) {
            console.log(err);
        }
    }


    useDebounce() {
        let timeout = null;

        return function ( callback, delay) {

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                callback();
            }, delay || 500);
        };
    }



}

let app = new Todo();
