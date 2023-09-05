<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }
        
        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }
        
        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
</head>
<body>
    <h1>Export Ke PDF Tabel Buku</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $dt)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->price }}</td>
                    <td>{{ $dt->desc }}</td>
                    <td>{{ $dt->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>