(function () {
  // "use strict";
  let f = document.getElementById('php-register-form');
  if(f){
  f['email2'].onpaste = function(e) {e.preventDefault();};
  f.addEventListener('submit', function(e) {
      e.preventDefault();
      let f = this;
      form_submit(f);
    });
  function form_submit(f) {
    f.querySelector('.alert-danger').classList.remove('show');
    let xhttp = new XMLHttpRequest();
    
    //f.getAttribute('action')
    if(f['first_name'].value   == '') return;
    if(f['last_name'].value  == '') return;
    if(f['email'].value  == '') return;
    if(f['email2'].value  == '') return;
    if(!f['terms'].checked ) return;
    if(f['email'].value != f['email2'].value) displayError(f,'please confirm your email');
    else{
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4){
          let f = document.getElementById('php-register-form');
          if(this.status == 200) {
            if(this.responseText.trim() == 'OK') window.location.href += '&confirm';
            else displayError(f, this.responseText); 
          }else displayError(f,'error sending form data');
        }
      };
      xhttp.open("POST", f.getAttribute('action'), true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send(
        'first_name='+f['first_name'].value+
        'last_name='+f['last_name'].value+
        'email='+f['email'].value);
    }
  };

  function displayError(f, e) {
    f.querySelector('.alert-danger').innerHTML = e;
    f.querySelector('.alert-danger').classList.add('show');
    };
}
})();
