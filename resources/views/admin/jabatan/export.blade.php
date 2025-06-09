<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="shortcut icon" sizes="196x196" href="/favicon.ico">
      <title>Sistem Informasi Pengaduan</title>
   </head>
   <body>
      <table width="100%" style="border-collapse: collapse;">
         <thead>
            <tr>
	            <th colspan="2" style="vertical-align: middle; text-align: center; font-size:20px; text-transform: uppercase;">List Data Jabatan</th>
	        </tr>
            <tr>
               <th width="5" style="vertical-align: middle; text-align: center; border: 1px solid black; background-color: #188ae2; color: #FFFFFF; font-weight: bold;">#</th>
               <th width="50" style="vertical-align: middle; text-align: left; border: 1px solid black; background-color: #188ae2; color: #FFFFFF; font-weight: bold;">Nama Jabatan</th>
            </tr>
         </thead>
         <tbody>
            <?php $no = 1; ?>
            @foreach($jabatan as $data)
            <tr>
               <td style="vertical-align: middle; text-align: center; border: 1px solid black;">{{ $no++ }}</td>
               <td style="vertical-align: middle; text-align: left; border: 1px solid black;">{{ $data->nama }}</td>
            </tr>
            @endforeach
         </tbody>
      </table>
   </body>
</html>