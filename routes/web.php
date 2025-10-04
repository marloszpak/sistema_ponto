<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\PontoController;

Route::get('/', function(){ return redirect()->route('funcionarios.listar'); });

// Funcionários (páginas)
Route::get('/funcionarios', [FuncionarioController::class,'listar'])->name('funcionarios.listar');
Route::post('/funcionarios/salvar', [FuncionarioController::class,'salvar'])->name('funcionarios.salvar');
Route::get('/funcionarios/{funcionario}/editar', [FuncionarioController::class,'editar'])->name('funcionarios.editar');
Route::put('/funcionarios/{funcionario}', [FuncionarioController::class,'atualizar'])->name('funcionarios.atualizar');
Route::delete('/funcionarios/{funcionario}', [FuncionarioController::class,'excluir'])->name('funcionarios.excluir');
Route::get('/funcionarios/{funcionario}/json', [FuncionarioController::class, 'mostrar'])->name('funcionarios.json');

// Ponto
Route::get('/ponto', [PontoController::class,'telaBaterPonto'])->name('ponto.tela');
Route::post('/ponto/registrar', [PontoController::class,'registrar'])->name('ponto.registrar');

// Relatórios
Route::get('/relatorios/batidas', [PontoController::class,'relatorio'])->name('relatorios.batidas');

// exports (stubs)
Route::get('/relatorios/batidas/exportar-csv', [PontoController::class,'exportarCsv'])->name('relatorios.batidas.exportar.csv');
Route::get('/relatorios/batidas/exportar-pdf', [PontoController::class,'exportarPdf'])->name('relatorios.batidas.exportar.pdf');

// rota para verificar unicidade do CPF via AJAX
Route::get('/funcionarios/verificar-cpf', [\App\Http\Controllers\FuncionarioController::class, 'verificarCpf'])->name('funcionarios.verificarCpf');
