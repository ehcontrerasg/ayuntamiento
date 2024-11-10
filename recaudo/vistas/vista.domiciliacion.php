
<!doctype html >
    <head>
        <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, ini
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/domiciliacion.css?<?echo time();?>">
        <script src="../js/domiciliacion.js?<?echo time();?>"></script>
    </head>
    <header>
        <div style=" background-color: #ff8000;width: 100%;padding: 0.5px;margin: 4px;color:#FFFFFF;"><center><h4>Pagos Recurrentes (Domiciliación)</h4></center></div>
    </header>
    <body>
        <div class="container-fluid">
            <button id="btnGeneraDomiciliacion" class="btn btn-primary">Generar domiciliacion</button>
            <div id="dvDatatable">
                <table id="dataTable"></table>
            </div>
        </div>
        <form id="frmSales" action="https://ecommerce.cardnet.com.do/api/payment/transactions/sales" method="POST">
            <input type="hidden" name="token"            value=""/>
            <input type="hidden" name="idempotency_key"  value=""/>
            <input type="hidden" name="merchant_id"      value=""/>
            <input type="hidden" name="terminal_id"      value=""/>
            <input type="hidden" name="card_number"      value=""/>
            <input type="hidden" name="environment"      value=""/>
            <input type="hidden" name="expiration_date"  value=""/>
            <input type="hidden" name="cvv"              value=""/>
            <input type="hidden" name="amount"           value=""/>
            <input type="hidden" name="currency"         value=""/>
            <input type="hidden" name="invoice_number"   value=""/>
            <input type="hidden" name="client_ip"        value=""/>
            <input type="hidden" name="reference_number" value=""/>
        </form>
    </body>

</html>