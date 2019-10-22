@extends('adminlte::page')

@section('title', 'Editar Piada')

@section('content_header')
    <h1>Editar Piada</h1>
@stop

@section('content')
    <div class="row">
        <div class= "col-md-6">
            <div class="box box-primary"> 
                   
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="POST">
                        <div class="box-body">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="exampleInputEmail1">Id</label>
                            <input type="text" class="form-control"  name="id_form" value= "{{$id}}" readonly>
                            </div>  

                            <div class="form-group">
                                <label for="exampleInputEmail1">Descrição</label>
                                <input type="text" class="form-control"  name="descricao_form" value= "{{$descricao}}"  required>
                            </div>  
                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">Categoria</label>
                                <input type="text" class="form-control"  name="categoria_form" value= "{{$categoria_id}}"  required>
                            </div>  
                        </div>
                        <!-- /.box-body -->
        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop