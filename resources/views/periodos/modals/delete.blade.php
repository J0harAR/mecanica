@can('borrar-periodo') 
<!-- Modal Eliminar -->
<div class="modal fade" id="modal-delete{{ $periodo->clave }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Confirmación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ¿Estás seguro de querer eliminar el periodo: {{$periodo->clave}}?
            </div>
            <div class="modal-footer">
              <form action="{{route('periodos.destroy',['id'=>$periodo->clave])}}" method="POST">
                @csrf
                @method('delete')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Eliminar -->
      @endcan         