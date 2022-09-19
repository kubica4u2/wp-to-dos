class Todo {

    apiUrl = '/wp-json/todos/v2/update_todos';
    apiCreateUrl = '/wp-json/todos/v2/create_todos';
    apiDeleteUrl = '/wp-json/todos/v2/delete_todos';
    apiCreateSubTaskUrl =  '/wp-json/todos/v2/subtask_todos';
    clickCount = 0;

    addPost(e) {
        if(e.keyCode === 13 || e.key === 'Enter') {
            this.addItem();
        }
    }

    preventDefault(e) {
        if(e.keyCode === 13 || e.key === 'Enter') {
            e.preventDefault();
        }
    }

    updateItem( e,  element ) {
        if( element !== undefined ) {
            let id = element.dataset.id;
            let checked = document.getElementById(`checkbox-${id}`).checked;
            let text = document.getElementById(`todo-text-${id}`).innerHTML;

            let data = {
                id,
                checked,
                text
            }

            if( text === '' ) {

                if(data.id === id) {
                    this.clickCount++;
                }

                if(this.clickCount === 2) {
                    this.sendData(data, 'delete').then(() => this.deleteItem( data ));
                    this.clickCount = 0;
                }

            } else {
                this.sendData(data, 'update').then();
            }
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
                            <div class="flex flex-center py-20">
                                <input type="checkbox" onchange="new Todo().updateItem( this )" data-id="${data.id}" id="checkbox-${data.id}">
                                <label contenteditable="true" data-checked="checked" data-id="${data.id}" id="todo-text-${data.id}" onkeydown='todo.preventDefault(event)' onkeyup="todo.updateItem( this ), todo.createSubTask(event, this)">
                                    ${text}
                                </label>
                            </div>
                        </li>
                    `;
                    esTodos.innerHTML += newItem;
                }
            }).then(() => location.reload());

        }

    }

    createSubTask( e, element ) {
        if(e.keyCode === 13 || e.key === 'Enter') {
            e.preventDefault();
            let id = element.dataset.id;
            let lineItem = document.getElementById(`line-item-${id}`);

            let data = {
                id: new Date().valueOf(),
                parentId: id,
                checked: false,
                text: 'New Item'
            }
            this.sendData(data, 'createsubtask').then(r => {
                let newItem = `
                <ul>
                    <li class="es-task-item" id="line-item-${data.id}">
                        <div class="flex flex-center py-20">
                            <input type="checkbox" onchange="new Todo().updateItem( this )" data-id="${data.id}" id="checkbox-${data.id}">
                            <label contenteditable="true" data-checked="checked" data-id="${data.id}" id="todo-text-${data.id}" onkeydown='todo.preventDefault(event)' onkeyup="todo.updateItem( this ), todo.createSubTask(event, this)">
                                ${data.text}
                            </label>
                        </div>
                    </li>
                </ul>
                `;
                lineItem.innerHTML += newItem;
            }).then(() => location.reload());
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
            case 'createsubtask' :
                url = this.apiCreateSubTaskUrl;
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
