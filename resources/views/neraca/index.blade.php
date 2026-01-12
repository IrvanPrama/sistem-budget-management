<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca</title>
</head>
<style>
    .header{
        text-align:center;
    }
    h1{
        text-align:center;
    }
    body{
        font-family:'Calibri','Cambria', 'san-serif';
        background-color: #2f2f2f;
        color: white;
    }
    .card{
        text-transform: capitalize;
        margin: 0 auto;
        font-size: 20px;
        border-radius:20px;
        padding:20px;
        width: fit-content;
        height:fit-content;
        background-color:#737373;
    }
    table, th, td {
  border-top: 1px solid white;
  border-bottom: 1px solid white;
  border-collapse: collapse;
}
</style>
<body>
    <div class="header">
    <h1>Laporan Neraca</h1>
    <p>Periode: {{$periode}}</p>
</div>

    <div class="card">
    <table>
        <!-- ASSET -->
        <tr><th style="text-align:left;" colspan="2">Asset</th></tr>
        @foreach ($asset as $a)
            <tr>
                <td style="text-align:center; column-width: 100px;">{{$a->akun_name}}</td>
                <td style="text-align:center; column-width: 100px;">Rp {{number_format($a->saldo)}}</td>
            </tr>
        @endforeach

        <tr>
               <td style="text-align:center; column-width: 100px;"> Total Asset</td>
               <td style="text-align:center; column-width: 100px;"> Rp {{ number_format(($asset->sum('saldo'))) }}</td>

        </tr>
<tr><td><br></td></tr>
        <!-- END ASSET -->
         <!-- Liabilitas -->
<tr>       <th style="text-align:left;" colspan="2">Kewajiban & Ekuitas</th></tr>
        @foreach ($liabilitas as $l)
            <tr>
                <td style="text-align:center; column-width: 100px;">{{$l->akun_name}}</td>
                <td style="text-align:center; column-width: 100px;">Rp {{number_format($l->saldo)}}</td>
            </tr>
        @endforeach
         <!-- Equitas -->
           @foreach ($equitas as $e)
            <tr>
                <td style="text-align:center; column-width: 100px;">{{$e->akun_name}}</td>
                <td style="text-align:center; column-width: 100px;">Rp {{number_format($e->saldo)}}</td>
            </tr>
        @endforeach
        <tr>
               <td style="text-align:center; column-width: 100px;"> Total</td>
               <td style="text-align:center; column-width: 100px;"> Rp {{ number_format(($liabilitas->sum('saldo'))+($equitas->sum('saldo'))) }}</td>

        </tr>
    </table>
    </div>
</body>
</html>