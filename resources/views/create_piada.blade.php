@extends('adminlte::page')

@section('title', 'AddPiada')

@section('content_header')
    <h1>Adicionar Piada</h1>
@stop

@section('content')
    <div class="row">
        <div class= "col-md-6">
            <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Insira sua Piada</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="POST">
                        <div class="box-body">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="exampleInputEmail1">Descrição</label>
                                <input type="text" class="form-control"  name="descricao_form" required>
                            </div>                                                                                    
                        </div>
                        <!-- /.box-body -->
        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop