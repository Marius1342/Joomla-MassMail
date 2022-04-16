const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

function RemoveStringAt(string : string, Start: number, End: number) {
    return string.slice(0, Start) + string.slice(End);

}

if (urlParams.get('ok') != undefined) {
    window.alert(urlParams.get('ok'));
    //Remove ok response
    let indexOk = queryString.indexOf("ok");
    let toChar = indexOk;
    let clearAll = true;
    for (let i = indexOk; i < queryString.length; i++){
        if (queryString[i] == '&') {
            clearAll = false;
            break;
        }
        toChar++;
    }
    if (clearAll == true) {
        window.location.search = "";
    } else {
        window.location.search = RemoveStringAt(queryString, indexOk, toChar);
    }
}
var selected = <HTMLFormElement> document.getElementById('select');
(<HTMLFormElement>document.getElementById('loc')).value = window.location.href;
var form = <HTMLFormElement> document.getElementById('emails');
var List = <HTMLFormElement> document.getElementById('list');
var submit = <HTMLFormElement> document.getElementById('submite');
var children = [''];
var emails = [''];

function CreateChild(username) {
    var child = document.createElement('p');
    child.addEventListener('click', function() {
        RemoveElement(this.innerText);
    });
    child.innerText = username;
    child.className = 'item';
    return child;

}

function RemoveElement(username) {
    if (!children.includes(username)) {
        console.log('Error cant find ' + username);
        return;
    }
    var index = children.indexOf(username);
    console.log(children.splice(index, 1));
    console.log(emails.splice(index, 1));
    for (var i = 0; i < List.childNodes.length; i++) {
        if ( (<HTMLInputElement>List.childNodes[i]).innerText == username) {
            List.removeChild(List.childNodes[i]);
        }

    }
    if (children.length > 1) {
        submit.disabled = false;
    } else {
        submit.disabled = true;
    }
    form.value = JSON.stringify(emails);
}

function AddList(email, username) {
    selected.selectedIndex = 0;
    //Checks if the child already exists
    if (children.includes(username)) {
        window.alert('This user is already selected.');
        return;
    }
    //Create and append child
    var child = CreateChild(username);
    List.appendChild(child);
    children.push(username);
    emails.push(email);
    submit.disabled = false;
    form.value = JSON.stringify(emails);

}