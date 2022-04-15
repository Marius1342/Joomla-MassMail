const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
if (urlParams.get('ok') != undefined) {
    window.alert(urlParams.get('ok'));
}
var selected = document.getElementById('select');
document.getElementById('loc').value = window.location.href;
var form = document.getElementById('emails');
var List = document.getElementById('list');
var submit = document.getElementById('submite');
var children = [''];
var emails = [''];
function CreatChild(username) {
    var child = document.createElement('p');
    child.addEventListener('click', function () {
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
        if (List.childNodes[i].innerText == username) {
            List.removeChild(List.childNodes[i]);
        }
    }
    if (children.length > 1) {
        submit.disabled = false;
    }
    else {
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
    var child = CreatChild(username);
    List.appendChild(child);
    children.push(username);
    emails.push(email);
    submit.disabled = false;
    form.value = JSON.stringify(emails);
}
