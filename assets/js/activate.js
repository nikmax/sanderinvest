
(function () {
  "use strict";


  let form = document.getElementById('form-activate');
  if(!form) return;
  form['psw2'].onpaste = (e) => {e.preventDefault();};

  form.addEventListener( 'submit', function (e) {
      e.preventDefault();
      let thisForm = this;
      thisForm.querySelector('.loading').classList.remove('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');
      //let action = thisForm.getAttribute( 'action' );
      //if( !action ) {displayError(thisForm, 'The form action property is not set!');return;}
      let formData = new FormData( thisForm );
      form_submit(thisForm, '?server', formData); });


  function form_submit(thisForm, action, formData) {
      //if(formData.get("id") == '') {displayError(thisForm, 'empty id!');return;}
      //else formData.delete("terms");
      for (const value of formData.values()) if( value == "" ) return;
      if( formData.get('psw') != formData.get('psw2') ) {
          displayError(thisForm,'passwords are different!');
          return;}
      const regex = /^(?=.*[!§$%&=?*#_])(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/g;
      if(!formData.get('psw').match(regex)) {
          displayError(thisForm,"password is simpel, must 8 or more symbols !§$%&=?*#_  a-z  A-Z  digits");
          return;}
      //formData.append("create","create");
      thisForm.querySelector('.loading').classList.add('d-block');

      fetch(action, {
          method: 'POST',
          body: formData,
          headers: {'X-Requested-With': 'XMLHttpRequest'} })
        .then(response => {
          return response.text(); })
        .then(data => {
          thisForm.querySelector('.loading').classList.remove('d-block');
          //const res = data.split(",",2);
          //if (res[0] == 'OK') {
          if(data == ''){
            thisForm.querySelector('.sent-message').classList.add('d-block');
            form.querySelectorAll("[required]").forEach((e)=>{e.removeAttribute('required')});
            thisForm.reset(); } 
          else {
            const msg = 'Form submission failed and no error message returned from: ';
            throw new Error(data ? data : msg + action); } })
        .catch((error) => {
          displayError(thisForm, error); }); }

  function displayError(thisForm, error) {
      thisForm.querySelector('.loading').classList.remove('d-block');
      thisForm.querySelector('.error-message').innerHTML = error;
      thisForm.querySelector('.error-message').classList.add('d-block'); }

  })();
