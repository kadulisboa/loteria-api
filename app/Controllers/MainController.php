<?php

namespace App\Controllers;

class MainController
{
    public function __construct()
    {
    }

    public function render() {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Rotas e Dependências</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }
                h1 {
                    color: #333;
                }
                .container {
                    background-color: #fff;
                    border-radius: 8px;
                    padding: 20px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    margin-bottom: 20px;
                }
                .route {
                    padding: 15px;
                    border-bottom: 1px solid #eaeaea;
                }
                .route:last-child {
                    border-bottom: none;
                }
                .route h2 {
                    font-size: 18px;
                    color: #007BFF;
                    margin-bottom: 10px;
                }
                .dependencies {
                    color: #555;
                    margin-left: 20px;
                }
                .dependencies ul {
                    list-style-type: none;
                    padding-left: 0;
                }
                .dependencies li {
                    margin-bottom: 5px;
                }
            </style>
        </head>
        <body>
            <h1>Rotas e Dependências</h1>
            
            <div class="container">
                <div class="route">
                    <h2>POST /sorteio/criar</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Nenhuma</li>
                        </ul>
                    </div>
                </div>
                
                <div class="route">
                    <h2>POST /sorteio/{id}/resultado</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Id do sorteio gerado na rota "POST /sorteio/criar"</li>
                        </ul>
                    </div>
                </div>
                
                <div class="route">
                    <h2>GET /sorteio/{id}/resultado</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Id do sorteio gerado na rota "POST /sorteio/criar"</li>
                        </ul>
                    </div>
                </div>
                
                <div class="route">
                    <h2>POST /bilhete/criar</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li><b>Enviar um json com:</b></li>
                           <li>Id do sorteio gerado na rota "POST /sorteio/criar"</li>
                            <li>Id do tripulante</li>
                            <li>quantidade ( no maximo 50 )</li>
                            <li>dezenas ( no maximo 10 )</li>
                        </ul>
                    </div>
                </div>
                
                <div class="route">
                    <h2>GET /migrate</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Servidor do banco de dados rodando</li>
                        </ul>
                    </div>
                </div>
        
                <div class="route">
                    <h2>GET /</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Nenhuma</li>
                        </ul>
                    </div>
                </div>
                
                <div class="route">
                    <h2>GET /server</h2>
                    <div class="dependencies">
                        <strong>Dependências:</strong>
                        <ul>
                            <li>Nenhuma</li>
                        </ul>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    public function renderJson() {
        echo json_encode(['Status' => 'Online']);
    }
}