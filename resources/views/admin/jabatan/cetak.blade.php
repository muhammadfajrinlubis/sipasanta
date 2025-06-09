<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" sizes="196x196">
    <title>Sistem Informasi Pengaduan</title>
    <!-- Bootstrap CSS -->
    <!-- Optional: Custom CSS -->
    <style>
        .bg-primary {
            background-color: #007bff;
            color: #fff;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">List Data Jabatan</h2>
        <table border="1" cellspacing="0" width="100%">
            <thead class="bg-primary">
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th class="text-left">Nama Jabatan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @foreach($jabatan as $data)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $data->nama }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
