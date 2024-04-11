function search(){
    let s = document.getElementById('search').value.trim();
    // console.log(s);
    if(s==""){
        s = "%";
    }
    s = encodeURIComponent(s);
    const url = 'search.php?str='+s;
    let xhr = new XMLHttpRequest();
    xhr.open('GET',url,true);
    xhr.onload = function(){
        if (xhr.status >= 200 && xhr.status < 300) {
            document.getElementById('contents').innerHTML = xhr.responseText;
        } else {
            console.error('Failed to load messages');
        }
    };
    xhr.send();
}
function chatWith(str){
    if(str===''){
        window.location.href = "home.php";
    }
    else{
        let url = "home.php?id="+encodeURIComponent(str);
        window.location.href = url;
    }
}

function loadContacts(){
    let xhr = new XMLHttpRequest();
    let url = "update_contacts.php";
    
    if(document.getElementById('r_id').value!=""){
        url = url + "?id=" + encodeURIComponent(document.getElementById('r_id').value) ;
    }

    xhr.open('GET',url,true);
    xhr.onload = function(){
        if(xhr.status >=200 && xhr.status < 300){
            document.getElementById('contacts').innerHTML = xhr.responseText;
        } else {
            console.error('Failed to load contacts');
        }
    };
    xhr.send();
}

let chats="";
function loadChat() {
    var xhr = new XMLHttpRequest();
    let url='message_get.php';
    if(document.getElementById('r_id').value!=""){
        url = url + "?id=" + encodeURIComponent(document.getElementById('r_id').value) ;
    }
    xhr.open('GET',url, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            document.getElementById('chat-box').innerHTML = decodeURIComponent(xhr.responseText);
            if(xhr.responseText != chats){
                chats = xhr.responseText;
                scrollToBottom();
            }
        } else {
            console.error('Failed to load messages');
        }
    };
    if(document.getElementById('chat-box') != null){
        xhr.send();
    }
}

function sendMessage() {
    var message = document.getElementById('user-input').value.trim();
    message = encodeURIComponent(message);
    if (message !== '') {
        let data = new URLSearchParams();
        data.append('message',message);
        data.append('r_id',document.getElementById('r_id').value.trim())
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'message_send.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                document.getElementById('user-input').value = '';
                // document.getElementById('chat-box').innerHTML = xhr.responseText;
                loadChat();
            } else {
                console.error('Failed to send message');
            }
        };

        xhr.send(data);
    }
}

function scrollToBottom() {
    var chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
}

document.addEventListener('DOMContentLoaded', function() {
    loadContacts();
    loadChat();
    setInterval(loadContacts, 10000);
    setInterval(loadChat, 5000);
});
