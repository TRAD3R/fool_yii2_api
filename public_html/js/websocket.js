(function () {
  window.onload = function () {
    // var socket = new WebSocket("ws://echo.websocket.org");
    // var socket = new WebSocket('ws://test.exsales.net:8585');
    var socket = new WebSocket('ws://fool.trd:8585');
    var status = document.querySelector('#status');

    socket.onopen = function (ev) {
      status.innerHTML = 'Соединение установлено';
    };

    socket.onclose = function (ev) {
      if(ev.wasClean || ev.code == 1006) {
        status.innerHTML = 'Соединение закрыто';
      }else{
        status.innerHTML = 'Соединение было как-то закрыто';
      }

      status.innerHTML += '<br> код: ' + ev.code + '; причина: ' + ev.reason;
    };

    socket.onmessage = function (ev) {
      // let result = JSON.parse(ev.data);
      status.innerHTML = 'Ответ сервера: ' + ev.data;
    };

    document.forms.message.onsubmit = function () {
      let message = {
        request: this.login.value,
        // email: this.msg.value,
        // password: '321'
        authKey: 'E7afJZ08z7i9ctkOdf-h'
      };

      socket.send(JSON.stringify(message));
      return false;
    };
  };
})();