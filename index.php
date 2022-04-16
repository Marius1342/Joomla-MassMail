<?php

?>

<?php
$checkForAdmin = true;
$group = 2;

if($checkForAdmin){
//Check for admin
$user = JFactory::getUser();
if ($user->authorise('core.admin') == false) {
    echo "No access please login with a super user account";
    exit();
}
}
?>
<style type='text/css'>
.item {
    float: left;
    margin-left: 10px;

}

.hidden {
    display: none;

}

.List {

    overflow: auto;

}

.item:hover {
    color: blue;

}

.textarea {
    width: 15%;

}

textarea {
    width: 30%;
    height: 30%;
}
</style>


<h2>All selected users</h2>
<div class='List' id='list'>


</div>
<br>
<p></p>
<div></div>
<br>
<label for='select_'>Select a user</label>
<div name='select_'>
    <select id='select'>
        <option>Please choose a user</option>

        <?php

        $URL = "https://example.url";
        //Getting the username
        use Joomla\CMS\Factory;
        //For getting all Users
        jimport('joomla.access.access');
        //Getting the User
        jimport('joomla.user.helper');

        if (isset($_GET['location']) && isset($_GET['emails']) && $_GET['header'] && $_GET['body']) {
            $ok = "One user must be set";
            $emails = json_decode($_GET['emails'], true);
            $body = $_GET['body'];
            $header = $_GET['header'];
            array_shift($emails);
            $emails = array_values($emails);
            if (count($emails) == 0) {
                header("Location: " . $_GET['location'] . "?ok=" . $ok);
                exit();
            }
            $mailer = JFactory::getMailer();
            //Set up the mailer
            $config = JFactory::getConfig();
            $sender = array(
                $config->get('mailfrom'),
                $config->get('fromname')
            );
            $mailer->setSender($sender);
            $mailer->addRecipient($emails);
            $mailer->setSubject($header);
            $mailer->setBody($body);
            $send = $mailer->Send();
            if ($send !== true) {
                $ok = 'The email was not send';
            } else {
                $ok = 'The Email has been sent';
            }

            echo $ok;
            header("Location: " . $_GET['location'] . "?ok=" . $ok);
            exit();
        }
        //Change this to your Group order
        $users = JAccess::getUsersByGroup($group);

        $user_array = array();

        foreach ($users as &$value) {
            array_push($user_array, array('name' => JFactory::getUser($value)->username, 'email' => JFactory::getUser($value)->email));
        }
        //Sort users
        usort($user_array, function ($a, $b) {
            return $a['name'] <=> $b['name'];
        });
        foreach ($user_array as $value) {
            echo '<option onclick="AddList(\'' . $value["email"] . '\', \'' . $value["name"] . '\')">' . $value["name"] . '</option>';
        }
        //print 
        ?>
    </select>

    <form method='GET' id='FORM'>
        <input type='text' hidden class='hidden' id='loc' name='location' value=''>
        <input type='text' hidden id='emails' style='display:none' name='emails' class='textarea' value=''>
        <br>
        <label for='header'>Subject</label>
        <br>
        <input type='text' name='header' required>
        <br>
        <label for='body'>Text</label>
        <br>
        <textarea name='body' required></textarea>
        <br>
        <input type='submit' value='Send' id='submite' disabled>

    </form>
    <a href='".$URL."'>Main Page</a>
</div>
<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

function RemoveStringAt(string, Start, End) {
    return string.slice(0, Start) + string.slice(End);
}
if (urlParams.get('ok') != undefined) {
    window.alert(urlParams.get('ok'));
    //Remove ok response
    let indexOk = queryString.indexOf("ok");
    let toChar = indexOk;
    let clearAll = true;
    for (let i = indexOk; i < queryString.length; i++) {
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
var selected = document.getElementById('select');
document.getElementById('loc').value = window.location.href;
var form = document.getElementById('emails');
var List = document.getElementById('list');
var submit = document.getElementById('submite');
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
        if (List.childNodes[i].innerText == username) {
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
</script>