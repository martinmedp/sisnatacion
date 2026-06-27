document.querySelectorAll('.form-eliminar').forEach(form => {
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const nombre = this.dataset.nombre ?? 'este registro';
    Swal.fire({
      title: '¿Eliminar ' + nombre + '?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});