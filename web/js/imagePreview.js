// Affiche un aperçu de l'image sélectionnée et met à jour le nom du fichier
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.file-upload').forEach(function (container) {
    var input = container.querySelector('input[type="file"]');
    var fileName = container.querySelector('.file-name');
    if (!input) return;

    var preview = container.querySelector('.image-preview');
    var currentObjectUrl = null;

    function clearPreview() {
      if (preview && preview.parentNode) {
        preview.parentNode.removeChild(preview);
      }
      preview = null;
      if (currentObjectUrl) {
        URL.revokeObjectURL(currentObjectUrl);
        currentObjectUrl = null;
      }
    }

    input.addEventListener('change', function () {
      var file = input.files && input.files[0];
      if (file) {
        if (fileName) fileName.textContent = file.name;
        if (file.type && file.type.indexOf('image') === 0) {
          if (!preview) {
            preview = document.createElement('img');
            preview.className = 'image-preview';
            preview.style.maxWidth = '240px';
            preview.style.maxHeight = '160px';
            preview.style.display = 'block';
            preview.style.marginTop = '0.5rem';
            preview.style.borderRadius = '6px';
          }
          if (currentObjectUrl) URL.revokeObjectURL(currentObjectUrl);
          currentObjectUrl = URL.createObjectURL(file);
          preview.src = currentObjectUrl;
          container.appendChild(preview);
        } else {
          // fichier non-image sélectionné
          clearPreview();
        }
      } else {
        if (fileName) fileName.textContent = 'Aucun fichier sélectionné';
        clearPreview();
      }
    });

    // Lors d'un reset du formulaire, réinitialiser l'aperçu
    var form = input.closest('form');
    if (form) {
      form.addEventListener('reset', function () {
        if (fileName) fileName.textContent = 'Aucun fichier sélectionné';
        input.value = '';
        clearPreview();
      });
    }
  });
});
