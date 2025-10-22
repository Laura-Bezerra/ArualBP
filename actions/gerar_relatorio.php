<?php
session_start();
require('../includes/config.php');
require_once('../includes/ReportPDF.php');

date_default_timezone_set('America/Sao_Paulo');

$tipo = $_GET['tipo_relatorio'] ?? '';

switch ($tipo) {
    case 'usuarios_setor':
        require('relatorios/usuarios_setor.php');
        break;

    case 'bps_setor':
        require('relatorios/bps_setor.php');
        break;

    case 'bps_data':
        require('relatorios/bps_data.php');
        break;

    case 'usuarios':
        require('relatorios/usuarios.php');
        break;

    case 'setores':
        require('relatorios/setores.php');
        break;
    
    case 'categorias':
        require('relatorios/categorias.php');
        break;

    case 'inventario_setor':
        require('relatorios/inventario_setor.php');
        break;

    case 'solicitacoes':
        require('relatorios/solicitacoes.php');
        break;
        



    default:
        echo "Tipo de relatório inválido ou não implementado.";
        exit;
}
