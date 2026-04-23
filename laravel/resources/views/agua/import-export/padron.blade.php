@extends('components.layouts.app')

@section('title', 'Importación de padrón')

@section('content')
    <x-ui.page-header
        title="Importación / Exportación de Padrón"
        subtitle="Carga masiva de residentes desde archivo y exportación del padrón vigente." />

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <x-ui.card title="Importar padrón" icon="upload_file">
                <form class="d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Archivo (CSV o Excel)</label>
                        <input type="file" class="form-control" accept=".csv,.xls,.xlsx">
                        <small class="text-body-secondary">
                            Tamaño máximo según configuración del sistema.
                            El proceso se ejecuta en cola y notifica al finalizar.
                        </small>
                    </div>

                    <x-ui.alert variant="info" icon="info">
                        La importación valida duplicados por código de usuario y tarifa.
                    </x-ui.alert>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <x-ui.icon name="cloud_upload" size="sm" /> Iniciar importación
                        </button>
                        <a href="#" class="btn btn-outline-secondary">Descargar plantilla</a>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <div class="col-12 col-lg-6">
            <x-ui.card title="Exportar padrón" icon="download">
                <p class="text-body-secondary">
                    Genera un archivo con el padrón actual y el estado de deuda por residente.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <x-ui.icon name="description" size="sm" /> CSV
                    </a>
                    <a href="#" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <x-ui.icon name="table_chart" size="sm" /> Excel
                    </a>
                    <a href="#" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <x-ui.icon name="picture_as_pdf" size="sm" /> PDF
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
