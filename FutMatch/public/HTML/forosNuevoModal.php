<!-- Modal Nuevo Foro -->
<div class="modal fade" id="modalNuevoForo" tabindex="-1" aria-labelledby="modalNuevoForoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalNuevoForoLabel">
          <i class="bi bi-chat-dots"></i> Crear Nuevo Foro
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoForo" enctype="multipart/form-data">
          <!-- Título del foro (Obligatorio) -->
          <div class="mb-3">
            <label for="tituloForo" class="form-label">
              <i class="bi bi-type"></i> Título del foro <span class="text-danger">*</span>
            </label>
            <input 
              type="text" 
              class="form-control" 
              id="tituloForo" 
              name="titulo" 
              placeholder="Ingresa un título llamativo para tu foro"
              required
              maxlength="100"
            >
            <div class="form-text">Máximo 100 caracteres</div>
          </div>

          <!-- Texto del foro (Opcional) -->
          <div class="mb-3">
            <label for="textoForo" class="form-label">
              <i class="bi bi-text-paragraph"></i> Contenido del foro
            </label>
            <textarea 
              class="form-control" 
              id="textoForo" 
              name="texto" 
              rows="5" 
              placeholder="Describe de qué se trata tu foro, comparte información relevante o haz una pregunta a la comunidad..."
              maxlength="500"
            ></textarea>
            <div class="form-text">Máximo 500 caracteres (opcional)</div>
          </div>

          <!-- Foto del foro (Opcional) -->
          <div class="mb-3">
            <label for="fotoForo" class="form-label">
              <i class="bi bi-image"></i> Imagen del foro
            </label>
            <input 
              type="file" 
              class="form-control" 
              id="fotoForo" 
              name="foto" 
              accept="image/*"
            >
            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB (opcional)</div>
            
            <!-- Vista previa de la imagen -->
            <div id="previewContainer" class="mt-3" style="display: none;">
              <label class="form-label">Vista previa:</label>
              <div class="border rounded p-2">
                <img id="previewImage" src="" alt="Vista previa" class="img-fluid rounded" style="max-height: 200px;">
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removePreview()">
                  <i class="bi bi-trash"></i> Quitar imagen
                </button>
              </div>
            </div>
          </div>

          <!-- Contador de caracteres -->
          <div class="row">
            <div class="col-6">
              <small class="text-muted">
                Título: <span id="contadorTitulo">0</span>/100
              </small>
            </div>
            <div class="col-6">
              <small class="text-muted">
                Contenido: <span id="contadorTexto">0</span>/500
              </small>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Cancelar
        </button>
        <button type="button" class="btn btn-outline-warning" id="btnGuardarBorrador">
          <i class="bi bi-archive"></i> Guardar como borrador
        </button>
        <button type="button" class="btn btn-primary" id="btnGuardarForo">
          <i class="bi bi-check-circle"></i> Crear Foro
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Script del modal incluido desde archivo externo -->
<script src="<?= JS_FOROS_NUEVO_MODAL ?>"></script>
