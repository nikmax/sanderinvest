(function () {
  "use strict";
  let form = document.getElementById('php-register-form');
  if(!form) return;
  form['coemail'].onpaste = function(e) {e.preventDefault();};

  form.addEventListener( 'submit', function(e) {
      e.preventDefault();
      let thisForm = this;
      thisForm.querySelector('.loading').classList.remove('d-block')
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block')
      let action = thisForm.getAttribute( 'action' );
      if( !action ) {
          displayError(thisForm, 'The form action property is not set!')
          return;}
      let formData = new FormData( thisForm );
      form_submit(thisForm, action, formData);
      });

  function form_submit(thisForm, action, formData) {
      if(!formData.has("terms")) return;
      else formData.delete("terms");
      for (const value of formData.values()) if( value == "" ) return;
      console.log(formData.get("first_name"));
      console.log(formData.get("last_name"));
      console.log(formData.get("email"));
      console.log(formData.get("coemail"));
      if( formData.get('email') != formData.get('coemail') ) {
          displayError(thisForm,'emails are different!');
          return;}
      formData.append("create","create");
      thisForm.querySelector('.loading').classList.add('d-block');
      fetch(action, {
          method: 'POST',
          body: formData,
          headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => {
          return response.text();
        })
        .then(data => {
          thisForm.querySelector('.loading').classList.remove('d-block');
          const res = data.split(",",2);
          if (res[0] == 'OK') {
            thisForm.querySelector('.sent-message').classList.add('d-block');
            if (res[1] == 'OK') thisForm.reset(); 
          } else {
            throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action); 
          }
        })
        .catch((error) => {
          displayError(thisForm, error);
        });


      }



  function displayError(thisForm, error) {
      thisForm.querySelector('.loading').classList.remove('d-block');
      thisForm.querySelector('.error-message').innerHTML = error;
      thisForm.querySelector('.error-message').classList.add('d-block'); }

})();
