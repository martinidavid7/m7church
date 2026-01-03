<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Cadastro de Visitante</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            max-width: 21cm;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10pt;
        }

        .header-info {
            flex: 1;
            text-align: center;
        }

        .header-info h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .header-info p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .header-title {
            border: 1px solid #000;
            padding: 5px;
            margin-left: 15px;
            min-width: 120px;
            text-align: center;
        }

        .header-title div {
            font-size: 9pt;
            font-style: italic;
        }

        .header-title h2 {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }

        .photo-box {
            width: 100px;
            height: 120px;
            border: 1px solid #000;
            float: right;
            margin: 0 0 10px 10px;
        }

        .section-title {
            font-weight: bold;
            font-style: italic;
            margin: 20px 0 10px 0;
            font-size: 14pt;
        }

        .form-field {
            margin-bottom: 12px;
            display: flex;
            align-items: baseline;
        }

        .form-field label {
            font-weight: normal;
            margin-right: 5px;
            white-space: nowrap;
        }

        .form-field .underline {
            flex: 1;
            border-bottom: 1px solid #000;
            min-height: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
        }

        .form-row > div {
            flex: 1;
        }

        .checkbox-group {
            display: inline-flex;
            gap: 15px;
            margin-left: 10px;
        }

        .checkbox-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            display: inline-block;
        }

        .footer {
            margin-top: 30px;
            font-style: italic;
            text-align: center;
            font-size: 10pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4F46E5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14pt;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background-color: #4338CA;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Imprimir Ficha</button>

    <div class="header">
        <div class="header-logo">
            @if($church && $church->logo)
                <img src="{{ asset($church->logo) }}" style="max-width: 100%; max-height: 100%;" alt="Logo">
            @else
                LOGO
            @endif
        </div>
        <div class="header-info">
            <h1>{{ $church ? strtoupper($church->church_name) : 'NOME DA IGREJA' }}</h1>
            <p>{{ $church && $church->address ? $church->address . ', ' . ($church->number ?? 's/n') : 'Endereço da Igreja' }}</p>
            <p>{{ $church && $church->city ? $church->city->name . ' - ' . ($church->city->uf->uf ?? '') : 'Cidade - UF' }}</p>
            <p>Pastor Titular: {{ $church && $church->pastor ? $church->pastor->name : '____________________________' }}</p>
        </div>
        <div class="header-title">
            <div>DIRETORIA</div>
            <h2>CADASTRO DE<br>VISITANTES</h2>
        </div>
    </div>

    <div class="photo-box"></div>

    <div class="section-title">CADASTRO DE VISITANTES</div>

    <div class="form-field">
        <label>Data da Visita:</label>
        <div class="underline"></div>
    </div>

    <div class="form-field">
        <label>Nome Completo:</label>
        <div class="underline"></div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label>Data de Nascimento:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field">
            <label>Sexo:</label>
            <span class="checkbox-group">
                <span class="checkbox-item">
                    <span class="checkbox"></span> M
                </span>
                <span class="checkbox-item">
                    <span class="checkbox"></span> F
                </span>
            </span>
        </div>
        <div class="form-field">
            <label>Estado Civil:</label>
            <div class="underline"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field" style="flex: 2;">
            <label>Endereço:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field" style="flex: 1;">
            <label>Nº:</label>
            <div class="underline"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label>Bairro:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field">
            <label>Cidade:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field" style="flex: 0.5;">
            <label>CEP:</label>
            <div class="underline"></div>
        </div>
    </div>

    <div class="form-field">
        <label>Complemento:</label>
        <div class="underline"></div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label>Telefone Fixo:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field">
            <label>Telefone Celular:</label>
            <div class="underline"></div>
        </div>
    </div>

    <div class="form-field">
        <label>E-mail:</label>
        <div class="underline"></div>
    </div>

    <div class="form-row">
        <div class="form-field">
            <label>Profissão:</label>
            <div class="underline"></div>
        </div>
        <div class="form-field">
            <label>Escolaridade:</label>
            <div class="underline"></div>
        </div>
    </div>

    <div class="form-field">
        <label>Aceita receber mensagens:</label>
        <span class="checkbox-group">
            <span class="checkbox-item">
                <span class="checkbox"></span> Sim
            </span>
            <span class="checkbox-item">
                <span class="checkbox"></span> Não
            </span>
        </span>
    </div>

    <div class="form-field">
        <label>Observações:</label>
        <div class="underline" style="min-height: 60px;"></div>
    </div>

    <div class="footer">
        "Tudo porém, seja feito com decência e ordem." (1 COR. 14:40)
    </div>
</body>
</html>
