@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <h1>Comunidade das Piadas</h1> 
@stop

@section('content') 
    <div class="box">
        <div class="box-header">
          <h3 class="box-title">Piadas Cadastradas</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
              <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-header --> 
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>ID</th>
              <th>Categoria</th>
              <th>Likes</th>
              <th>Deslikes</th>
              <th>Descrição</th>
              <th>Opções</th>
            </tr>
            @foreach ($piadas as $pia)
              <tr>
              <td>{{ $pia->id }}</td>
              @foreach ($categorias as $cat)
                @if($pia->categoria_id == $cat->id)
                  <td>{{ $cat->nome }}</td>
                @endif
              @endforeach
              <td><span class="label label-success">{{ $pia->curtidas }}</span></td>
              <td><span class=" label label-danger">{{ $pia->deslikes }}</span></td>              
              <td>{{ $pia->descricao }}</td>
              <td> 
                <a class="btn btn-info" href= "editar/{{$pia->id}}">Editar</a> 
                <a class="btn btn-danger" href= "excluir/{{$pia->id}}">Excluir</a>
              </td>
              </tr>
             
            @endforeach
            
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
@stop