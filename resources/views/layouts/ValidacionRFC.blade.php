<script>
// JavaScript para la validación del formulario
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity() || !validateRFC()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
          } else {
            form.classList.remove('was-validated');
          }
        }, false)
      })
  })()

  function validateRFC() {
    const curp = document.getElementById('curp').value;
    const rfc = document.getElementById('rfc').value;
    const errorMessage = document.getElementById('rfc-error-message');
    
    if (curp.substring(0, 10) !== rfc.substring(0, 10)) {
      errorMessage.style.display = 'block';
      return false;
    } else {
      errorMessage.style.display = 'none';
      return true;
    }
  }

</script>